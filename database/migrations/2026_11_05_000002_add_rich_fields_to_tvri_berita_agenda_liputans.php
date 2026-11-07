<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tvri_berita_agenda_liputans')) {
            Schema::table('tvri_berita_agenda_liputans', function (Blueprint $table) {
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'kode')) {
                    $table->string('kode', 50)->nullable()->after('id');
                }
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'jenis')) {
                    $table->string('jenis', 50)->nullable()->after('agenda');
                }
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'kategori')) {
                    $table->string('kategori', 100)->nullable()->after('jenis');
                }
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'koordinator')) {
                    $table->string('koordinator', 100)->nullable()->default('Nahrum')->after('driver');
                }
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'mulai')) {
                    $table->string('mulai', 10)->nullable()->after('waktu');
                }
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'selesai')) {
                    $table->string('selesai', 10)->nullable()->after('mulai');
                }
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'durasi_label')) {
                    $table->string('durasi_label', 50)->nullable()->after('selesai');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tvri_berita_agenda_liputans')) {
            Schema::table('tvri_berita_agenda_liputans', function (Blueprint $table) {
                $columns = ['kode', 'jenis', 'kategori', 'koordinator', 'mulai', 'selesai', 'durasi_label'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('tvri_berita_agenda_liputans', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
