<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanFactory extends Factory
{
    protected $model = Peminjaman::class;

    public function definition(): array
    {
        return [
            'kode_peminjaman' => 'PJ-' . date('Ymd') . '-' . strtoupper(fake()->unique()->bothify('####')),
            'barang_id' => Barang::factory(),
            'kategori_peminjam' => 'Mahasiswa',
            'nama_peminjam' => fake()->name(),
            'nomor_identitas' => fake()->numerify('##########'),
            'prodi_unit' => 'S1 Keperawatan',
            'kontak_peminjam' => '08' . fake()->numerify('##########'),
            'jumlah' => fake()->numberBetween(1, 3),
            'tanggal_pinjam' => now()->toDateString(),
            'tenggat_kembali' => now()->addDays(3)->toDateString(),
            'status' => 'Menunggu',
            'keperluan' => 'Praktikum Mandiri',
            'petugas_id' => User::factory(),
        ];
    }
}
