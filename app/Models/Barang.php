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

    protected $appends = [
        'stok_dipinjam',
        'stok_tersedia',
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

    // Relasi ke Rincian Peminjaman Detail
    public function peminjamanDetails()
    {
        return $this->hasMany(PeminjamanDetail::class);
    }

    // Hitung total unit yang sedang aktif dipinjam keluar (Status: Diambil atau Terlambat)
    public function getStokDipinjamAttribute(): int
    {
        $activeStatuses = ['Diambil', 'Terlambat'];

        // 1. Dari peminjaman_details yang status induknya aktif
        $fromDetails = PeminjamanDetail::where('barang_id', $this->id)
            ->whereHas('peminjaman', function ($q) use ($activeStatuses) {
                $q->whereIn('status', $activeStatuses);
            })
            ->sum('jumlah');

        // 2. Dari peminjaman langsung (legacy data tanpa details)
        $fromDirect = Peminjaman::where('barang_id', $this->id)
            ->whereIn('status', $activeStatuses)
            ->whereDoesntHave('details')
            ->sum('jumlah');

        return (int) ($fromDetails + $fromDirect);
    }

    // Hitung sisa stok fisik yang ada di tempat dan siap dipinjam
    public function getStokTersediaAttribute(): int
    {
        return max(0, (int) $this->jumlah - $this->stok_dipinjam);
    }
}