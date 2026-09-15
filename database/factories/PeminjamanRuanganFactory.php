<?php

namespace Database\Factories;

use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanRuanganFactory extends Factory
{
    protected $model = PeminjamanRuangan::class;

    public function definition(): array
    {
        return [
            'kode_booking' => 'BK-' . date('Ymd') . '-' . strtoupper(fake()->unique()->bothify('####')),
            'ruangan_id' => Ruangan::factory(),
            'kategori_peminjam' => 'Dosen',
            'nama_peminjam' => fake()->name(),
            'nomor_identitas' => fake()->numerify('##########'),
            'prodi_unit' => 'D3 Kebidanan',
            'kontak_peminjam' => '08' . fake()->numerify('##########'),
            'tanggal_pemakaian' => now()->addDay()->toDateString(),
            'jam_mulai' => '08:00',
            'jam_selesai' => '11:00',
            'keperluan' => 'Kuliah Praktik Terpadu',
            'status' => 'Menunggu',
        ];
    }
}
