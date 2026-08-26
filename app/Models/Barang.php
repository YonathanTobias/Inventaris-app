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
        'tahun_pengadaan',
        'keterangan',
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
}