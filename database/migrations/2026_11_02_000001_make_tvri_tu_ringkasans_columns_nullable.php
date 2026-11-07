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
        if (Schema::hasTable('tvri_tu_ringkasans')) {
            $statements = [
                "ALTER TABLE `tvri_tu_ringkasans` MODIFY `periode` varchar(255) NULL DEFAULT 'September 2025'",
                "ALTER TABLE `tvri_tu_ringkasans` MODIFY `header_waktu` varchar(255) NULL DEFAULT 'Selasa, 2 September 2025 | 10:24 WIT'",
                "ALTER TABLE `tvri_tu_ringkasans` MODIFY `online_status` varchar(255) NULL DEFAULT 'Online | Periode: September 2025'",
                "ALTER TABLE `tvri_tu_ringkasans` MODIFY `slogan_header` varchar(255) NULL DEFAULT 'TERTIB ADMINISTRASI • CEPAT LAYANAN • AKURAT DATA • MENDUKUNG KINERJA'",
                "ALTER TABLE `tvri_tu_ringkasans` MODIFY `slogan_sub` varchar(255) NULL DEFAULT 'Maluku Bisa!'",
                "ALTER TABLE `tvri_tu_ringkasans` MODIFY `diff_surat_masuk` varchar(255) NULL DEFAULT '+12% dari bulan lalu'",
                "ALTER TABLE `tvri_tu_ringkasans` MODIFY `diff_surat_keluar` varchar(255) NULL DEFAULT '+8%'",
                "ALTER TABLE `tvri_tu_ringkasans` MODIFY `diff_disposisi` varchar(255) NULL DEFAULT '+15%'",
                "ALTER TABLE `tvri_tu_ringkasans` MODIFY `diff_agenda` varchar(255) NULL DEFAULT '+20%'",
                "ALTER TABLE `tvri_tu_ringkasans` MODIFY `diff_layanan` varchar(255) NULL DEFAULT '+10%'",
            ];

            foreach ($statements as $sql) {
                try {
                    DB::statement($sql);
                } catch (\Throwable $e) {
                    // Ignore if already applied or database driver differs
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
