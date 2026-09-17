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
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `mutasis` MODIFY COLUMN `jenis_mutasi` VARCHAR(100) NOT NULL");
        } else {
            Schema::table('mutasis', function (Blueprint $table) {
                $table->string('jenis_mutasi', 100)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `mutasis` MODIFY COLUMN `jenis_mutasi` VARCHAR(100) NOT NULL");
        }
    }
};
