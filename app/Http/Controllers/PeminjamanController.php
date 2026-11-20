<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Mutasi;
use App\Models\Peminjaman;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // Dashboard Petugas / Kepala Sarpras
    public function index(Request $request)
    {
        // Update status terlambat bagi peminjaman aset yang sudah diambil tetapi melewati tenggat kembali
        Peminjaman::where('status', 'Diambil')
            ->where('tenggat_kembali', '<', now()->toDateString())
            ->update(['status' => 'Terlambat']);

        $activeTab = $request->input('tab', 'aset');
        if (!in_array($activeTab, ['aset', 'ruangan'])) {
            $activeTab = 'aset';
        }

        // ==========================================
        // 1. DATA PEMINJAMAN ASET SARPRAS
        // ==========================================
        $queryAset = Peminjaman::with(['barang.ruangan', 'details.barang.ruangan', 'penyetujui', 'penyerah']);

        // Filter status aset (hanya jika tab aset aktif atau tanpa tab)
        if (($activeTab === 'aset' || !$request->filled('tab')) && $request->filled('status')) {
            $queryAset->where('status', $request->status);
        }

        // Filter pencarian aset
        if (($activeTab === 'aset' || !$request->filled('tab')) && $request->filled('search')) {
            $search = $request->search;
            $queryAset->where(function($q) use ($search) {
                $q->where('nama_peminjam', 'like', "%{$search}%")
                  ->orWhere('nomor_identitas', 'like', "%{$search}%")
                  ->orWhere('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhere('prodi_unit', 'like', "%{$search}%")
                  ->orWhereHas('barang', function($qb) use ($search) {
                      $qb->where('nama_barang', 'like', "%{$search}%")
                         ->orWhere('kode_barang', 'like', "%{$search}%");
                  })
                  ->orWhereHas('details.barang', function($qb) use ($search) {
                      $qb->where('nama_barang', 'like', "%{$search}%")
                         ->orWhere('kode_barang', 'like', "%{$search}%");
                  });
            });
        }

        $peminjamansAset = $queryAset->latest()->get();

        // Statistik Dashboard Peminjaman Aset
        $statsAset = [
            'total'       => Peminjaman::count(),
            'menunggu'    => Peminjaman::where('status', 'Menunggu')->count(),
            'disetujui'   => Peminjaman::where('status', 'Disetujui')->count(),
            'diambil'     => Peminjaman::where('status', 'Diambil')->count(),
            'terlambat'   => Peminjaman::where('status', 'Terlambat')->count(),
            'kembali'     => Peminjaman::where('status', 'Kembali')->count(),
            'ditolak'     => Peminjaman::where('status', 'Ditolak')->count(),
        ];

        // Daftar barang yang bisa dipinjam untuk form internal
        $barangBisaDipinjam = Barang::with('ruangan')
            ->where('bisa_dipinjam', true)
            ->where('jumlah', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        // ==========================================
        // 2. DATA PEMINJAMAN RUANGAN KEGIATAN
        // ==========================================
        $queryRuangan = PeminjamanRuangan::with(['ruangan', 'penyetujui', 'penyerah']);

        // Filter status ruangan (jika tab ruangan aktif)
        if ($activeTab === 'ruangan' && $request->filled('status')) {
            $queryRuangan->where('status', $request->status);
        }

        // Filter tanggal ruangan (jika tab ruangan aktif)
        if ($activeTab === 'ruangan' && $request->filled('tanggal')) {
            $queryRuangan->where('tanggal_pemakaian', $request->tanggal);
        }

        // Filter pencarian ruangan
        if ($activeTab === 'ruangan' && $request->filled('search')) {
            $search = $request->search;
            $queryRuangan->where(function($q) use ($search) {
                $q->where('nama_peminjam', 'like', "%{$search}%")
                  ->orWhere('nomor_identitas', 'like', "%{$search}%")
                  ->orWhere('kode_booking', 'like', "%{$search}%")
                  ->orWhere('prodi_unit', 'like', "%{$search}%")
                  ->orWhereHas('ruangan', function($qb) use ($search) {
                      $qb->where('nama_ruangan', 'like', "%{$search}%")
                         ->orWhere('kode_ruangan', 'like', "%{$search}%");
                  });
            });
        }

        $peminjamansRuangan = $queryRuangan->latest('tanggal_pemakaian')->latest('jam_mulai')->get();

        // Statistik Dashboard Booking Ruangan
        $statsRuangan = [
            'total'       => PeminjamanRuangan::count(),
            'menunggu'    => PeminjamanRuangan::where('status', 'Menunggu')->count(),
            'disetujui'   => PeminjamanRuangan::where('status', 'Disetujui')->count(),
            'digunakan'   => PeminjamanRuangan::where('status', 'Digunakan')->count(),
            'selesai'     => PeminjamanRuangan::where('status', 'Selesai')->count(),
            'ditolak'     => PeminjamanRuangan::where('status', 'Ditolak')->count(),
        ];

        // Daftar ruangan yang bisa dipinjam untuk form booking internal
        $ruanganBisaDipinjam = Ruangan::where('bisa_dipinjam', true)->orderBy('nama_ruangan')->get();

        // Kompatibilitas variabel lama
        $peminjamans = $peminjamansAset;
        $stats = $statsAset;

        return view('peminjaman.index', compact(
            'peminjamansAset',
            'statsAset',
            'barangBisaDipinjam',
            'peminjamansRuangan',
            'statsRuangan',
            'ruanganBisaDipinjam',
            'activeTab',
            'peminjamans',
            'stats'
        ));
    }

    // Aksi 1: Approval / Persetujuan oleh Kepala Sarpras
    public function approve($id)
    {
        $peminjaman = Peminjaman::with(['barang', 'details.barang'])->findOrFail($id);

        if ($peminjaman->status !== 'Menunggu') {
            return redirect()->back()->with('error', 'Status peminjaman ini bukan lagi menunggu persetujuan.');
        }

        // Cek stok seluruh item dalam transaksi
        if ($peminjaman->details->count() > 0) {
            foreach ($peminjaman->details as $detail) {
                if ($detail->barang && $detail->barang->jumlah < $detail->jumlah) {
                    return redirect()->back()->with('error', "Stok untuk [{$detail->barang->nama_barang}] tidak mencukupi ({$detail->barang->jumlah} unit tersisa, diajukan {$detail->jumlah} unit)!");
                }
            }
        } elseif ($peminjaman->barang && $peminjaman->barang->jumlah < $peminjaman->jumlah) {
            return redirect()->back()->with('error', "Stok barang saat ini ({$peminjaman->barang->jumlah} unit) tidak mencukupi permohonan ({$peminjaman->jumlah} unit)!");
        }

        $peminjaman->update([
            'status'         => 'Disetujui',
            'disetujui_oleh' => auth()->id(),
            'disetujui_pada' => now(),
        ]);

        return redirect()->back()->with('success', "Permohonan [{$peminjaman->kode_peminjaman}] berhasil DISETUJUI oleh Kepala Sarpras! Pemohon dapat mengambil barang fisik di ruangan lab/sarpras.");
    }

    // Aksi 1b: Penolakan oleh Kepala Sarpras
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'Menunggu') {
            return redirect()->back()->with('error', 'Permohonan ini sudah diproses sebelumnya.');
        }

        $peminjaman->update([
            'status'           => 'Ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
            'disetujui_oleh'   => auth()->id(),
            'disetujui_pada'   => now(),
        ]);

        return redirect()->back()->with('success', "Permohonan [{$peminjaman->kode_peminjaman}] telah DITOLAK dengan alasan: {$request->alasan_penolakan}.");
    }

    // Aksi 2: Serah Terima Barang / Barang Sudah Diambil oleh Peminjam (Potong Stok Seluruh Item)
    public function serahkan($id)
    {
        $peminjaman = Peminjaman::with(['barang.ruangan', 'details.barang.ruangan'])->findOrFail($id);

        if (!in_array($peminjaman->status, ['Disetujui', 'Menunggu'])) {
            return redirect()->back()->with('error', 'Barang hanya dapat diserahkan jika permohonan telah disetujui.');
        }

        DB::beginTransaction();
        try {
            // Validasi ketersediaan stok fisik seluruh item di keranjang
            if ($peminjaman->details->count() > 0) {
                foreach ($peminjaman->details as $detail) {
                    if ($detail->barang) {
                        if ($detail->barang->stok_tersedia < $detail->jumlah) {
                            throw new \Exception("Stok fisik aset [{$detail->barang->nama_barang}] saat ini tidak mencukupi (Tersedia: {$detail->barang->stok_tersedia} unit)!");
                        }

                        // Catat Riwayat Mutasi Peminjaman (tanpa mengubah total registrasi aset di KIR)
                        Mutasi::create([
                            'barang_id'       => $detail->barang_id,
                            'jenis_mutasi'    => 'Dipinjam',
                            'ruangan_asal_id' => $detail->barang->ruangan_id,
                            'jumlah'          => $detail->jumlah,
                            'keterangan'      => "Barang diambil oleh {$peminjaman->nama_peminjam} ({$peminjaman->nomor_identitas}) - Keperluan: {$peminjaman->keperluan}",
                        ]);
                    }
                }
            } elseif ($peminjaman->barang) {
                if ($peminjaman->barang->stok_tersedia < $peminjaman->jumlah) {
                    throw new \Exception("Stok fisik aset [{$peminjaman->barang->nama_barang}] saat ini tidak mencukupi (Tersedia: {$peminjaman->barang->stok_tersedia} unit)!");
                }

                // Catat Riwayat Mutasi Peminjaman
                Mutasi::create([
                    'barang_id'       => $peminjaman->barang_id,
                    'jenis_mutasi'    => 'Dipinjam',
                    'ruangan_asal_id' => $peminjaman->barang->ruangan_id,
                    'jumlah'          => $peminjaman->jumlah,
                    'keterangan'      => "Barang diambil oleh {$peminjaman->nama_peminjam} ({$peminjaman->nomor_identitas}) - Keperluan: {$peminjaman->keperluan}",
                ]);
            }

            // Update status menjadi 'Diambil' (Barang Sudah Diambil)
            $peminjaman->update([
                'status'          => 'Diambil',
                'diserahkan_oleh' => auth()->id(),
                'tanggal_diambil' => now(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', "Seluruh barang dalam permohonan [{$peminjaman->kode_peminjaman}] telah resmi diserahkan ke {$peminjaman->nama_peminjam}! Status: [Barang Sudah Diambil].");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyerahkan barang: ' . $e->getMessage());
        }
    }

    // Aksi 3: Proses Pengembalian Barang (Kembalikan Stok Seluruh Item)
    public function kembalikan(Request $request, $id)
    {
        $request->validate([
            'tanggal_kembali' => 'required|date',
            'kondisi_kembali' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'catatan'         => 'nullable|string|max:500',
        ]);

        $peminjaman = Peminjaman::with(['barang.ruangan', 'details.barang.ruangan'])->findOrFail($id);

        if ($peminjaman->status === 'Kembali') {
            return redirect()->back()->with('error', 'Peminjaman ini sudah tercatat telah dikembalikan sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Update status pengembalian
            $peminjaman->update([
                'status'          => 'Kembali',
                'tanggal_kembali' => $request->tanggal_kembali,
                'kondisi_kembali' => $request->kondisi_kembali,
            ]);

            $ket = "Dikembalikan oleh {$peminjaman->nama_peminjam} (Kondisi: {$request->kondisi_kembali})";
            if ($request->filled('catatan')) {
                $ket .= " - Catatan: " . $request->catatan;
            }

            // Catat log mutasi pengembalian
            if ($peminjaman->details->count() > 0) {
                foreach ($peminjaman->details as $detail) {
                    if ($detail->barang) {
                        $detail->update(['kondisi_kembali' => $request->kondisi_kembali]);

                        Mutasi::create([
                            'barang_id'       => $detail->barang_id,
                            'jenis_mutasi'    => 'Dikembalikan',
                            'ruangan_asal_id' => $detail->barang->ruangan_id,
                            'jumlah'          => $detail->jumlah,
                            'keterangan'      => $ket,
                        ]);
                    }
                }
            } elseif ($peminjaman->barang) {
                Mutasi::create([
                    'barang_id'       => $peminjaman->barang_id,
                    'jenis_mutasi'    => 'Dikembalikan',
                    'ruangan_asal_id' => $peminjaman->barang->ruangan_id,
                    'jumlah'          => $peminjaman->jumlah,
                    'keterangan'      => $ket,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', "Seluruh aset dalam peminjaman [{$peminjaman->kode_peminjaman}] berhasil dikembalikan! Stok tersedia otomatis pulih kembali.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }

    // Hapus data transaksi
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();

        return redirect()->back()->with('success', 'Data peminjaman berhasil dihapus!');
    }
}
