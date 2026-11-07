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
                $table->string('waktu', 50)->change();
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'tanggal')) {
                    $table->date('tanggal')->nullable()->after('id');
                }
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'kendaraan')) {
                    $table->string('kendaraan', 50)->nullable()->after('pic');
                }
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'reporter')) {
                    $table->string('reporter', 100)->nullable()->after('kendaraan');
                }
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'kamerawan')) {
                    $table->string('kamerawan', 100)->nullable()->after('reporter');
                }
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'driver')) {
                    $table->string('driver', 100)->nullable()->after('kamerawan');
                }
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'prioritas')) {
                    $table->string('prioritas', 50)->nullable()->after('status');
                }
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'catatan')) {
                    $table->text('catatan')->nullable()->after('prioritas');
                }
                if (!Schema::hasColumn('tvri_berita_agenda_liputans', 'link')) {
                    $table->string('link', 500)->nullable()->after('catatan');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tvri_berita_agenda_liputans')) {
            Schema::table('tvri_berita_agenda_liputans', function (Blueprint $table) {
                $table->string('waktu', 10)->change();
                $columns = ['tanggal', 'kendaraan', 'reporter', 'kamerawan', 'driver', 'prioritas', 'catatan', 'link'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('tvri_berita_agenda_liputans', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
