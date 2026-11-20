<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class PeminjamanRuanganController extends Controller
{
    // Dashboard Petugas / Kepala Sarpras: Kelola Booking Ruangan (Unified Single Page)
    public function index(Request $request)
    {
        $request->merge(['tab' => 'ruangan']);
        return app(PeminjamanController::class)->index($request);
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
