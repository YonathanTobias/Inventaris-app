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
        Schema::create('mutasis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
        $table->enum('jenis_mutasi', ['Pindah Ruangan', 'Pengurangan/Rusak', 'Penambahan']);
        $table->foreignId('ruangan_asal_id')->nullable()->constrained('ruangans')->onDelete('set null');
        $table->foreignId('ruangan_tujuan_id')->nullable()->constrained('ruangans')->onDelete('set null');
        $table->integer('jumlah');
        $table->text('keterangan')->nullable(); // Alasan pindah / alasan rusak/hilang
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasis');
    }
};
