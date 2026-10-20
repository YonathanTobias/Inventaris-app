<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->string('kode_kategori', 20)->nullable()->after('nama_kategori');
        });

        // Set default kode_kategori untuk kategori yang sudah ada
        $kategoris = DB::table('kategoris')->get();
        foreach ($kategoris as $k) {
            $nama = strtoupper($k->nama_kategori);
            $kode = 'UMM';
            if (str_contains($nama, 'ELEKTRONIK') || str_contains($nama, 'KOMPUTER') || str_contains($nama, 'IT') || str_contains($nama, 'MULTIMEDIA')) {
                $kode = 'ELK';
            } elseif (str_contains($nama, 'MEBEL') || str_contains($nama, 'FURNITUR') || str_contains($nama, 'FURNITURE')) {
                $kode = 'MBL';
            } elseif (str_contains($nama, 'MEDIS') || str_contains($nama, 'KESEHATAN') || str_contains($nama, 'ALKES')) {
                $kode = 'MED';
            } elseif (str_contains($nama, 'KANTOR') || str_contains($nama, 'ATK')) {
                $kode = 'ATK';
            } elseif (str_contains($nama, 'LAB') || str_contains($nama, 'PRAKTIKUM')) {
                $kode = 'LAB';
            } else {
                $clean = preg_replace('/[^A-Z0-9]/', '', $nama);
                $kode = substr($clean, 0, 3) ?: 'UMM';
            }

            DB::table('kategoris')->where('id', $k->id)->update(['kode_kategori' => $kode]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropColumn('kode_kategori');
        });
    }
};
