<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class PeminjamanRuanganPublikController extends Controller
{
    // Pemrosesan Pengajuan Booking / Peminjaman Ruangan dari Dosen & Mahasiswa
    public function store(Request $request)
    {
        $request->validate([
            'ruangan_id'        => 'required|exists:ruangans,id',
            'kategori_peminjam' => 'required|in:Dosen,Mahasiswa / Ormawa,Staf / Tendik',
            'nama_peminjam'     => 'required|string|max:255',
            'nomor_identitas'   => 'required|string|max:100',
            'prodi_unit'        => 'required|string|max:150',
            'kontak_peminjam'   => 'required|string|max:50',
            'tanggal_pemakaian' => 'required|date',
            'jam_mulai'         => 'required',
            'jam_selesai'       => 'required|after:jam_mulai',
            'keperluan'         => 'required|string|max:500',
        ], [
            'ruangan_id.required'        => 'Silakan pilih ruangan yang ingin dipinjam / dibooking.',
            'nama_peminjam.required'     => 'Nama pemohon wajib diisi.',
            'nomor_identitas.required'   => 'NIM / NIP wajib diisi.',
            'kontak_peminjam.required'   => 'Nomor WhatsApp aktif wajib diisi untuk konfirmasi persetujuan.',
            'jam_selesai.after'          => 'Jam selesai harus lebih akhir dari jam mulai pemakaian.',
            'keperluan.required'         => 'Keperluan penggunaan ruangan wajib diisi.',
        ]);

        $ruangan = Ruangan::findOrFail($request->ruangan_id);

        // 1. Validasi apakah ruangan diizinkan untuk dipinjam publik
        if (!$ruangan->bisa_dipinjam) {
            return redirect()->back()->with('error', "Ruangan [{$ruangan->nama_ruangan}] bertipe khusus internal dan tidak diizinkan untuk dipinjam!");
        }

        // 2. Validasi pencegahan jadwal bentrok (Conflict / Overlap Prevention)
        $overlap = PeminjamanRuangan::where('ruangan_id', $request->ruangan_id)
            ->where('tanggal_pemakaian', $request->tanggal_pemakaian)
            ->whereIn('status', ['Menunggu', 'Disetujui', 'Digunakan'])
            ->where(function($q) use ($request) {
                $q->where('jam_mulai', '<', $request->jam_selesai)
                  ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->first();

        if ($overlap) {
            $jamBentrok = date('H:i', strtotime($overlap->jam_mulai)) . ' - ' . date('H:i', strtotime($overlap->jam_selesai));
            return redirect()->back()->with('error', "Jadwal bentrok! Ruangan [{$ruangan->nama_ruangan}] telah diajukan/disetujui pada tanggal " . date('d M Y', strtotime($request->tanggal_pemakaian)) . " pukul {$jamBentrok} untuk {$overlap->nama_peminjam} (Status: {$overlap->status}). Silakan pilih jam atau ruangan lain!");
        }

        // 3. Generate Kode Booking Unik: BKG-YYYYMM-XXXX
        $prefix = 'BKG-' . date('Ym');
        $countThisMonth = PeminjamanRuangan::where('kode_booking', 'like', "{$prefix}-%")->count();
        $nextSeq = sprintf('%04d', $countThisMonth + 1);
        $kodeBooking = "{$prefix}-{$nextSeq}";

        while (PeminjamanRuangan::where('kode_booking', $kodeBooking)->exists()) {
            $countThisMonth++;
            $nextSeq = sprintf('%04d', $countThisMonth + 1);
            $kodeBooking = "{$prefix}-{$nextSeq}";
        }

        // 4. Simpan Pengajuan Booking Ruangan
        $booking = PeminjamanRuangan::create([
            'kode_booking'      => $kodeBooking,
            'ruangan_id'        => $ruangan->id,
            'kategori_peminjam' => $request->kategori_peminjam,
            'nama_peminjam'     => $request->nama_peminjam,
            'nomor_identitas'   => $request->nomor_identitas,
            'prodi_unit'        => $request->prodi_unit,
            'kontak_peminjam'   => $request->kontak_peminjam,
            'tanggal_pemakaian' => $request->tanggal_pemakaian,
            'jam_mulai'         => $request->jam_mulai,
            'jam_selesai'       => $request->jam_selesai,
            'keperluan'         => $request->keperluan,
            'status'            => 'Menunggu',
        ]);

        return redirect()->route('publik.ruangan.sukses', $booking->kode_booking);
    }

    // Tampilan E-Ticket / Bukti Booking Ruangan Digital
    public function sukses($kode)
    {
        $booking = PeminjamanRuangan::with(['ruangan', 'penyetujui', 'penyerah'])
            ->where('kode_booking', $kode)
            ->firstOrFail();

        return view('publik.sukses_ruangan', compact('booking'));
    }

    // Endpoint API untuk Cek Jadwal Ruangan Terisi
    public function jadwal(Request $request)
    {
        $ruanganId = $request->input('ruangan_id');
        $tanggal = $request->input('tanggal', date('Y-m-d'));

        $bookings = PeminjamanRuangan::where('ruangan_id', $ruanganId)
            ->where('tanggal_pemakaian', $tanggal)
            ->whereIn('status', ['Menunggu', 'Disetujui', 'Digunakan'])
            ->orderBy('jam_mulai')
            ->get(['jam_mulai', 'jam_selesai', 'nama_peminjam', 'keperluan', 'status']);

        return response()->json($bookings);
    }
}
