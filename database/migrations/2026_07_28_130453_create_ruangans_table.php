<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ruangans', function (Blueprint $table) {
        $table->id();
        $table->string('kode_ruangan')->unique(); // Contoh: R-001, LAB-01
        $table->string('nama_ruangan');           // Contoh: Ruang Perpustakaan, Lab Komputer
        $table->string('penanggung_jawab')->nullable(); // Contoh: Nama Kepala Ruangan/PJ
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangans');
    }
};
