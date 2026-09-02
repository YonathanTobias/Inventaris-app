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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->string('nama_peminjam');
            $table->string('nomor_identitas'); // NIM / NIP / NIK
            $table->string('kontak_peminjam')->nullable(); // No WhatsApp / Telp
            $table->integer('jumlah')->default(1);
            $table->date('tanggal_pinjam');
            $table->date('tenggat_kembali');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['Dipinjam', 'Kembali', 'Terlambat'])->default('Dipinjam');
            $table->enum('kondisi_kembali', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->nullable();
            $table->text('keperluan')->nullable();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
