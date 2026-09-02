<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'kode_peminjaman',
        'barang_id',
        'kategori_peminjam',
        'nama_peminjam',
        'nomor_identitas',
        'prodi_unit',
        'kontak_peminjam',
        'jumlah',
        'tanggal_pinjam',
        'tenggat_kembali',
        'status',
        'disetujui_oleh',
        'disetujui_pada',
        'alasan_penolakan',
        'diserahkan_oleh',
        'tanggal_diambil',
        'tanggal_kembali',
        'kondisi_kembali',
        'keperluan',
        'petugas_id',
    ];

    protected $casts = [
        'tanggal_pinjam'  => 'date',
        'tenggat_kembali' => 'date',
        'tanggal_kembali' => 'date',
        'disetujui_pada'  => 'datetime',
        'tanggal_diambil' => 'datetime',
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Relasi ke Petugas yang mencatat / petugas default
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // Relasi ke Kepala Sarpras / Pejabat yang menyetujui
    public function penyetujui()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    // Relasi ke Petugas yang menyerahkan barang fisik
    public function penyerah()
    {
        return $this->belongsTo(User::class, 'diserahkan_oleh');
    }

    // Relasi ke Rincian Item Peminjaman (Keranjang)
    public function details()
    {
        return $this->hasMany(PeminjamanDetail::class, 'peminjaman_id');
    }
}
