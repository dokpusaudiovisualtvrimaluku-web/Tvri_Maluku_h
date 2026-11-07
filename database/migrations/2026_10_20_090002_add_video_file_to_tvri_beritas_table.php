<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tvri_beritas') && !Schema::hasColumn('tvri_beritas', 'video_file')) {
            Schema::table('tvri_beritas', function (Blueprint $table) {
                $table->string('video_file', 255)->nullable()->after('durasi');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tvri_beritas') && Schema::hasColumn('tvri_beritas', 'video_file')) {
            Schema::table('tvri_beritas', function (Blueprint $table) {
                $table->dropColumn('video_file');
            });
        }
    }
};
