<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition(): array
    {
        return [
            'kode_barang' => 'AST-' . fake()->unique()->numerify('#####'),
            'nama_barang' => 'Alat Medis ' . fake()->words(2, true),
            'kategori_id' => Kategori::factory(),
            'ruangan_id' => Ruangan::factory(),
            'jumlah' => fake()->numberBetween(5, 50),
            'kondisi' => 'Baik',
            'bisa_dipinjam' => true,
            'tahun_pengadaan' => fake()->year(),
            'keterangan' => 'Keterangan barang uji coba',
        ];
    }
}
