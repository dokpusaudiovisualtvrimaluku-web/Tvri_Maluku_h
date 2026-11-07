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
        Schema::table('tvri_umum_ringkasans', function (Blueprint $table) {
            $table->integer('pos_keamanan')->default(3)->after('personel_security');
            $table->integer('cctv_total')->default(28)->after('pos_keamanan');
            $table->integer('cctv_aktif')->default(26)->after('cctv_total');
            $table->integer('cctv_perbaikan')->default(2)->after('cctv_aktif');
            $table->integer('apar_total')->default(32)->after('cctv_perbaikan');
            $table->integer('apar_terverifikasi')->default(32)->after('apar_total');

            $table->integer('nilai_kebersihan_kantor')->default(94)->after('tingkat_kebersihan');
            $table->integer('nilai_kebersihan_studio')->default(96)->after('nilai_kebersihan_kantor');
            $table->integer('nilai_kebersihan_halaman')->default(90)->after('nilai_kebersihan_studio');
            $table->integer('nilai_kebersihan_toilet')->default(88)->after('nilai_kebersihan_halaman');

            $table->text('agenda_json')->nullable()->after('footer_nilai');
            $table->text('notifikasi_json')->nullable()->after('agenda_json');
            $table->text('kontak_json')->nullable()->after('notifikasi_json');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tvri_umum_ringkasans', function (Blueprint $table) {
            $table->dropColumn([
                'pos_keamanan', 'cctv_total', 'cctv_aktif', 'cctv_perbaikan', 'apar_total', 'apar_terverifikasi',
                'nilai_kebersihan_kantor', 'nilai_kebersihan_studio', 'nilai_kebersihan_halaman', 'nilai_kebersihan_toilet',
                'agenda_json', 'notifikasi_json', 'kontak_json'
            ]);
        });
    }
};
