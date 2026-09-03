<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanRuangan extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_ruangans';

    protected $fillable = [
        'kode_booking',
        'ruangan_id',
        'kategori_peminjam',
        'nama_peminjam',
        'nomor_identitas',
        'prodi_unit',
        'kontak_peminjam',
        'tanggal_pemakaian',
        'jam_mulai',
        'jam_selesai',
        'keperluan',
        'status',
        'disetujui_oleh',
        'disetujui_pada',
        'alasan_penolakan',
        'diserahkan_oleh',
        'waktu_masuk',
        'waktu_selesai',
        'catatan_kondisi',
    ];

    protected $casts = [
        'tanggal_pemakaian' => 'date',
        'disetujui_pada'    => 'datetime',
        'waktu_masuk'       => 'datetime',
        'waktu_selesai'     => 'datetime',
    ];

    // Relasi ke Ruangan
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    // Relasi ke User / Kepala Sarpras yang menyetujui
    public function penyetujui()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    // Relasi ke Petugas yang membukakan ruangan / mencatat check-in
    public function penyerah()
    {
        return $this->belongsTo(User::class, 'diserahkan_oleh');
    }

    public function petugasSerah()
    {
        return $this->belongsTo(User::class, 'diserahkan_oleh');
    }
}
