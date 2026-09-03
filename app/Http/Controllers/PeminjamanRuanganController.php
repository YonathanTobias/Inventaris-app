<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class PeminjamanRuanganController extends Controller
{
    // Dashboard Petugas / Kepala Sarpras: Kelola Booking Ruangan
    public function index(Request $request)
    {
        $query = PeminjamanRuangan::with(['ruangan', 'penyetujui', 'penyerah']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->where('tanggal_pemakaian', $request->tanggal);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
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

        $peminjamans = $query->latest('tanggal_pemakaian')->latest('jam_mulai')->get();

        // Statistik Dashboard Booking Ruangan
        $stats = [
            'total'       => PeminjamanRuangan::count(),
            'menunggu'    => PeminjamanRuangan::where('status', 'Menunggu')->count(),
            'disetujui'   => PeminjamanRuangan::where('status', 'Disetujui')->count(),
            'digunakan'   => PeminjamanRuangan::where('status', 'Digunakan')->count(),
            'selesai'     => PeminjamanRuangan::where('status', 'Selesai')->count(),
            'ditolak'     => PeminjamanRuangan::where('status', 'Ditolak')->count(),
        ];

        // Daftar ruangan yang bisa dipinjam untuk form booking internal
        $ruanganBisaDipinjam = Ruangan::where('bisa_dipinjam', true)->orderBy('nama_ruangan')->get();

        return view('peminjaman_ruangan.index', compact('peminjamans', 'stats', 'ruanganBisaDipinjam'));
    }

    // Aksi 1: Approval / Persetujuan oleh Kepala Sarpras
    public function approve($id)
    {
        $booking = PeminjamanRuangan::with('ruangan')->findOrFail($id);

        if ($booking->status !== 'Menunggu') {
            return redirect()->back()->with('error', 'Status booking ini sudah diproses sebelumnya.');
        }

        // Cek apakah ada jadwal bentrok dengan yang sudah disetujui sebelumnya
        $overlap = PeminjamanRuangan::where('ruangan_id', $booking->ruangan_id)
            ->where('id', '!=', $booking->id)
            ->where('tanggal_pemakaian', $booking->tanggal_pemakaian)
            ->whereIn('status', ['Disetujui', 'Digunakan'])
            ->where(function($q) use ($booking) {
                $q->where('jam_mulai', '<', $booking->jam_selesai)
                  ->where('jam_selesai', '>', $booking->jam_mulai);
            })
            ->first();

        if ($overlap) {
            $jam = date('H:i', strtotime($overlap->jam_mulai)) . ' - ' . date('H:i', strtotime($overlap->jam_selesai));
            return redirect()->back()->with('error', "Gagal menyetujui! Terdapat jadwal yang bertabrakan pada jam {$jam} atas nama {$overlap->nama_peminjam}.");
        }

        $booking->update([
            'status'         => 'Disetujui',
            'disetujui_oleh' => auth()->id(),
            'disetujui_pada' => now(),
        ]);

        return redirect()->back()->with('success', "Permohonan booking [{$booking->kode_booking}] untuk Ruangan {$booking->ruangan->nama_ruangan} berhasil DISETUJUI oleh Kepala Sarpras!");
    }

    // Aksi 1b: Penolakan oleh Kepala Sarpras
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $booking = PeminjamanRuangan::findOrFail($id);

        if ($booking->status !== 'Menunggu') {
            return redirect()->back()->with('error', 'Permohonan ini sudah diproses sebelumnya.');
        }

        $booking->update([
            'status'           => 'Ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
            'disetujui_oleh'   => auth()->id(),
            'disetujui_pada'   => now(),
        ]);

        return redirect()->back()->with('success', "Permohonan booking [{$booking->kode_booking}] telah DITOLAK dengan alasan: {$request->alasan_penolakan}.");
    }

    // Aksi 2: Ruangan Dibuka oleh Petugas / Ruangan Mulai Digunakan
    public function serahkan($id)
    {
        $booking = PeminjamanRuangan::with('ruangan')->findOrFail($id);

        if (!in_array($booking->status, ['Disetujui', 'Menunggu'])) {
            return redirect()->back()->with('error', 'Ruangan hanya dapat dibuka jika permohonan telah disetujui.');
        }

        $booking->update([
            'status'          => 'Digunakan',
            'diserahkan_oleh' => auth()->id(),
            'waktu_masuk'     => now(),
        ]);

        return redirect()->back()->with('success', "Ruangan {$booking->ruangan->nama_ruangan} telah dibuka oleh petugas untuk kegiatan {$booking->nama_peminjam}! Status: [Sedang Digunakan].");
    }

    // Aksi 3: Selesai Pemakaian & Ruangan Dikunci Kembali oleh Petugas
    public function selesai(Request $request, $id)
    {
        $booking = PeminjamanRuangan::with('ruangan')->findOrFail($id);

        if ($booking->status === 'Selesai') {
            return redirect()->back()->with('error', 'Peminjaman ruangan ini sudah selesai sebelumnya.');
        }

        $booking->update([
            'status'          => 'Selesai',
            'waktu_selesai'   => now(),
            'catatan_kondisi' => $request->catatan_kondisi,
        ]);

        return redirect()->back()->with('success', "Pemakaian ruangan {$booking->ruangan->nama_ruangan} telah selesai dan ruangan telah dikunci kembali oleh petugas!");
    }

    // Hapus data transaksi booking
    public function destroy($id)
    {
        $booking = PeminjamanRuangan::findOrFail($id);
        $booking->delete();

        return redirect()->back()->with('success', 'Data booking ruangan berhasil dihapus!');
    }
}
