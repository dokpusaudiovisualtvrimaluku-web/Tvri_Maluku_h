<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TvriPromoRingkasan;
use App\Models\TvriPromoSop;

class TvriPromoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distribusiMedia = [
            ['media' => 'TV On-Air', 'persen' => 45, 'jumlah' => 554, 'color' => 'bg-indigo-600', 'textColor' => 'text-indigo-600'],
            ['media' => 'YouTube', 'persen' => 20, 'jumlah' => 246, 'color' => 'bg-rose-600', 'textColor' => 'text-rose-600'],
            ['media' => 'Instagram', 'persen' => 15, 'jumlah' => 185, 'color' => 'bg-pink-600', 'textColor' => 'text-pink-600'],
            ['media' => 'TikTok', 'persen' => 10, 'jumlah' => 123, 'color' => 'bg-slate-800', 'textColor' => 'text-slate-800'],
            ['media' => 'Website', 'persen' => 5, 'jumlah' => 62, 'color' => 'bg-blue-600', 'textColor' => 'text-blue-600'],
            ['media' => 'Media Luar Ruang', 'persen' => 5, 'jumlah' => 60, 'color' => 'bg-emerald-600', 'textColor' => 'text-emerald-600'],
        ];

        $capaianKampanye = [
            ['kategori' => 'Identitas TVRI Maluku', 'persen' => 120, 'status' => 'Melampaui Target', 'color' => 'bg-emerald-500'],
            ['kategori' => 'Program Unggulan', 'persen' => 95, 'status' => 'On Track', 'color' => 'bg-indigo-500'],
            ['kategori' => 'Berita & Current Affairs', 'persen' => 88, 'status' => 'Baik', 'color' => 'bg-blue-500'],
            ['kategori' => 'Kegiatan Daerah', 'persen' => 92, 'status' => 'On Track', 'color' => 'bg-cyan-500'],
            ['kategori' => 'Layanan Publik', 'persen' => 76, 'status' => 'Perhatian', 'color' => 'bg-amber-500'],
            ['kategori' => 'Event Khusus', 'persen' => 110, 'status' => 'Melampaui Target', 'color' => 'bg-purple-500'],
        ];

        $topKampanye = [
            ['nama' => 'Maluku Bisa!', 'reach' => '420K Reach', 'engagement' => '68K Engagement', 'pertumbuhan' => '+56%', 'peringkat' => 1],
            ['nama' => 'Dialog Maluku', 'reach' => '320K Reach', 'engagement' => '52K Engagement', 'pertumbuhan' => '+38%', 'peringkat' => 2],
            ['nama' => 'Pesona Maluku', 'reach' => '286K Reach', 'engagement' => '40K Engagement', 'pertumbuhan' => '+32%', 'peringkat' => 3],
            ['nama' => 'UMKM Maluku', 'reach' => '210K Reach', 'engagement' => '28K Engagement', 'pertumbuhan' => '+25%', 'peringkat' => 4],
            ['nama' => 'Ayo Nonton TVRI', 'reach' => '198K Reach', 'engagement' => '24K Engagement', 'pertumbuhan' => '+18%', 'peringkat' => 5],
        ];

        $platformDigital = [
            [
                'platform' => 'YouTube',
                'audience' => '286K Subscribers',
                'aud_growth' => '+12%',
                'metric' => '1,2M Views',
                'metric_growth' => '+35%',
                'color' => 'text-rose-600',
                'bg' => 'bg-rose-50 border-rose-100',
            ],
            [
                'platform' => 'Instagram',
                'audience' => '186K Followers',
                'aud_growth' => '+18%',
                'metric' => '520K Reach',
                'metric_growth' => '+42%',
                'color' => 'text-pink-600',
                'bg' => 'bg-pink-50 border-pink-100',
            ],
            [
                'platform' => 'TikTok',
                'audience' => '142K Followers',
                'aud_growth' => '+28%',
                'metric' => '480K Views',
                'metric_growth' => '+55%',
                'color' => 'text-slate-900',
                'bg' => 'bg-slate-100 border-slate-200',
            ],
            [
                'platform' => 'Website',
                'audience' => '88K Visitors',
                'aud_growth' => '+20%',
                'metric' => '3,5 Min Avg. Session',
                'metric_growth' => '+15%',
                'color' => 'text-blue-600',
                'bg' => 'bg-blue-50 border-blue-100',
            ],
        ];

        $statusProduksi = [
            'total' => 56,
            'items' => [
                ['status' => 'Selesai', 'jumlah' => 28, 'persen' => 50, 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'bar' => 'bg-emerald-500'],
                ['status' => 'Dalam Produksi', 'jumlah' => 15, 'persen' => 27, 'badge' => 'bg-blue-50 text-blue-700 border-blue-200', 'bar' => 'bg-blue-500'],
                ['status' => 'Review', 'jumlah' => 8, 'persen' => 14, 'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200', 'bar' => 'bg-indigo-500'],
                ['status' => 'Revisi', 'jumlah' => 3, 'persen' => 5, 'badge' => 'bg-amber-50 text-amber-700 border-amber-200', 'bar' => 'bg-amber-500'],
                ['status' => 'Ditunda', 'jumlah' => 2, 'persen' => 4, 'badge' => 'bg-slate-100 text-slate-700 border-slate-200', 'bar' => 'bg-slate-400'],
            ]
        ];

        $jadwalTayang = [
            ['waktu' => '06:15', 'program' => 'Maluku Pagi Ini', 'media' => 'TV', 'durasi' => 30, 'status' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
            ['waktu' => '08:30', 'program' => 'Pesona Maluku', 'media' => 'TV', 'durasi' => 30, 'status' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
            ['waktu' => '12:00', 'program' => 'UMKM Maluku', 'media' => 'TV', 'durasi' => 30, 'status' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
            ['waktu' => '15:30', 'program' => 'Dialog Maluku', 'media' => 'TV', 'durasi' => 60, 'status' => 'Menunggu', 'badge' => 'bg-amber-50 text-amber-700 border border-amber-200 animate-pulse'],
            ['waktu' => '17:45', 'program' => 'Maluku Bisa!', 'media' => 'TV', 'durasi' => 30, 'status' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
            ['waktu' => '19:30', 'program' => 'Ayo Nonton TVRI', 'media' => 'TV', 'durasi' => 30, 'status' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
            ['waktu' => '21:00', 'program' => 'Promo Program Malam', 'media' => 'TV', 'durasi' => 30, 'status' => 'Terjadwal', 'badge' => 'bg-blue-50 text-blue-700 border border-blue-200'],
            ['waktu' => '22:15', 'program' => 'Layanan Publik', 'media' => 'TV', 'durasi' => 30, 'status' => 'Terjadwal', 'badge' => 'bg-blue-50 text-blue-700 border border-blue-200'],
        ];

        $agendaPromo = [
            ['tanggal' => '4 Sep 2025', 'kegiatan' => 'Promo HUT Maluku', 'pic' => 'Tim Promo', 'status' => 'Persiapan', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200'],
            ['tanggal' => '8 Sep 2025', 'kegiatan' => 'Kampanye UMKM', 'pic' => 'Tim Promo', 'status' => 'On Progress', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'],
            ['tanggal' => '12 Sep 2025', 'kegiatan' => 'Peluncuran Program Baru', 'pic' => 'Tim Promo', 'status' => 'Persiapan', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200'],
            ['tanggal' => '20 Sep 2025', 'kegiatan' => 'Event Maluku Kreatif', 'pic' => 'Tim Promo', 'status' => 'Planning', 'badge' => 'bg-slate-100 text-slate-700 border-slate-200'],
            ['tanggal' => '25 Sep 2025', 'kegiatan' => 'Promo Hari Pariwisata', 'pic' => 'Tim Promo', 'status' => 'On Progress', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'],
        ];

        TvriPromoRingkasan::updateOrCreate(
            ['id' => 1],
            [
                'total_kampanye' => 24,
                'diff_kampanye' => '+33%',
                'materi_diproduksi' => 56,
                'diff_materi' => '+27%',
                'penayangan_onair' => '1.230 spot',
                'diff_onair' => '+18%',
                'reach_total' => '1,8 Juta',
                'diff_reach' => '+42%',
                'engagement_digital' => '286 Ribu',
                'diff_engagement' => '+35%',
                'tingkat_capaian' => 92,
                'target_capaian' => 90,
                'distribusi_media_json' => $distribusiMedia,
                'capaian_kampanye_json' => $capaianKampanye,
                'top_kampanye_json' => $topKampanye,
                'platform_digital_json' => $platformDigital,
                'status_produksi_json' => $statusProduksi,
                'jadwal_tayang_json' => $jadwalTayang,
                'agenda_promo_json' => $agendaPromo,
            ]
        );

        if (TvriPromoSop::count() === 0) {
            $defaultSops = [
                [
                    'kode' => 'SOP-PRM-01',
                    'judul' => 'Produksi & Kurasi Video Promo Program Unggulan',
                    'kategori' => 'Produksi Materi',
                    'versi' => 'v1.0',
                    'tanggal_efektif' => '2026-01-02',
                    'status' => 'Aktif',
                    'keterangan' => 'Standar operasional pembuatan spot promo dan bumper program unggulan TVRI Maluku on-air serta digital.',
                    'urutan' => 1,
                ],
                [
                    'kode' => 'SOP-PRM-02',
                    'judul' => 'Penjadwalan & Rotasi Filler, Bumper & ILM On-Air',
                    'kategori' => 'Penyiaran & Traffic',
                    'versi' => 'v1.0',
                    'tanggal_efektif' => '2026-01-05',
                    'status' => 'Aktif',
                    'keterangan' => 'Prosedur penentuan slot dan penjadwalan harian materi bumper stasiun serta iklan layanan masyarakat.',
                    'urutan' => 2,
                ],
                [
                    'kode' => 'SOP-PRM-03',
                    'judul' => 'Distribusi & Amplifikasi Promo Multi-Platform Digital',
                    'kategori' => 'Distribusi Digital',
                    'versi' => 'v1.0',
                    'tanggal_efektif' => '2026-01-10',
                    'status' => 'Aktif',
                    'keterangan' => 'Tata kelola publikasi materi kampanye promo di kanal YouTube, Instagram Reels, dan TikTok TVRI Maluku.',
                    'urutan' => 3,
                ],
                [
                    'kode' => 'SOP-PRM-04',
                    'judul' => 'Monitoring, Analisis Reach & Evaluasi Kampanye',
                    'kategori' => 'Evaluasi Kinerja',
                    'versi' => 'v1.0',
                    'tanggal_efektif' => '2026-01-15',
                    'status' => 'Aktif',
                    'keterangan' => 'Pedoman evaluasi capaian reach, impresi, dan engagement audiens atas seluruh materi promosi lembaga.',
                    'urutan' => 4,
                ],
            ];

            foreach ($defaultSops as $sop) {
                TvriPromoSop::create($sop);
            }
        }
    }
}
