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
        if (Schema::hasTable('tvri_surat_masuks')) {
            Schema::table('tvri_surat_masuks', function (Blueprint $table) {
                if (!Schema::hasColumn('tvri_surat_masuks', 'file_url')) {
                    $table->string('file_url')->nullable()->after('status');
                }
                if (!Schema::hasColumn('tvri_surat_masuks', 'file_path')) {
                    $table->string('file_path')->nullable()->after('file_url');
                }
            });
        }

        if (Schema::hasTable('tvri_surat_keluars')) {
            Schema::table('tvri_surat_keluars', function (Blueprint $table) {
                if (!Schema::hasColumn('tvri_surat_keluars', 'file_url')) {
                    $table->string('file_url')->nullable()->after('status');
                }
                if (!Schema::hasColumn('tvri_surat_keluars', 'file_path')) {
                    $table->string('file_path')->nullable()->after('file_url');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tvri_surat_masuks')) {
            Schema::table('tvri_surat_masuks', function (Blueprint $table) {
                if (Schema::hasColumn('tvri_surat_masuks', 'file_path')) {
                    $table->dropColumn('file_path');
                }
                if (Schema::hasColumn('tvri_surat_masuks', 'file_url')) {
                    $table->dropColumn('file_url');
                }
            });
        }

        if (Schema::hasTable('tvri_surat_keluars')) {
            Schema::table('tvri_surat_keluars', function (Blueprint $table) {
                if (Schema::hasColumn('tvri_surat_keluars', 'file_path')) {
                    $table->dropColumn('file_path');
                }
                if (Schema::hasColumn('tvri_surat_keluars', 'file_url')) {
                    $table->dropColumn('file_url');
                }
            });
        }
    }
};
