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
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->string('kategori_peminjam')->default('Mahasiswa')->after('barang_id'); // Dosen, Mahasiswa, Staf / Tendik
            $table->string('prodi_unit')->nullable()->after('nomor_identitas'); // Misal: S1 Keperawatan, D3 Kebidanan
            
            // Approval fields
            $table->foreignId('disetujui_oleh')->nullable()->after('status')->constrained('users')->onDelete('set null');
            $table->timestamp('disetujui_pada')->nullable()->after('disetujui_oleh');
            $table->text('alasan_penolakan')->nullable()->after('disetujui_pada');

            // Handover / Pick up fields
            $table->foreignId('diserahkan_oleh')->nullable()->after('alasan_penolakan')->constrained('users')->onDelete('set null');
            $table->timestamp('tanggal_diambil')->nullable()->after('diserahkan_oleh');
        });

        // Modifikasi tipe status peminjaman
        \DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('Menunggu', 'Disetujui', 'Diambil', 'Ditolak', 'Kembali', 'Terlambat') DEFAULT 'Menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropForeign(['disetujui_oleh']);
            $table->dropForeign(['diserahkan_oleh']);
            $table->dropColumn([
                'kategori_peminjam',
                'prodi_unit',
                'disetujui_oleh',
                'disetujui_pada',
                'alasan_penolakan',
                'diserahkan_oleh',
                'tanggal_diambil',
            ]);
        });

        \DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('Dipinjam', 'Kembali', 'Terlambat') DEFAULT 'Dipinjam'");
    }
};
