<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ringkasan Parameter Dashboard Teknik
        Schema::create('tvri_teknik_ringkasans', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->default('DASHBOARD TEKNIK TVRI MALUKU');
            $table->string('sub_judul')->default('TVRI MALUKU Media Pemersatu Bangsa di Bumi Raja-Raja');
            $table->string('slogan')->default('SIARAN ANDAL • TEKNOLOGI TERDEPAN • LAYANAN TANPA HENTI');
            $table->string('kutipan')->default('Infrastruktur Kuat, Siaran Berkualitas, untuk Maluku dan Indonesia');
            $table->string('tagline')->default('Maluku Bisa Lebih Jauh!');
            $table->string('waktu_status')->default('Selasa, 2 September 2025 | 10:24 WIT | Online');
            $table->string('periode')->default('September 2025');

            // KPI
            $table->integer('on_air_pct')->default(100);
            $table->integer('transmitter_on')->default(8);
            $table->integer('transmitter_total')->default(8);
            $table->integer('studio_ready')->default(4);
            $table->integer('studio_total')->default(4);
            $table->integer('peralatan_kritis_pct')->default(96);
            $table->integer('insiden_hari_ini')->default(1);
            $table->integer('insiden_diff')->default(-67);
            $table->integer('maintenance_pct')->default(92);
            $table->integer('maintenance_target')->default(90);
            $table->integer('efisiensi_energi_pct')->default(87);
            $table->integer('efisiensi_diff')->default(-5);

            // Status Siaran Live
            $table->string('siaran_program')->default('MALUKU HARI INI');
            $table->string('siaran_tagline')->default('Informasi untuk Semua');
            $table->string('running_text')->default('TVRI 10:24 WIT MALUKU: POTENSI MARITIM UNTUK MASA DEPAN >>');
            $table->string('siaran_sumber')->default('Studio 1');
            $table->string('siaran_resolusi')->default('1080i HD');
            $table->string('siaran_audio')->default('Stereo');
            $table->string('siaran_bitrate')->default('8 Mbps');
            $table->string('siaran_durasi')->default('02:14:25');
            $table->string('siaran_status')->default('Normal');

            // Technical Readiness Index
            $table->integer('readiness_overall')->default(94);
            $table->string('readiness_predikat')->default('Sangat Baik');
            $table->json('readiness_items_json')->nullable();

            // Peta Jangkauan & Transmisi
            $table->string('tx_locations_text')->default('Ternate, Sanana, Namlea, Ambon, Masohi, Bula, Saumlaki');
            $table->integer('coverage_area_pct')->default(92);
            $table->string('estimasi_pemirsa')->default('1,2 Juta');

            // Monitoring Sistem
            $table->integer('video_pct')->default(100);
            $table->integer('audio_pct')->default(100);
            $table->integer('playout_pct')->default(100);
            $table->integer('streaming_pct')->default(99);
            $table->integer('uplink_pct')->default(100);
            $table->integer('network_pct')->default(96);
            $table->string('sinyal_trend')->default('Sinyal Stabil');

            // Status Studio & Inventaris (JSON)
            $table->json('status_studio_json')->nullable();
            $table->json('inventaris_json')->nullable();
            $table->json('footer_points_json')->nullable();
            $table->string('quote_penutup')->default('Maluku Bisa! Indonesia Maju!');

            $table->timestamps();
        });

        // 2. Jadwal Produksi & Teknik
        Schema::create('tvri_teknik_jadwals', function (Blueprint $table) {
            $table->id();
            $table->string('waktu');
            $table->string('program');
            $table->string('lokasi');
            $table->string('kebutuhan');
            $table->string('status');
            $table->string('badge')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // 3. Maintenance & Servis Peralatan
        Schema::create('tvri_teknik_maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal');
            $table->string('peralatan');
            $table->string('jenis');
            $table->string('pic');
            $table->string('status');
            $table->timestamps();
        });

        // 4. Insiden & Gangguan
        Schema::create('tvri_teknik_insidens', function (Blueprint $table) {
            $table->id();
            $table->string('waktu');
            $table->string('lokasi');
            $table->text('deskripsi');
            $table->string('status');
            $table->string('badge')->nullable();
            $table->timestamps();
        });

        // 5. Status Transmitter
        Schema::create('tvri_teknik_transmitters', function (Blueprint $table) {
            $table->id();
            $table->string('lokasi');
            $table->string('frekuensi');
            $table->string('daya');
            $table->string('status');
            $table->string('sinyal_warna')->default('Hijau');
            $table->timestamps();
        });

        // 6. Dokumen & SOP Teknik
        Schema::create('tvri_teknik_sops', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->nullable();
            $table->string('judul');
            $table->string('kategori')->default('SOP Studio & Siaran');
            $table->string('versi')->default('v1.0');
            $table->date('tanggal_efektif')->nullable();
            $table->string('status')->default('Aktif');
            $table->text('keterangan')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tvri_teknik_sops');
        Schema::dropIfExists('tvri_teknik_transmitters');
        Schema::dropIfExists('tvri_teknik_insidens');
        Schema::dropIfExists('tvri_teknik_maintenances');
        Schema::dropIfExists('tvri_teknik_jadwals');
        Schema::dropIfExists('tvri_teknik_ringkasans');
    }
};
