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
        Schema::table('ruangans', function (Blueprint $table) {
            if (!Schema::hasColumn('ruangans', 'penanggung_jawab')) {
                $table->string('penanggung_jawab')->nullable()->after('nama_ruangan');
            }
            if (!Schema::hasColumn('ruangans', 'nip_penanggung_jawab')) {
                $table->string('nip_penanggung_jawab')->nullable()->after('penanggung_jawab');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruangans', function (Blueprint $table) {
            if (Schema::hasColumn('ruangans', 'nip_penanggung_jawab')) {
                $table->dropColumn('nip_penanggung_jawab');
            }
        });
    }
};
