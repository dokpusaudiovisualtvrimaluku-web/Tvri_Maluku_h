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
        Schema::create('tvri_usaha_ringkasans', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->default('DASHBOARD PENGEMBANGAN USAHA TVRI MALUKU');
            $table->string('sub_judul')->default('Sinergi Konten, Kemitraan, dan Layanan untuk Keberlanjutan');
            $table->string('slogan')->default('Maluku Lebih Baik Bersama');
            $table->string('pilar')->default('Kolaborasi, Inovasi, Nilai Tambah, Berdampak Nyata');
            $table->string('kutipan')->default('Menggerakkan Potensi Membangun Masa Depan');
            $table->string('waktu')->default('Senin, 18 November 2024, 10:24 WIT');
            $table->string('tagline_peta')->default('Dari Maluku untuk Indonesia');

            // Indikator Kinerja Utama
            $table->decimal('total_pendapatan', 18, 2)->default(2850000000);
            $table->string('diff_total_pendapatan')->default('▲ 18,6% vs periode sebelumnya');
            $table->decimal('target_pendapatan', 18, 2)->default(3400000000);
            $table->decimal('capaian_target', 6, 2)->default(83.8);
            $table->integer('jumlah_kontrak_aktif')->default(28);
            $table->string('diff_kontrak_aktif')->default('▲ 12,0% vs periode sebelumnya');
            $table->integer('jumlah_mitra')->default(24);
            $table->string('diff_mitra')->default('▲ 26,3% vs periode sebelumnya');
            $table->integer('kegiatan_usaha')->default(12);
            $table->string('diff_kegiatan')->default('▲ 50,0% vs periode sebelumnya');

            // Komposisi Pendapatan (%)
            $table->decimal('komposisi_iklan_pct', 5, 2)->default(35.1);
            $table->decimal('komposisi_sponsorship_pct', 5, 2)->default(24.6);
            $table->decimal('komposisi_event_pct', 5, 2)->default(18.9);
            $table->decimal('komposisi_sewa_pct', 5, 2)->default(12.3);
            $table->decimal('komposisi_konten_pct', 5, 2)->default(6.7);
            $table->decimal('komposisi_lainnya_pct', 5, 2)->default(2.4);

            // Operasional & Realisasi Anggaran Usaha
            $table->decimal('operasional_promosi_pagu', 18, 2)->default(400000000);
            $table->decimal('operasional_promosi_realisasi', 18, 2)->default(300000000);
            $table->decimal('operasional_promosi_pct', 5, 2)->default(75);

            $table->decimal('operasional_event_pagu', 18, 2)->default(500000000);
            $table->decimal('operasional_event_realisasi', 18, 2)->default(340000000);
            $table->decimal('operasional_event_pct', 5, 2)->default(68);

            $table->decimal('operasional_aset_pagu', 18, 2)->default(400000000);
            $table->decimal('operasional_aset_realisasi', 18, 2)->default(220000000);
            $table->decimal('operasional_aset_pct', 5, 2)->default(55);

            $table->decimal('operasional_konten_pagu', 18, 2)->default(300000000);
            $table->decimal('operasional_konten_realisasi', 18, 2)->default(180000000);
            $table->decimal('operasional_konten_pct', 5, 2)->default(60);

            // Pipeline Peluang Usaha
            $table->integer('pipeline_prospek')->default(42);
            $table->integer('pipeline_pendekatan')->default(28);
            $table->integer('pipeline_proposal')->default(17);
            $table->integer('pipeline_negosiasi')->default(9);
            $table->integer('pipeline_kontrak')->default(5);

            // Peta Sebaran Mitra
            $table->integer('mitra_ambon')->default(12);
            $table->integer('mitra_tual')->default(4);
            $table->integer('mitra_saumlaki')->default(3);
            $table->integer('mitra_buru')->default(2);
            $table->integer('mitra_seram')->default(2);
            $table->integer('mitra_lainnya')->default(1);

            // Indeks Kinerja Pengembangan Usaha (Nilai: 82 - Baik)
            $table->integer('indeks_kinerja')->default(82);
            $table->string('indeks_predikat')->default('Baik');
            $table->integer('skor_pertumbuhan_pendapatan')->default(85);
            $table->integer('skor_diversifikasi_usaha')->default(80);
            $table->integer('skor_kemitraan_strategis')->default(78);
            $table->integer('skor_efisiensi_operasional')->default(82);
            $table->integer('skor_inovasi_produk')->default(85);

            // Strategi 2024-2025
            $table->text('fokus_utama')->nullable();
            $table->text('sudah_berjalan_json')->nullable();
            $table->text('dalam_proses_json')->nullable();
            $table->text('rencana_selanjutnya_json')->nullable();

            // Nilai Dasar & Penutup
            $table->string('nilai_dasar')->default('Integritas, Profesional, Kolaboratif, Inovatif, Berdampak');
            $table->string('quote_penutup')->default('Media untuk Masa Depan yang Lebih Baik');

            $table->timestamps();
        });

        Schema::create('tvri_usaha_sops', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->default('SOP-PU-01');
            $table->string('judul');
            $table->string('kategori')->default('Kemitraan');
            $table->string('versi')->default('v1.0');
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
        Schema::dropIfExists('tvri_usaha_sops');
        Schema::dropIfExists('tvri_usaha_ringkasans');
    }
};
