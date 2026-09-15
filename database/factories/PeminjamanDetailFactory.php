<?php

namespace Database\Factories;

use App\Models\PeminjamanDetail;
use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanDetailFactory extends Factory
{
    protected $model = PeminjamanDetail::class;

    public function definition(): array
    {
        return [
            'peminjaman_id' => Peminjaman::factory(),
            'barang_id' => Barang::factory(),
            'jumlah' => 1,
            'kondisi_kembali' => null,
            'catatan' => null,
        ];
    }
}
