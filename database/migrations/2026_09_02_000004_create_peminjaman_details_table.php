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
        // 1. Buat tabel rincian item keranjang peminjaman
        Schema::create('peminjaman_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->enum('kondisi_kembali', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 2. Buat barang_id pada peminjamans nullable (sebagai header transaksi)
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->foreignId('barang_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_details');
    }
};
