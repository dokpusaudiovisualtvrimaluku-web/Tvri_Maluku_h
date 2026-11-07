<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ringkasan Parameter & KPI Dashboard Transmisi
        if (!Schema::hasTable('tvri_transmisi_ringkasans')) {
            Schema::create('tvri_transmisi_ringkasans', function (Blueprint $table) {
                $table->id();
                $table->string('judul')->default('DASHBOARD TRANSMISI TVRI MALUKU');
                $table->string('slogan')->default('SIARAN ANDAL • JANGKAUAN LUAS • KUALITAS TERJAGA • INDONESIA SEMAKIN DEKAT');
                $table->string('slogan_tambahan')->default('Maluku Bisa! Siaran Tanpa Batas!');
                $table->string('waktu_status')->default('Selasa, 2 September 2025, 10:24 WIT, Online');
                $table->string('periode')->default('September 2025');

                // KPI
                $table->integer('total_site')->default(28);
                $table->string('total_site_diff')->default('+4% dari bulan lalu');
                $table->integer('on_air_pct')->default(100);
                $table->integer('on_air_target')->default(99);
                $table->integer('cakupan_wilayah_pct')->default(92);
                $table->string('cakupan_diff')->default('+3% dari bulan lalu');
                $table->decimal('kualitas_rata', 3, 1)->default(4.6);
                $table->string('kualitas_diff')->default('+0.2 dari bulan lalu');
                $table->integer('gangguan_aktif')->default(2);
                $table->string('gangguan_diff')->default('-60% dari bulan lalu');
                $table->integer('pemeliharaan_berjalan')->default(3);
                $table->string('pemeliharaan_diff')->default('-25% dari bulan lalu');
                $table->integer('efisiensi_daya_pct')->default(87);
                $table->string('efisiensi_diff')->default('+5% dari bulan lalu');

                // Status Siaran per Platform (JSON)
                $table->json('platform_siaran_json')->nullable();

                // Kualitas Sinyal (JSON)
                $table->json('kualitas_sinyal_json')->nullable();

                // Parameter Teknis Real-Time (JSON)
                $table->json('parameter_teknis_json')->nullable();

                // Ketersediaan Infrastruktur (JSON)
                $table->json('infrastruktur_json')->nullable();

                // Footer & Slogans
                $table->string('footer_slogan')->default('Siaran Berkualitas • Layanan Tanpa Batas • Inovasi untuk Negeri • Integritas • Kolaborasi');
                $table->string('footer_quote')->default('Maluku Bisa! Indonesia Maju!');

                $table->timestamps();
            });
        }

        // 2. Pemeliharaan Terjadwal Transmisi
        if (!Schema::hasTable('tvri_transmisi_pemeliharaans')) {
            Schema::create('tvri_transmisi_pemeliharaans', function (Blueprint $table) {
                $table->id();
                $table->string('tanggal');
                $table->string('site');
                $table->string('kegiatan');
                $table->string('status')->default('Terjadwal');
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        // 3. Log Aktivitas Terkini Transmisi
        if (!Schema::hasTable('tvri_transmisi_logs')) {
            Schema::create('tvri_transmisi_logs', function (Blueprint $table) {
                $table->id();
                $table->string('waktu');
                $table->string('site')->nullable();
                $table->string('aktivitas');
                $table->string('user')->default('operator');
                $table->string('status')->default('Normal');
                $table->timestamps();
            });
        }

        // 4. Dokumen & SOP Transmisi
        if (!Schema::hasTable('tvri_transmisi_sops')) {
            Schema::create('tvri_transmisi_sops', function (Blueprint $table) {
                $table->id();
                $table->string('kode')->nullable();
                $table->string('judul');
                $table->string('kategori')->default('SOP Transmisi');
                $table->string('versi')->default('v1.0');
                $table->string('status')->default('Aktif');
                $table->string('file_path')->nullable();
                $table->text('keterangan')->nullable();
                $table->integer('urutan')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tvri_transmisi_sops');
        Schema::dropIfExists('tvri_transmisi_logs');
        Schema::dropIfExists('tvri_transmisi_pemeliharaans');
        Schema::dropIfExists('tvri_transmisi_ringkasans');
    }
};
