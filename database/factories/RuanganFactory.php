<?php

namespace Database\Factories;

use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;

class RuanganFactory extends Factory
{
    protected $model = Ruangan::class;

    public function definition(): array
    {
        return [
            'kode_ruangan' => 'RNG-' . fake()->unique()->numerify('###'),
            'nama_ruangan' => 'Laboratorium ' . fake()->unique()->word(),
            'bisa_dipinjam' => true,
        ];
    }
}
