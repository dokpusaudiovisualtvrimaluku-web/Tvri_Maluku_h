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
        Schema::create('tvri_tu_ringkasans', function (Blueprint $table) {
            $table->id();
            $table->string('periode')->default('September 2025');
            $table->string('header_waktu')->default('Selasa, 2 September 2025 | 10:24 WIT');
            $table->string('online_status')->default('Online | Periode: September 2025');
            $table->string('slogan_header')->default('TERTIB ADMINISTRASI • CEPAT LAYANAN • AKURAT DATA • MENDUKUNG KINERJA');
            $table->string('slogan_sub')->default('Maluku Bisa!');

            // Statistik Atas
            $table->integer('surat_masuk')->default(128);
            $table->string('diff_surat_masuk')->default('+12% dari bulan lalu');
            $table->integer('surat_keluar')->default(96);
            $table->string('diff_surat_keluar')->default('+8%');
            $table->integer('disposisi')->default(110);
            $table->string('diff_disposisi')->default('+15%');
            $table->integer('agenda_kegiatan')->default(24);
            $table->string('diff_agenda')->default('+20%');
            $table->integer('layanan_adm')->default(86);
            $table->string('diff_layanan')->default('+10%');
            $table->integer('service_completion_rate')->default(94);
            $table->integer('target_completion_rate')->default(90);

            // Status Disposisi
            $table->integer('disposisi_selesai')->default(78);
            $table->integer('disposisi_proses')->default(18);
            $table->integer('disposisi_menunggu')->default(9);
            $table->integer('disposisi_terlambat')->default(5);

            // Kategori Surat Masuk
            $table->integer('kat_kementerian')->default(32);
            $table->integer('kat_lpp')->default(24);
            $table->integer('kat_pemda')->default(20);
            $table->integer('kat_vertikal')->default(16);
            $table->integer('kat_mitra')->default(14);
            $table->integer('kat_masyarakat')->default(12);
            $table->integer('kat_lainnya')->default(10);

            // Layanan Administrasi Breakdown
            $table->integer('layanan_sk')->default(24);
            $table->integer('layanan_data')->default(18);
            $table->integer('layanan_internal')->default(22);
            $table->integer('layanan_eksternal')->default(16);
            $table->integer('layanan_lainnya')->default(6);

            // Arsip & Dokumentasi
            $table->integer('arsip_aktif')->default(1250);
            $table->integer('arsip_inaktif')->default(320);
            $table->integer('arsip_digital')->default(850);
            $table->integer('arsip_retensi')->default(42);

            // Kepatuhan SOP & Monitoring
            $table->integer('kepatuhan_administrasi')->default(96);
            $table->integer('target_kepatuhan')->default(90);

            // JSON data
            $table->text('kpi_json')->nullable();
            $table->text('notifikasi_json')->nullable();

            $table->timestamps();
        });

        Schema::create('tvri_tu_sops', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->default('SOP-TU-01');
            $table->string('judul');
            $table->string('kategori')->default('Administrasi');
            $table->string('versi')->default('v2.1');
            $table->date('tanggal_efektif')->nullable();
            $table->string('status')->default('Aktif');
            $table->string('file_url')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tvri_tu_sops');
        Schema::dropIfExists('tvri_tu_ringkasans');
    }
};
