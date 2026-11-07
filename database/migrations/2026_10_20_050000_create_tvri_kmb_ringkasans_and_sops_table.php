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
        Schema::create('tvri_kmb_ringkasans', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->default('DASHBOARD KONTEN MEDIA BARU TVRI MALUKU');
            $table->string('tagline')->default('SATU KONTEN, LEBIH DEKAT DENGAN MALUKU, LEBIH LUAS UNTUK INDONESIA');
            $table->string('pilar')->default('KREATIF • RELEVAN • BERDAMPAK • BERKELANJUTAN');
            $table->string('slogan')->default('Maluku Bersama di Layar Digital!');
            $table->string('waktu_status')->default('Selasa, 2 September 2025 | 10:24 WIT | Live Data');
            $table->string('periode')->default('September 2025');
            $table->string('sub_judul')->default('TVRI MALUKU: Media Pemersatu Bangsa di Bumi Raja-Raja');
            $table->string('tagline_peta')->default('Maluku Bisa! Go Digital Go Global');

            // KPI
            $table->integer('total_konten')->default(256);
            $table->string('diff_total_konten')->default('▲ +28% dari bulan lalu');
            $table->bigInteger('total_views')->default(1200000);
            $table->string('diff_total_views')->default('▲ +35%');
            $table->bigInteger('total_reach')->default(980000);
            $table->string('diff_total_reach')->default('▲ +42%');
            $table->bigInteger('engagement')->default(84000);
            $table->string('diff_engagement')->default('▲ +38%');
            $table->bigInteger('watch_time')->default(125000);
            $table->string('diff_watch_time')->default('▲ +40%');
            $table->bigInteger('follower_growth')->default(12500);
            $table->string('diff_follower_growth')->default('▲ +18%');
            $table->decimal('growth_rate', 5, 2)->default(35);

            // Platform YouTube
            $table->string('yt_subs')->default('392K');
            $table->string('yt_views')->default('1.2M');
            $table->string('yt_diff_views')->default('▲ +35%');
            $table->string('yt_eng')->default('6.8%');
            $table->string('yt_diff_eng')->default('▲ +12%');

            // Platform Instagram
            $table->string('ig_followers')->default('286K');
            $table->string('ig_reach')->default('520K');
            $table->string('ig_diff_reach')->default('▲ +42%');
            $table->string('ig_eng')->default('8.4%');
            $table->string('ig_diff_eng')->default('▲ +18%');

            // Platform TikTok
            $table->string('tiktok_followers')->default('515K');
            $table->string('tiktok_views')->default('480K');
            $table->string('tiktok_diff_views')->default('▲ +55%');
            $table->string('tiktok_eng')->default('12.1%');
            $table->string('tiktok_diff_eng')->default('▲ +28%');

            // Platform Website
            $table->string('web_visitors')->default('88K');
            $table->string('web_views')->default('210K');
            $table->string('web_diff_views')->default('▲ +20%');
            $table->string('web_avg_duration')->default('3.5 Min');
            $table->string('web_diff_duration')->default('▲ +15%');

            // Distribusi Kategori (%)
            $table->decimal('kat_berita_pct', 5, 2)->default(28);
            $table->integer('kat_berita_total')->default(72);
            $table->decimal('kat_budaya_pct', 5, 2)->default(18);
            $table->integer('kat_budaya_total')->default(46);
            $table->decimal('kat_pariwisata_pct', 5, 2)->default(15);
            $table->integer('kat_pariwisata_total')->default(38);
            $table->decimal('kat_pendidikan_pct', 5, 2)->default(12);
            $table->integer('kat_pendidikan_total')->default(31);
            $table->decimal('kat_umkm_pct', 5, 2)->default(10);
            $table->integer('kat_umkm_total')->default(26);
            $table->decimal('kat_talkshow_pct', 5, 2)->default(8);
            $table->integer('kat_talkshow_total')->default(21);
            $table->decimal('kat_khusus_pct', 5, 2)->default(6);
            $table->integer('kat_khusus_total')->default(15);
            $table->decimal('kat_lainnya_pct', 5, 2)->default(3);
            $table->integer('kat_lainnya_total')->default(7);

            // Demografi Usia (%)
            $table->decimal('usia_13_17', 5, 2)->default(12);
            $table->decimal('usia_18_24', 5, 2)->default(28);
            $table->decimal('usia_25_34', 5, 2)->default(32);
            $table->decimal('usia_35_44', 5, 2)->default(18);
            $table->decimal('usia_45_54', 5, 2)->default(8);
            $table->decimal('usia_55_plus', 5, 2)->default(2);

            // Peta Sebaran Audiens (%)
            $table->decimal('aud_maluku', 5, 2)->default(38);
            $table->decimal('aud_malut', 5, 2)->default(12);
            $table->decimal('aud_jawa', 5, 2)->default(18);
            $table->decimal('aud_sulawesi', 5, 2)->default(10);
            $table->decimal('aud_papua', 5, 2)->default(8);
            $table->decimal('aud_nusa', 5, 2)->default(6);
            $table->decimal('aud_lainnya', 5, 2)->default(8);

            // Perangkat (%)
            $table->decimal('device_mobile', 5, 2)->default(78);
            $table->decimal('device_desktop', 5, 2)->default(16);
            $table->decimal('device_tablet', 5, 2)->default(4);
            $table->decimal('device_smart_tv', 5, 2)->default(2);

            // Target & Capaian
            $table->bigInteger('target_views')->default(1000000);
            $table->integer('capaian_views_pct')->default(120);
            $table->bigInteger('target_engagement')->default(70000);
            $table->integer('capaian_engagement_pct')->default(120);
            $table->bigInteger('target_follower_growth')->default(10000);
            $table->integer('capaian_follower_growth_pct')->default(125);
            $table->integer('target_konten')->default(200);
            $table->integer('capaian_konten_pct')->default(128);

            // Kalender & Notifikasi JSON
            $table->text('kalender_json')->nullable();
            $table->text('notifikasi_json')->nullable();

            // Footer
            $table->text('footer_points_json')->nullable();
            $table->string('quote_penutup')->default('Maluku Bisa! Indonesia Maju!');

            $table->timestamps();
        });

        Schema::create('tvri_kmb_sops', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->default('SOP-KMB-01');
            $table->string('judul');
            $table->string('kategori')->default('Produksi Digital');
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
        Schema::dropIfExists('tvri_kmb_sops');
        Schema::dropIfExists('tvri_kmb_ringkasans');
    }
};
