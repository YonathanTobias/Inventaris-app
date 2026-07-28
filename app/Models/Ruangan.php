<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi: Satu ruangan bisa memiliki banyak barang aset
    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}