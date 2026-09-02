<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanPublikController extends Controller
{
    // Halaman Beranda Utama: Form Peminjaman Mandiri, Keranjang Belanja, & Katalog Barang Siap Pinjam
    public function index()
    {
        $barangs = Barang::with(['ruangan', 'kategori'])
            ->where('bisa_dipinjam', true)
            ->orderBy('nama_barang')
            ->get();

        return view('publik.index', compact('barangs'));
    }

    // Pemrosesan Pengajuan Peminjaman Mandiri (Mendukung Multi-Item Keranjang)
    public function store(Request $request)
    {
        $request->validate([
            'kategori_peminjam' => 'required|in:Dosen,Mahasiswa,Staf / Tendik',
            'nama_peminjam'     => 'required|string|max:255',
            'nomor_identitas'   => 'required|string|max:100',
            'prodi_unit'        => 'required|string|max:150',
            'kontak_peminjam'   => 'required|string|max:50',
            'tanggal_pinjam'    => 'required|date',
            'tenggat_kembali'   => 'required|date|after_or_equal:tanggal_pinjam',
            'keperluan'         => 'required|string|max:500',
        ], [
            'nama_peminjam.required'     => 'Nama peminjam wajib diisi.',
            'nomor_identitas.required'   => 'NIM / NIP / NIDN wajib diisi.',
            'kontak_peminjam.required'   => 'Nomor WhatsApp wajib diisi untuk konfirmasi persetujuan.',
            'tenggat_kembali.after_or_equal' => 'Tanggal pengembalian tidak boleh mendahului tanggal peminjaman.',
        ]);

        // Parsing item keranjang
        $cartItems = [];
        if ($request->filled('cart_data')) {
            $decoded = json_decode($request->cart_data, true);
            if (is_array($decoded) && count($decoded) > 0) {
                $cartItems = $decoded;
            }
        }

        // Fallback jika pengajuan single item
        if (empty($cartItems) && $request->filled('barang_id')) {
            $cartItems[] = [
                'barang_id' => $request->barang_id,
                'jumlah'    => (int)($request->jumlah ?? 1),
            ];
        }

        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Keranjang peminjaman masih kosong! Silakan tambahkan minimal 1 aset ke keranjang.');
        }

        // Validasi stok & izin pinjam setiap barang
        $totalJumlah = 0;
        $firstBarangId = null;
        $itemsToInsert = [];

        foreach ($cartItems as $item) {
            $bId = $item['barang_id'] ?? $item['id'] ?? null;
            $qty = (int)($item['jumlah'] ?? $item['qty'] ?? 1);

            if (!$bId || $qty < 1) continue;

            $barang = Barang::find($bId);
            if (!$barang) {
                return redirect()->back()->with('error', 'Salah satu aset yang dipilih tidak ditemukan dalam sistem.');
            }

            if (!$barang->bisa_dipinjam) {
                return redirect()->back()->with('error', "Aset [{$barang->nama_barang}] tidak diizinkan untuk dipinjam.");
            }

            if ($qty > $barang->jumlah) {
                return redirect()->back()->with('error', "Jumlah pinjam untuk [{$barang->nama_barang}] ({$qty} unit) melebihi stok yang tersedia ({$barang->jumlah} unit)!");
            }

            if (!$firstBarangId) {
                $firstBarangId = $barang->id;
            }

            $totalJumlah += $qty;
            $itemsToInsert[] = [
                'barang_id' => $barang->id,
                'jumlah'    => $qty,
            ];
        }

        // Generate Kode Peminjaman Unik: PJM-YYYYMM-XXXX
        $prefix = 'PJM-' . date('Ym');
        $countThisMonth = Peminjaman::where('kode_peminjaman', 'like', "{$prefix}-%")->count();
        $nextSeq = sprintf('%04d', $countThisMonth + 1);
        $kodePeminjaman = "{$prefix}-{$nextSeq}";

        while (Peminjaman::where('kode_peminjaman', $kodePeminjaman)->exists()) {
            $countThisMonth++;
            $nextSeq = sprintf('%04d', $countThisMonth + 1);
            $kodePeminjaman = "{$prefix}-{$nextSeq}";
        }

        DB::beginTransaction();
        try {
            // 1. Simpan Header Transaksi Peminjaman
            $peminjaman = Peminjaman::create([
                'kode_peminjaman'   => $kodePeminjaman,
                'barang_id'         => $firstBarangId,
                'kategori_peminjam' => $request->kategori_peminjam,
                'nama_peminjam'     => $request->nama_peminjam,
                'nomor_identitas'   => $request->nomor_identitas,
                'prodi_unit'        => $request->prodi_unit,
                'kontak_peminjam'   => $request->kontak_peminjam,
                'jumlah'            => $totalJumlah,
                'tanggal_pinjam'    => $request->tanggal_pinjam,
                'tenggat_kembali'   => $request->tenggat_kembali,
                'status'            => 'Menunggu',
                'keperluan'         => $request->keperluan,
            ]);

            // 2. Simpan Rincian Semua Item di Keranjang
            foreach ($itemsToInsert as $ins) {
                PeminjamanDetail::create([
                    'peminjaman_id' => $peminjaman->id,
                    'barang_id'     => $ins['barang_id'],
                    'jumlah'        => $ins['jumlah'],
                ]);
            }

            DB::commit();
            return redirect()->route('publik.sukses', $peminjaman->kode_peminjaman);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses pengajuan: ' . $e->getMessage());
        }
    }

    // Tampilan Tiket / Bukti Pengajuan Digital
    public function sukses($kode)
    {
        $peminjaman = Peminjaman::with(['barang.ruangan', 'details.barang.ruangan', 'penyetujui', 'penyerah'])
            ->where('kode_peminjaman', $kode)
            ->firstOrFail();

        return view('publik.sukses', compact('peminjaman'));
    }

    // Fitur Cek / Lacak Status Peminjaman Mandiri
    public function lacak(Request $request)
    {
        $results = null;
        $keyword = $request->input('keyword');

        if ($request->filled('keyword')) {
            $keyword = trim($keyword);
            $results = Peminjaman::with(['barang.ruangan', 'details.barang.ruangan', 'penyetujui'])
                ->where('nomor_identitas', $keyword)
                ->orWhere('kode_peminjaman', $keyword)
                ->latest()
                ->get();
        }

        return view('publik.lacak', compact('results', 'keyword'));
    }
}
