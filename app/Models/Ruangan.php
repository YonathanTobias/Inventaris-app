<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'bisa_dipinjam',
    ];

    protected $casts = [
        'bisa_dipinjam' => 'boolean',
    ];

    // Relasi: Satu ruangan bisa memiliki banyak barang aset
    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

    // Relasi: Peminjaman / booking ruangan
    public function peminjamanRuangans()
    {
        return $this->hasMany(PeminjamanRuangan::class);
    }
}