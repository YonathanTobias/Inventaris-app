<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanPublikController extends Controller
{
    // Halaman Beranda Utama: Form Peminjaman Mandiri, Booking Ruangan, Keranjang Belanja, & Katalog
    public function index()
    {
        $barangs = Barang::with(['ruangan', 'kategori'])
            ->where('bisa_dipinjam', true)
            ->orderBy('nama_barang')
            ->get();

        $ruangans = Ruangan::where('bisa_dipinjam', true)
            ->orderBy('nama_ruangan')
            ->get();

        $kategoris = Kategori::whereHas('barangs', function ($query) {
            $query->where('bisa_dipinjam', true);
        })->orderBy('nama_kategori')->get();

        return view('publik.index', compact('barangs', 'ruangans', 'kategoris'));
    }

    // Pemrosesan Pengajuan Peminjaman Mandiri (Mendukung Multi-Item Keranjang)
    public function store(Request $request)
    {
        // 1. Honeypot check (jika bot spammer mengisi input jebakan tersembunyi)
        if ($request->filled('_hp_security_check')) {
            // Berikan respons sukses palsu agar bot mengira berhasil dan berhenti spamming tanpa menyentuh database
            return redirect()->route('home')->with('success', 'Permohonan peminjaman mandiri Anda telah diterima.');
        }

        $request->validate([
            'kategori_peminjam' => 'required|in:Dosen,Mahasiswa,Staf / Tendik',
            'nama_peminjam'     => [
                'required',
                'string',
                'max:100',
                'regex:/^[\pL\s.,\'’`\-]+$/u',
                function ($attribute, $value, $fail) {
                    if (preg_match('/(pg_sleep|dbms_pipe|waitfor|select|union|benchmark|--|\/\*)/i', $value)) {
                        $fail('Nama peminjam mengandung format yang tidak diizinkan.');
                    }
                },
            ],
            'nomor_identitas'   => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9\/\-.]+$/',
                function ($attribute, $value, $fail) {
                    if (preg_match('/(select|union|pg_sleep|dbms_pipe|--|\/\*)/i', $value)) {
                        $fail('Nomor identitas mengandung format yang tidak diizinkan.');
                    }
                },
            ],
            'prodi_unit'        => 'required|string|max:150',
            'kontak_peminjam'   => 'required|string|max:30|regex:/^[0-9+\-\s()]+$/',
            'tanggal_pinjam'    => 'required|date',
            'tenggat_kembali'   => 'required|date|after_or_equal:tanggal_pinjam',
            'keperluan'         => [
                'required',
                'string',
                'max:500',
                function ($attribute, $value, $fail) {
                    if (preg_match('/(pg_sleep|dbms_pipe|waitfor|benchmark\(|union\s+select)/i', $value)) {
                        $fail('Keperluan peminjaman mengandung karakter tidak valid.');
                    }
                },
            ],
        ], [
            'nama_peminjam.required'         => 'Nama peminjam wajib diisi.',
            'nama_peminjam.regex'            => 'Nama peminjam hanya boleh berisi huruf, spasi, dan tanda baca nama.',
            'nomor_identitas.required'       => 'NIM / NIP / NIDN wajib diisi.',
            'nomor_identitas.regex'          => 'Format nomor identitas hanya boleh berisi huruf, angka, dan garis miring.',
            'kontak_peminjam.required'       => 'Nomor WhatsApp wajib diisi untuk konfirmasi persetujuan.',
            'kontak_peminjam.regex'          => 'Format nomor WhatsApp tidak valid.',
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

            if ($qty > $barang->stok_tersedia) {
                return redirect()->back()->with('error', "Jumlah pinjam untuk [{$barang->nama_barang}] ({$qty} unit) melebihi stok yang tersedia saat ini ({$barang->stok_tersedia} unit)!");
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

    // Tampilan Tiket / Bukti Pengajuan Digital Aset
    public function sukses($kode)
    {
        $peminjaman = Peminjaman::with(['barang.ruangan', 'details.barang.ruangan', 'penyetujui', 'penyerah'])
            ->where('kode_peminjaman', $kode)
            ->firstOrFail();

        return view('publik.sukses', compact('peminjaman'));
    }

    // Fitur Cek / Lacak Status Peminjaman Mandiri (Aset & Ruangan)
    public function lacak(Request $request)
    {
        $results = null;
        $resultsRuangan = null;
        $keyword = $request->input('keyword');

        if ($request->filled('keyword')) {
            $keyword = trim($keyword);
            
            // Cari data peminjaman aset
            $results = Peminjaman::with(['barang.ruangan', 'details.barang.ruangan', 'penyetujui'])
                ->where('nomor_identitas', $keyword)
                ->orWhere('kode_peminjaman', $keyword)
                ->latest()
                ->get();

            // Cari data booking ruangan
            $resultsRuangan = PeminjamanRuangan::with(['ruangan', 'penyetujui'])
                ->where('nomor_identitas', $keyword)
                ->orWhere('kode_booking', $keyword)
                ->latest()
                ->get();
        }

        return view('publik.lacak', compact('results', 'resultsRuangan', 'keyword'));
    }
}
