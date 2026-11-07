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
        Schema::create('tvri_umum_ringkasans', function (Blueprint $table) {
            $table->id();
            $table->string('periode')->default('September 2025');
            $table->string('header_waktu')->default('Selasa, 2 September 2025 | 10:24 WIT');
            $table->string('live_status')->default('Live System | Periode: September 2025');
            $table->string('slogan_header')->default('LAYANAN OPERASIONAL • SARANA PRASARANA • ASET • KEAMANAN • KENYAMANAN');
            $table->string('slogan_sub')->default('"Lingkungan Kerja yang Aman, Nyaman dan Produktif untuk Siaran yang Lebih Bermakna"');
            $table->string('slogan_motto')->default('Maluku Bisa Lebih Maju!');

            // 7 Metrik Utama
            $table->integer('total_aset')->default(1245);
            $table->string('diff_aset')->default('+3% dari bulan lalu');
            $table->integer('kendaraan_total')->default(28);
            $table->integer('kendaraan_siap')->default(26);
            $table->integer('kendaraan_siap_pct')->default(92);
            $table->string('diff_kendaraan')->default('+4%');
            $table->integer('kendaraan_perbaikan')->default(2);
            $table->integer('kendaraan_tidak_operasi')->default(0);

            $table->integer('sarpras_total')->default(56);
            $table->integer('sarpras_baik_pct')->default(89);
            $table->string('diff_sarpras')->default('+5%');

            $table->integer('permintaan_total')->default(42);
            $table->integer('permintaan_selesai')->default(37);
            $table->integer('permintaan_selesai_pct')->default(88);
            $table->string('diff_permintaan')->default('+12%');
            $table->integer('permintaan_proses')->default(3);
            $table->integer('permintaan_ditunda')->default(1);
            $table->integer('permintaan_dibatalkan')->default(1);

            $table->integer('insiden_keamanan')->default(2);
            $table->string('diff_insiden')->default('-50% dari bulan lalu');
            $table->integer('indeks_kenyamanan')->default(92);
            $table->integer('target_kenyamanan')->default(90);
            $table->integer('operational_readiness')->default(94);
            $table->integer('target_readiness')->default(90);

            // Kondisi Aset Breakdown
            $table->integer('aset_baik')->default(892);
            $table->integer('aset_rusak_ringan')->default(178);
            $table->integer('aset_rusak_berat')->default(56);
            $table->integer('aset_dalam_perbaikan')->default(78);
            $table->integer('aset_tidak_aktif')->default(41);

            // Stok Barang Breakdown
            $table->integer('stok_aman_pct')->default(65);
            $table->integer('stok_menipis_pct')->default(25);
            $table->integer('stok_habis_pct')->default(10);
            $table->integer('stok_atk_item')->default(120);
            $table->integer('stok_kebersihan_item')->default(45);
            $table->integer('stok_sparepart_item')->default(68);
            $table->integer('stok_lainnya_item')->default(32);

            // Keamanan & K3
            $table->integer('kecelakaan_kerja')->default(0);
            $table->integer('personel_security')->default(16);
            $table->integer('temuan_k3')->default(12);
            $table->string('diff_temuan_k3')->default('-25%');

            // Kebersihan & Lingkungan
            $table->integer('area_dibersihkan')->default(32);
            $table->integer('pengelolaan_sampah')->default(4);
            $table->integer('tingkat_kebersihan')->default(85);

            // Anggaran Operasional
            $table->decimal('anggaran_pagu', 18, 2)->default(1250000000);
            $table->decimal('anggaran_terpakai', 18, 2)->default(900000000);
            $table->decimal('anggaran_sisa', 18, 2)->default(350000000);
            $table->integer('anggaran_persen')->default(72);

            // Kepuasan Internal
            $table->decimal('skor_kepuasan', 3, 1)->default(4.6);
            $table->decimal('kepuasan_kecepatan', 3, 1)->default(4.5);
            $table->decimal('kepuasan_kualitas', 3, 1)->default(4.6);
            $table->decimal('kepuasan_responsivitas', 3, 1)->default(4.7);
            $table->decimal('kepuasan_kebersihan', 3, 1)->default(4.6);
            $table->decimal('kepuasan_keamanan', 3, 1)->default(4.4);

            $table->string('footer_slogan')->default('Maluku Rumah Kita Bersama');
            $table->text('footer_nilai')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tvri_umum_ringkasans');
    }
};
