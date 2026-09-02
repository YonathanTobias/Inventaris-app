<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'ruangan_id',
        'jumlah',
        'kondisi',
        'bisa_dipinjam',
        'tahun_pengadaan',
        'keterangan',
    ];

    protected $casts = [
        'bisa_dipinjam' => 'boolean',
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Relasi ke Ruangan
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    // Relasi ke Riwayat Mutasi
    public function mutasis()
    {
        return $this->hasMany(Mutasi::class);
    }

    // Relasi ke Riwayat Peminjaman
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}