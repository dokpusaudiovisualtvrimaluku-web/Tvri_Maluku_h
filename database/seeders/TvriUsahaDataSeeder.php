<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TvriUsahaRingkasan;
use App\Models\TvriUsahaSop;
use App\Models\TvriMitra;
use App\Models\TvriKegiatanUsaha;
use App\Models\TvriPnbp;
use Illuminate\Support\Facades\DB;

class TvriUsahaDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ringkasan Dashboard Pengembangan Usaha
        TvriUsahaRingkasan::updateOrCreate(
            ['id' => 1],
            [
                'judul' => 'DASHBOARD PENGEMBANGAN USAHA TVRI MALUKU',
                'sub_judul' => 'Sinergi Konten, Kemitraan, dan Layanan untuk Keberlanjutan',
                'slogan' => 'Maluku Lebih Baik Bersama',
                'pilar' => 'Kolaborasi, Inovasi, Nilai Tambah, Berdampak Nyata',
                'kutipan' => 'Menggerakkan Potensi Membangun Masa Depan',
                'waktu' => 'Senin, 18 November 2024, 10:24 WIT',
                'tagline_peta' => 'Dari Maluku untuk Indonesia',

                'total_pendapatan' => 2850000000,
                'diff_total_pendapatan' => '▲ 18,6% vs periode sebelumnya',
                'target_pendapatan' => 3400000000,
                'capaian_target' => 83.8,
                'jumlah_kontrak_aktif' => 28,
                'diff_kontrak_aktif' => '▲ 12,0% vs periode sebelumnya',
                'jumlah_mitra' => 24,
                'diff_mitra' => '▲ 26,3% vs periode sebelumnya',
                'kegiatan_usaha' => 12,
                'diff_kegiatan' => '▲ 50,0% vs periode sebelumnya',

                'komposisi_iklan_pct' => 35.1,
                'komposisi_sponsorship_pct' => 24.6,
                'komposisi_event_pct' => 18.9,
                'komposisi_sewa_pct' => 12.3,
                'komposisi_konten_pct' => 6.7,
                'komposisi_lainnya_pct' => 2.4,

                'operasional_promosi_pagu' => 400000000,
                'operasional_promosi_realisasi' => 300000000,
                'operasional_promosi_pct' => 75,

                'operasional_event_pagu' => 500000000,
                'operasional_event_realisasi' => 340000000,
                'operasional_event_pct' => 68,

                'operasional_aset_pagu' => 400000000,
                'operasional_aset_realisasi' => 220000000,
                'operasional_aset_pct' => 55,

                'operasional_konten_pagu' => 300000000,
                'operasional_konten_realisasi' => 180000000,
                'operasional_konten_pct' => 60,

                'pipeline_prospek' => 42,
                'pipeline_pendekatan' => 28,
                'pipeline_proposal' => 17,
                'pipeline_negosiasi' => 9,
                'pipeline_kontrak' => 5,

                'mitra_ambon' => 12,
                'mitra_tual' => 4,
                'mitra_saumlaki' => 3,
                'mitra_buru' => 2,
                'mitra_seram' => 2,
                'mitra_lainnya' => 1,

                'indeks_kinerja' => 82,
                'indeks_predikat' => 'Baik',
                'skor_pertumbuhan_pendapatan' => 85,
                'skor_diversifikasi_usaha' => 80,
                'skor_kemitraan_strategis' => 78,
                'skor_efisiensi_operasional' => 82,
                'skor_inovasi_produk' => 85,

                'fokus_utama' => 'Ekspansi Kemitraan Lintas Sektor, Optimalisasi Aset & Facility, Pengembangan Event dan Aktivasi, Inovasi Konten Berbasis Potensi Lokal, Digitalisasi Layanan Usaha',
                'sudah_berjalan_json' => [
                    'Penguatan tim pemasaran',
                    'Rebranding proposal usaha',
                    'Penawaran paket integrated marketing'
                ],
                'dalam_proses_json' => [
                    'Pengembangan e-catalog usaha',
                    'Kerja sama dengan Pemda & BUMN',
                    'Persiapan event besar 2025'
                ],
                'rencana_selanjutnya_json' => [
                    'Ekspansi pasar regional',
                    'Inovasi layanan digital',
                    'Peningkatan pendapatan 30% di 2025'
                ],

                'nilai_dasar' => 'Integritas, Profesional, Kolaboratif, Inovatif, Berdampak',
                'quote_penutup' => 'Media untuk Masa Depan yang Lebih Baik',
            ]
        );

        // 2. Mitra / Klien (Top 5 + Regional)
        $mitras = [
            ['nama' => 'Bank Maluku Malut', 'jenis_kerja_sama' => 'Iklan & Sponsorship', 'nilai_kontrak' => 600000000, 'status' => 'Aktif', 'lokasi' => 'Ambon'],
            ['nama' => 'Telkom Indonesia', 'jenis_kerja_sama' => 'Program & Event', 'nilai_kontrak' => 450000000, 'status' => 'Aktif', 'lokasi' => 'Ambon'],
            ['nama' => 'Pemda Maluku', 'jenis_kerja_sama' => 'Kampanye Publik', 'nilai_kontrak' => 400000000, 'status' => 'Aktif', 'lokasi' => 'Ambon'],
            ['nama' => 'PLN UIP Maluku', 'jenis_kerja_sama' => 'Iklan Layanan', 'nilai_kontrak' => 300000000, 'status' => 'Proses', 'lokasi' => 'Ambon'],
            ['nama' => 'Pelindo Regional', 'jenis_kerja_sama' => 'Event & Aktivasi', 'nilai_kontrak' => 250000000, 'status' => 'Aktif', 'lokasi' => 'Ambon'],
            ['nama' => 'Pertamina Patra Niaga', 'jenis_kerja_sama' => 'Sponsorship & Event', 'nilai_kontrak' => 220000000, 'status' => 'Aktif', 'lokasi' => 'Ambon'],
            ['nama' => 'Bank Mandiri Area Maluku', 'jenis_kerja_sama' => 'Iklan & Sponsorship', 'nilai_kontrak' => 180000000, 'status' => 'Aktif', 'lokasi' => 'Ambon'],
            ['nama' => 'Pemkot Ambon', 'jenis_kerja_sama' => 'Iklan Layanan Masyarakat', 'nilai_kontrak' => 150000000, 'status' => 'Aktif', 'lokasi' => 'Ambon'],
            ['nama' => 'Universitas Pattimura', 'jenis_kerja_sama' => 'Kerja Sama Konten & Event', 'nilai_kontrak' => 120000000, 'status' => 'Aktif', 'lokasi' => 'Ambon'],
            ['nama' => 'BNI KC Ambon', 'jenis_kerja_sama' => 'Iklan & Promosi', 'nilai_kontrak' => 100000000, 'status' => 'Aktif', 'lokasi' => 'Ambon'],
            ['nama' => 'Pemkab Maluku Tenggara', 'jenis_kerja_sama' => 'Kampanye Publik', 'nilai_kontrak' => 80000000, 'status' => 'Aktif', 'lokasi' => 'Tual'],
            ['nama' => 'Pemkot Tual', 'jenis_kerja_sama' => 'Sponsorship Program', 'nilai_kontrak' => 65000000, 'status' => 'Aktif', 'lokasi' => 'Tual'],
            ['nama' => 'Pemkab Kepulauan Tanimbar', 'jenis_kerja_sama' => 'Sponsorship Program', 'nilai_kontrak' => 55000000, 'status' => 'Aktif', 'lokasi' => 'Saumlaki'],
            ['nama' => 'Pemkab Buru', 'jenis_kerja_sama' => 'Kampanye Publik', 'nilai_kontrak' => 45000000, 'status' => 'Aktif', 'lokasi' => 'Buru'],
            ['nama' => 'Pemkab Seram Bagian Barat', 'jenis_kerja_sama' => 'Kampanye Publik', 'nilai_kontrak' => 40000000, 'status' => 'Aktif', 'lokasi' => 'Seram'],
        ];

        TvriMitra::truncate();
        foreach ($mitras as $m) {
            TvriMitra::create($m);
        }

        // 3. Kegiatan Usaha Terdekat
        $kegiatans = [
            ['tanggal' => '2024-11-20', 'kegiatan' => 'Maluku Expo 2024', 'jenis' => 'Event', 'estimasi_nilai' => 150000000, 'status' => 'Persiapan'],
            ['tanggal' => '2024-11-25', 'kegiatan' => 'Talkshow UMKM', 'jenis' => 'Sponsorship', 'estimasi_nilai' => 100000000, 'status' => 'Kontrak'],
            ['tanggal' => '2024-11-28', 'kegiatan' => 'Festival Budaya', 'jenis' => 'Event', 'estimasi_nilai' => 200000000, 'status' => 'Persiapan'],
            ['tanggal' => '2024-12-02', 'kegiatan' => 'Program Khusus Nataru', 'jenis' => 'Sponsorship', 'estimasi_nilai' => 250000000, 'status' => 'Negosiasi'],
            ['tanggal' => '2024-12-10', 'kegiatan' => 'Kampanye Layanan Publik', 'jenis' => 'Iklan', 'estimasi_nilai' => 150000000, 'status' => 'Kontrak'],
            ['tanggal' => '2024-12-18', 'kegiatan' => 'Live Broadcast Akhir Tahun', 'jenis' => 'Event & Aktivasi', 'estimasi_nilai' => 180000000, 'status' => 'Persiapan'],
            ['tanggal' => '2024-12-28', 'kegiatan' => 'Dialog Interaktif Pilkada Damai', 'jenis' => 'Iklan Layanan', 'estimasi_nilai' => 120000000, 'status' => 'Kontrak'],
        ];

        TvriKegiatanUsaha::truncate();
        foreach ($kegiatans as $k) {
            TvriKegiatanUsaha::create($k);
        }

        // 4. Sumber Pendapatan & PNBP
        $pnbps = [
            ['jenis' => 'Iklan TV & Radio', 'target' => 1200000000, 'realisasi' => 1000000000, 'tahun' => 2024],
            ['jenis' => 'Sponsorship Program', 'target' => 850000000, 'realisasi' => 700000000, 'tahun' => 2024],
            ['jenis' => 'Event & Aktivasi', 'target' => 650000000, 'realisasi' => 540000000, 'tahun' => 2024],
            ['jenis' => 'Sewa Aset & Fasilitas', 'target' => 420000000, 'realisasi' => 350000000, 'tahun' => 2024],
            ['jenis' => 'Kerja Sama Konten', 'target' => 280000000, 'realisasi' => 190000000, 'tahun' => 2024],
        ];

        TvriPnbp::truncate();
        foreach ($pnbps as $p) {
            TvriPnbp::create($p);
        }

        // 5. SOP & Regulasi Pengembangan Usaha
        $sops = [
            [
                'kode' => 'SOP-PU-01',
                'judul' => 'Prosedur Penjualan Slot Iklan & Sponsorship TV/Radio',
                'kategori' => 'Kemitraan & Iklan',
                'versi' => 'v2.0',
                'tanggal_efektif' => '2024-01-15',
                'status' => 'Aktif',
                'keterangan' => 'Alur penerimaan order penayangan iklan, penentuan rate card, verifikasi kelayakan materi sensor, dan penerbitan bukti tayang siaran.',
                'urutan' => 1
            ],
            [
                'kode' => 'SOP-PU-02',
                'judul' => 'Prosedur Kerja Sama Event & Aktivasi Lapangan',
                'kategori' => 'Event & Aktivasi',
                'versi' => 'v1.5',
                'tanggal_efektif' => '2024-02-10',
                'status' => 'Aktif',
                'keterangan' => 'Mekanisme perencanaan event bersama mitra komersial/instansi, pembagian bagi hasil (revenue sharing), dan koordinasi teknis produksi lapangan.',
                'urutan' => 2
            ],
            [
                'kode' => 'SOP-PU-03',
                'judul' => 'Standar Penyewaan Aset, Studio, dan Fasilitas Siaran',
                'kategori' => 'Aset & Fasilitas',
                'versi' => 'v1.8',
                'tanggal_efektif' => '2024-03-01',
                'status' => 'Aktif',
                'keterangan' => 'Tata cara pengajuan sewa studio TVRI, OB Van, kamera siar, tower transmisi, dan fasilitas pendukung untuk pihak ketiga.',
                'urutan' => 3
            ],
            [
                'kode' => 'SOP-PU-04',
                'judul' => 'Tata Cara Penerimaan & Penyetoran PNBP Usaha ke Kas Negara',
                'kategori' => 'Keuangan & PNBP',
                'versi' => 'v2.1',
                'tanggal_efektif' => '2024-01-20',
                'status' => 'Aktif',
                'keterangan' => 'Prosedur penerbitan kode billing SIMPONI Kementerian Keuangan, verifikasi bukti transfer mitra, dan pelaporan realisasi PNBP.',
                'urutan' => 4
            ],
            [
                'kode' => 'SOP-PU-05',
                'judul' => 'Manajemen Hubungan Mitra, Negosiasi Kontrak & Evaluasi Kemitraan',
                'kategori' => 'Manajemen Risiko & Kontrak',
                'versi' => 'v1.0',
                'tanggal_efektif' => '2024-04-12',
                'status' => 'Aktif',
                'keterangan' => 'Standar pembuatan proposal bisnis, penyusunan naskah MoU/PKS kerja sama, pemantauan masa berlaku kontrak, dan mitigasi risiko piutang.',
                'urutan' => 5
            ]
        ];

        TvriUsahaSop::truncate();
        foreach ($sops as $sop) {
            TvriUsahaSop::create($sop);
        }
    }
}
