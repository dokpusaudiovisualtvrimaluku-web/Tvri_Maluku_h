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
        // Change enum columns to varchar so they are flexible
        DB::statement("ALTER TABLE `simandat_teachers` MODIFY COLUMN `status_kepegawaian` VARCHAR(50) NOT NULL DEFAULT 'PNS'");
        DB::statement("ALTER TABLE `simandat_teachers` MODIFY COLUMN `sertifikasi` TINYINT(1) NOT NULL DEFAULT 1");
        DB::statement("ALTER TABLE `simandat_students` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'AKTIF'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
