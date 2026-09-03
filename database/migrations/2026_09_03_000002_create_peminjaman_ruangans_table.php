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
        Schema::create('peminjaman_ruangans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('ruangan_id')->constrained('ruangans')->onDelete('cascade');
            $table->enum('kategori_peminjam', ['Dosen', 'Mahasiswa / Ormawa', 'Staf / Tendik'])->default('Mahasiswa / Ormawa');
            $table->string('nama_peminjam');
            $table->string('nomor_identitas');
            $table->string('prodi_unit')->nullable();
            $table->string('kontak_peminjam');
            $table->date('tanggal_pemakaian');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('keperluan');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Digunakan', 'Selesai', 'Ditolak'])->default('Menunggu');
            
            // Approval
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('disetujui_pada')->nullable();
            $table->text('alasan_penolakan')->nullable();

            // Handover / In use
            $table->foreignId('diserahkan_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('waktu_masuk')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->text('catatan_kondisi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_ruangans');
    }
};
