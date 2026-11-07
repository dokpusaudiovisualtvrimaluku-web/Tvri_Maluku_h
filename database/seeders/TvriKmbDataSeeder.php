<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TvriKmbRingkasan;
use App\Models\TvriKmbSop;
use App\Models\TvriKonten;
use Illuminate\Support\Facades\DB;

class TvriKmbDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ringkasan Dashboard KMB
        TvriKmbRingkasan::updateOrCreate(
            ['id' => 1],
            [
                'judul' => 'DASHBOARD KONTEN MEDIA BARU TVRI MALUKU',
                'tagline' => 'SATU KONTEN, LEBIH DEKAT DENGAN MALUKU, LEBIH LUAS UNTUK INDONESIA',
                'pilar' => 'KREATIF • RELEVAN • BERDAMPAK • BERKELANJUTAN',
                'slogan' => 'Maluku Bersama di Layar Digital!',
                'waktu_status' => 'Selasa, 2 September 2025 | 10:24 WIT | Live Data',
                'periode' => 'September 2025',
                'sub_judul' => 'TVRI MALUKU: Media Pemersatu Bangsa di Bumi Raja-Raja',
                'tagline_peta' => 'Maluku Bisa! Go Digital Go Global',

                // KPI
                'total_konten' => 256,
                'diff_total_konten' => '▲ +28% dari bulan lalu',
                'total_views' => 1200000,
                'diff_total_views' => '▲ +35%',
                'total_reach' => 980000,
                'diff_total_reach' => '▲ +42%',
                'engagement' => 84000,
                'diff_engagement' => '▲ +38%',
                'watch_time' => 125000,
                'diff_watch_time' => '▲ +40%',
                'follower_growth' => 12500,
                'diff_follower_growth' => '▲ +18%',
                'growth_rate' => 35,

                // YouTube
                'yt_subs' => '392K',
                'yt_views' => '1.2M',
                'yt_diff_views' => '▲ +35%',
                'yt_eng' => '6.8%',
                'yt_diff_eng' => '▲ +12%',

                // Instagram
                'ig_followers' => '286K',
                'ig_reach' => '520K',
                'ig_diff_reach' => '▲ +42%',
                'ig_eng' => '8.4%',
                'ig_diff_eng' => '▲ +18%',

                // TikTok
                'tiktok_followers' => '515K',
                'tiktok_views' => '480K',
                'tiktok_diff_views' => '▲ +55%',
                'tiktok_eng' => '12.1%',
                'tiktok_diff_eng' => '▲ +28%',

                // Website
                'web_visitors' => '88K',
                'web_views' => '210K',
                'web_diff_views' => '▲ +20%',
                'web_avg_duration' => '3.5 Min',
                'web_diff_duration' => '▲ +15%',

                // Kategori
                'kat_berita_pct' => 28,
                'kat_berita_total' => 72,
                'kat_budaya_pct' => 18,
                'kat_budaya_total' => 46,
                'kat_pariwisata_pct' => 15,
                'kat_pariwisata_total' => 38,
                'kat_pendidikan_pct' => 12,
                'kat_pendidikan_total' => 31,
                'kat_umkm_pct' => 10,
                'kat_umkm_total' => 26,
                'kat_talkshow_pct' => 8,
                'kat_talkshow_total' => 21,
                'kat_khusus_pct' => 6,
                'kat_khusus_total' => 15,
                'kat_lainnya_pct' => 3,
                'kat_lainnya_total' => 7,

                // Demografi Usia
                'usia_13_17' => 12,
                'usia_18_24' => 28,
                'usia_25_34' => 32,
                'usia_35_44' => 18,
                'usia_45_54' => 8,
                'usia_55_plus' => 2,

                // Sebaran Audiens
                'aud_maluku' => 38,
                'aud_malut' => 12,
                'aud_jawa' => 18,
                'aud_sulawesi' => 10,
                'aud_papua' => 8,
                'aud_nusa' => 6,
                'aud_lainnya' => 8,

                // Perangkat
                'device_mobile' => 78,
                'device_desktop' => 16,
                'device_tablet' => 4,
                'device_smart_tv' => 2,

                // Target & Capaian
                'target_views' => 1000000,
                'capaian_views_pct' => 120,
                'target_engagement' => 70000,
                'capaian_engagement_pct' => 120,
                'target_follower_growth' => 10000,
                'capaian_follower_growth_pct' => 125,
                'target_konten' => 200,
                'capaian_konten_pct' => 128,

                // Kalender Konten
                'kalender_json' => [
                    ['tanggal' => '2 Sep Sel', 'judul' => 'Maluku Hari Ini', 'format' => 'Short Video', 'status' => 'Published'],
                    ['tanggal' => '3 Sep Rab', 'judul' => 'Tips Wisata Maluku', 'format' => 'Reels', 'status' => 'Scheduled'],
                    ['tanggal' => '4 Sep Kam', 'judul' => 'Dialog Maluku', 'format' => 'Full Video', 'status' => 'Editing'],
                    ['tanggal' => '5 Sep Jum', 'judul' => 'UMKM Maluku', 'format' => 'Short Video', 'status' => 'Draft'],
                    ['tanggal' => '6 Sep Sab', 'judul' => 'Pesona Alam Maluku', 'format' => 'Reels', 'status' => 'Planning'],
                ],

                // Notifikasi & Insight
                'notifikasi_json' => [
                    ['tipe' => 'positive', 'simbol' => '+', 'pesan' => 'Konten "Pesona Raja Ampat" trending +120%'],
                    ['tipe' => 'positive', 'simbol' => '+', 'pesan' => 'Engagement TikTok meningkat 28%'],
                    ['tipe' => 'info', 'simbol' => '!', 'pesan' => 'Waktu terbaik posting: 18.00 - 21.00 WIT'],
                    ['tipe' => 'info', 'simbol' => '!', 'pesan' => 'Pertumbuhan followers di atas target'],
                    ['tipe' => 'warning', 'simbol' => '⚠', 'pesan' => 'Perlu peningkatan variasi konten edukasi'],
                ],

                // Footer
                'footer_points_json' => [
                    'Konten Lokal Untuk Dunia',
                    'Audiens Lebih Dekat Maluku Lebih Dikenal',
                    'Kreativitas Tanpa Batas Inspirasi Tiada Henti',
                    'Data untuk Aksi Aksi untuk Dampak',
                    'TVRI Maluku Mendunia'
                ],
                'quote_penutup' => 'Maluku Bisa! Indonesia Maju!'
            ]
        );

        // 2. Data Konten (Top 5 + Tambahan)
        $kontens = [
            [
                'judul' => 'Pesona Raja Ampat dari Maluku',
                'platform' => 'YouTube',
                'kategori' => 'Pariwisata',
                'views' => 328000,
                'reach' => 290000,
                'engagement' => 24500,
                'watch_time' => 38000,
                'tanggal' => '2025-08-25',
                'status' => 'Published'
            ],
            [
                'judul' => 'Dialog Maluku: Geliat UMKM Lokal',
                'platform' => 'YouTube',
                'kategori' => 'UMKM',
                'views' => 256000,
                'reach' => 210000,
                'engagement' => 19200,
                'watch_time' => 29500,
                'tanggal' => '2025-08-18',
                'status' => 'Published'
            ],
            [
                'judul' => 'Festival Budaya Pela Gandong',
                'platform' => 'Instagram',
                'kategori' => 'Budaya',
                'views' => 198000,
                'reach' => 180000,
                'engagement' => 16800,
                'watch_time' => 21000,
                'tanggal' => '2025-08-12',
                'status' => 'Published'
            ],
            [
                'judul' => 'Maluku Hari Ini - Edisi Spesial',
                'platform' => 'TikTok',
                'kategori' => 'Berita',
                'views' => 176000,
                'reach' => 165000,
                'engagement' => 21400,
                'watch_time' => 18200,
                'tanggal' => '2025-08-28',
                'status' => 'Published'
            ],
            [
                'judul' => 'Keindahan Pantai Ora',
                'platform' => 'Instagram',
                'kategori' => 'Pariwisata',
                'views' => 142000,
                'reach' => 135000,
                'engagement' => 12000,
                'watch_time' => 18300,
                'tanggal' => '2025-08-10',
                'status' => 'Published'
            ],
            [
                'judul' => 'Tips Wisata Maluku (Reels)',
                'platform' => 'Instagram',
                'kategori' => 'Pariwisata',
                'views' => 95000,
                'reach' => 90000,
                'engagement' => 8500,
                'watch_time' => 9200,
                'tanggal' => '2025-09-01',
                'status' => 'Published'
            ],
            [
                'judul' => 'Inovasi Pendidikan Pulau Terluar',
                'platform' => 'Website',
                'kategori' => 'Pendidikan',
                'views' => 45000,
                'reach' => 40000,
                'engagement' => 3800,
                'watch_time' => 4800,
                'tanggal' => '2025-08-30',
                'status' => 'Published'
            ],
        ];

        TvriKonten::truncate();
        foreach ($kontens as $k) {
            TvriKonten::create($k);
        }

        // 3. SOP & Regulasi Konten Media Baru
        $sops = [
            [
                'kode' => 'SOP-KMB-01',
                'judul' => 'Standar Produksi & Pengunggahan Konten Video Pendek (Shorts/Reels/TikTok)',
                'kategori' => 'Produksi Digital',
                'versi' => 'v2.0',
                'tanggal_efektif' => '2025-01-15',
                'status' => 'Aktif',
                'keterangan' => 'Standar rasio vertikal 9:16, resolusi 1080x1920, durasi hook 3 detik pertama, penyematan subtitle otomatis, dan ketentuan hak cipta audio.',
                'urutan' => 1
            ],
            [
                'kode' => 'SOP-KMB-02',
                'judul' => 'Prosedur Siaran Langsung (Live Streaming) Multiplatform YouTube & Medsos',
                'kategori' => 'Live Streaming',
                'versi' => 'v1.5',
                'tanggal_efektif' => '2025-02-10',
                'status' => 'Aktif',
                'keterangan' => 'Alur uji coba bitrate encoder OBS/vMix, redundansi koneksi internet, penjadwalan siaran YouTube Live, dan penyiapan interaksi live chat.',
                'urutan' => 2
            ],
            [
                'kode' => 'SOP-KMB-03',
                'judul' => 'Manajemen Komentar, Moderasi Netizen & Interaksi Media Sosial',
                'kategori' => 'Media Sosial',
                'versi' => 'v1.8',
                'tanggal_efektif' => '2025-03-01',
                'status' => 'Aktif',
                'keterangan' => 'Panduan respon engagement audiens, filtrasi kata-kata kasar/SARA, eskalasi komplain warganet, dan penjagaan reputasi TVRI Maluku.',
                'urutan' => 3
            ],
            [
                'kode' => 'SOP-KMB-04',
                'judul' => 'Tata Cara Penerbitan Berita & Artikel Portal Website TVRI Maluku',
                'kategori' => 'Web Publishing',
                'versi' => 'v2.1',
                'tanggal_efektif' => '2025-01-20',
                'status' => 'Aktif',
                'keterangan' => 'Pedoman SEO konten, pembuatan thumbnail beresolusi tinggi, penulisan headline jurnalistik digital, dan integrasi link video siaran.',
                'urutan' => 4
            ],
            [
                'kode' => 'SOP-KMB-05',
                'judul' => 'Kemitraan Kolaborasi Konten Kreator & Skema Monetisasi Digital',
                'kategori' => 'Monetisasi & Kolaborasi',
                'versi' => 'v1.0',
                'tanggal_efektif' => '2025-04-12',
                'status' => 'Aktif',
                'keterangan' => 'Prosedur penandatanganan kerja sama kreator lokal, cross-posting konten, program revenue sharing AdSense/brand deal, serta evaluasi engagement.',
                'urutan' => 5
            ]
        ];

        TvriKmbSop::truncate();
        foreach ($sops as $sop) {
            TvriKmbSop::create($sop);
        }
    }
}
