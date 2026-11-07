<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tvri_beritas') && !Schema::hasColumn('tvri_beritas', 'durasi')) {
            Schema::table('tvri_beritas', function (Blueprint $table) {
                $table->string('durasi', 20)->nullable()->default('02:00')->after('views');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tvri_beritas') && Schema::hasColumn('tvri_beritas', 'durasi')) {
            Schema::table('tvri_beritas', function (Blueprint $table) {
                $table->dropColumn('durasi');
            });
        }
    }
};
