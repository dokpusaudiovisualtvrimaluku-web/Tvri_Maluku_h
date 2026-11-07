<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TvriTransmisiRingkasan;
use App\Models\TvriTransmisiPemeliharaan;
use App\Models\TvriTransmisiLog;
use App\Models\TvriTransmisiSop;
use App\Models\TvriStasiunPemancar;
use App\Models\TvriGangguan;

class TvriTransmisiDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ringkasan & Parameter Utama
        TvriTransmisiRingkasan::truncate();
        TvriTransmisiRingkasan::create([
            'judul' => 'DASHBOARD TRANSMISI TVRI MALUKU',
            'slogan' => 'SIARAN ANDAL • JANGKAUAN LUAS • KUALITAS TERJAGA • INDONESIA SEMAKIN DEKAT',
            'slogan_tambahan' => 'Maluku Bisa! Siaran Tanpa Batas!',
            'waktu_status' => 'Selasa, 2 September 2025, 10:24 WIT, Online',
            'periode' => 'September 2025',

            // Key Performance Indicators (KPI)
            'total_site' => 28,
            'total_site_diff' => '+4% dari bulan lalu',
            'on_air_pct' => 100,
            'on_air_target' => 99,
            'cakupan_wilayah_pct' => 92,
            'cakupan_diff' => '+3% dari bulan lalu',
            'kualitas_rata' => 4.6,
            'kualitas_diff' => '+0.2 dari bulan lalu',
            'gangguan_aktif' => 2,
            'gangguan_diff' => '-60% dari bulan lalu',
            'pemeliharaan_berjalan' => 3,
            'pemeliharaan_diff' => '-25% dari bulan lalu',
            'efisiensi_daya_pct' => 87,
            'efisiensi_diff' => '+5% dari bulan lalu',

            // Status Siaran per Platform
            'platform_siaran_json' => [
                ['platform' => 'TV Digital DVB-T2', 'status' => 'On-Air 100%', 'badge' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/30', 'ikon' => 'tv', 'keterangan' => '28 Site Pemancar Aktif Terestrial'],
                ['platform' => 'Satelit Telkom-4', 'status' => 'On-Air 100%', 'badge' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/30', 'ikon' => 'satellite', 'keterangan' => 'Transponder 3900 V 3000'],
                ['platform' => 'IPTV/Streaming TVRI Live', 'status' => 'On-Air 100%', 'badge' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/30', 'ikon' => 'wifi', 'keterangan' => 'TVRI Klik & OTT Platforms'],
                ['platform' => 'Radio Visual (Backhaul)', 'status' => 'Normal 100%', 'badge' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/30', 'ikon' => 'radio', 'keterangan' => 'Link Mikrowave Antar Site'],
            ],

            // Kualitas Sinyal Nasional & Regional
            'kualitas_sinyal_json' => [
                'skor' => 4.6,
                'predikat' => 'Sangat Baik',
                'breakdown' => [
                    ['bintang' => 5, 'label' => '5 Excellent', 'persen' => 52, 'color' => 'bg-emerald-500'],
                    ['bintang' => 4, 'label' => '4 Good', 'persen' => 36, 'color' => 'bg-cyan-500'],
                    ['bintang' => 3, 'label' => '3 Fair', 'persen' => 9, 'color' => 'bg-amber-500'],
                    ['bintang' => 2, 'label' => '2 Poor', 'persen' => 2, 'color' => 'bg-orange-500'],
                    ['bintang' => 1, 'label' => '1 Bad', 'persen' => 1, 'color' => 'bg-rose-500'],
                ],
            ],

            // Parameter Teknis (Real-Time)
            'parameter_teknis_json' => [
                ['param' => 'Level Sinyal', 'nilai' => '-48 dBm', 'status' => 'Baik', 'badge' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'],
                ['param' => 'SNR (Signal to Noise)', 'nilai' => '32 dB', 'status' => 'Baik', 'badge' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'],
                ['param' => 'Bit Error Rate (BER)', 'nilai' => '1.0E-6', 'status' => 'Baik', 'badge' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'],
                ['param' => 'Modulasi Digital', 'nilai' => '64QAM', 'status' => 'Normal', 'badge' => 'bg-blue-500/10 text-cyan-300 border border-blue-500/20'],
                ['param' => 'Bandwidth Kanal', 'nilai' => '8 MHz', 'status' => 'Normal', 'badge' => 'bg-blue-500/10 text-cyan-300 border border-blue-500/20'],
                ['param' => 'Suhu Pemancar TX', 'nilai' => '42 °C', 'status' => 'Normal', 'badge' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'],
                ['param' => 'Tegangan Listrik', 'nilai' => '220 V', 'status' => 'Normal', 'badge' => 'bg-blue-500/10 text-cyan-300 border border-blue-500/20'],
                ['param' => 'Arus Kerja', 'nilai' => '12 A', 'status' => 'Normal', 'badge' => 'bg-blue-500/10 text-cyan-300 border border-blue-500/20'],
                ['param' => 'Daya Pancar (ERP)', 'nilai' => '5 kW', 'status' => 'Normal', 'badge' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'],
            ],

            // Ketersediaan Infrastruktur
            'infrastruktur_json' => [
                ['item' => 'Menara Pemancar', 'persen' => 96, 'color' => 'bg-cyan-500'],
                ['item' => 'Antena Pemancar', 'persen' => 98, 'color' => 'bg-emerald-500'],
                ['item' => 'Perangkat TX', 'persen' => 95, 'color' => 'bg-blue-500'],
                ['item' => 'Genset & Power Cadangan', 'persen' => 93, 'color' => 'bg-amber-500'],
                ['item' => 'Catu Daya (UPS & PLN)', 'persen' => 97, 'color' => 'bg-indigo-500'],
            ],

            'footer_slogan' => 'Siaran Berkualitas • Layanan Tanpa Batas • Inovasi untuk Negeri • Integritas • Kolaborasi',
            'footer_quote' => 'Maluku Bisa! Indonesia Maju!',
        ]);

        // 2. Daftar 28 Stasiun Pemancar
        TvriStasiunPemancar::truncate();
        $stasiuns = [
            ['nama' => 'Ambon', 'lokasi' => 'Kota + Kab. Ambon', 'frekuensi' => '530 MHz', 'kanal' => 'CH 28', 'daya_kw' => 5.0, 'coverage' => 'Kota + Kab', 'status' => 'On-Air', 'kualitas' => 4.8, 'latitude' => -3.6954, 'longitude' => 128.1814],
            ['nama' => 'Ternate', 'lokasi' => 'Kota + Kab. Ternate', 'frekuensi' => '562 MHz', 'kanal' => 'CH 32', 'daya_kw' => 5.0, 'coverage' => 'Kota + Kab', 'status' => 'On-Air', 'kualitas' => 4.7, 'latitude' => 0.7833, 'longitude' => 127.3667],
            ['nama' => 'Masohi', 'lokasi' => 'Kab. Malteng', 'frekuensi' => '546 MHz', 'kanal' => 'CH 30', 'daya_kw' => 2.0, 'coverage' => 'Kab. Malteng', 'status' => 'On-Air', 'kualitas' => 4.5, 'latitude' => -3.3072, 'longitude' => 128.1748],
            ['nama' => 'Namlea', 'lokasi' => 'Kab. Buru', 'frekuensi' => '514 MHz', 'kanal' => 'CH 26', 'daya_kw' => 1.0, 'coverage' => 'Kab. Buru', 'status' => 'On-Air', 'kualitas' => 4.4, 'latitude' => -2.9778, 'longitude' => 127.6958],
            ['nama' => 'Saumlaki', 'lokasi' => 'Kep. Tanimbar', 'frekuensi' => '538 MHz', 'kanal' => 'CH 29', 'daya_kw' => 2.0, 'coverage' => 'Kep. Tanimbar', 'status' => 'On-Air', 'kualitas' => 4.6, 'latitude' => -7.9833, 'longitude' => 131.3167],
            ['nama' => 'Dobo', 'lokasi' => 'Kep. Aru', 'frekuensi' => '522 MHz', 'kanal' => 'CH 27', 'daya_kw' => 1.0, 'coverage' => 'Kep. Aru', 'status' => 'On-Air', 'kualitas' => 4.3, 'latitude' => -5.7500, 'longitude' => 134.2167],
            ['nama' => 'Sanana', 'lokasi' => 'Kep. Sula', 'frekuensi' => '506 MHz', 'kanal' => 'CH 25', 'daya_kw' => 1.0, 'coverage' => 'Kep. Sula', 'status' => 'Maintenance', 'kualitas' => 3.9, 'latitude' => -2.5000, 'longitude' => 125.5000],
            ['nama' => 'Morotai', 'lokasi' => 'Kab. Pulau Morotai', 'frekuensi' => '570 MHz', 'kanal' => 'CH 33', 'daya_kw' => 1.0, 'coverage' => 'Kab. Pulau Morotai', 'status' => 'Gangguan', 'kualitas' => 2.8, 'latitude' => 2.4333, 'longitude' => 128.3833],
            ['nama' => 'Bula', 'lokasi' => 'Kab. Seram Bagian Timur', 'frekuensi' => '554 MHz', 'kanal' => 'CH 31', 'daya_kw' => 1.0, 'coverage' => 'Kab. SBT', 'status' => 'On-Air', 'kualitas' => 4.5, 'latitude' => -3.1667, 'longitude' => 130.4333],
            ['nama' => 'Tiakur', 'lokasi' => 'Kab. Maluku Barat Daya', 'frekuensi' => '514 MHz', 'kanal' => 'CH 26', 'daya_kw' => 2.0, 'coverage' => 'Kab. MBD', 'status' => 'On-Air', 'kualitas' => 4.4, 'latitude' => -8.1333, 'longitude' => 127.9167],
            ['nama' => 'Tual', 'lokasi' => 'Kota Tual & Malra', 'frekuensi' => '530 MHz', 'kanal' => 'CH 28', 'daya_kw' => 2.0, 'coverage' => 'Kota Tual + Malra', 'status' => 'On-Air', 'kualitas' => 4.6, 'latitude' => -5.6333, 'longitude' => 132.7500],
            ['nama' => 'Piru', 'lokasi' => 'Kab. Seram Bagian Barat', 'frekuensi' => '546 MHz', 'kanal' => 'CH 30', 'daya_kw' => 1.0, 'coverage' => 'Kab. SBB', 'status' => 'On-Air', 'kualitas' => 4.5, 'latitude' => -3.0667, 'longitude' => 128.1833],
            ['nama' => 'Banda Neira', 'lokasi' => 'Kepulauan Banda', 'frekuensi' => '522 MHz', 'kanal' => 'CH 27', 'daya_kw' => 1.0, 'coverage' => 'Kep. Banda', 'status' => 'On-Air', 'kualitas' => 4.7, 'latitude' => -4.5261, 'longitude' => 129.9042],
            ['nama' => 'Airbuaya', 'lokasi' => 'Kab. Buru Utara', 'frekuensi' => '506 MHz', 'kanal' => 'CH 25', 'daya_kw' => 0.5, 'coverage' => 'Buru Utara', 'status' => 'On-Air', 'kualitas' => 4.3, 'latitude' => -3.1500, 'longitude' => 126.8667],
            ['nama' => 'Kairatu', 'lokasi' => 'Kab. Seram Bagian Barat', 'frekuensi' => '538 MHz', 'kanal' => 'CH 29', 'daya_kw' => 1.0, 'coverage' => 'SBB Selatan', 'status' => 'On-Air', 'kualitas' => 4.6, 'latitude' => -3.3333, 'longitude' => 128.3667],
            ['nama' => 'Labuha', 'lokasi' => 'Kab. Halmahera Selatan', 'frekuensi' => '562 MHz', 'kanal' => 'CH 32', 'daya_kw' => 2.0, 'coverage' => 'Halmahera Selatan', 'status' => 'On-Air', 'kualitas' => 4.5, 'latitude' => -0.6333, 'longitude' => 127.4833],
            ['nama' => 'Tobelo', 'lokasi' => 'Kab. Halmahera Utara', 'frekuensi' => '546 MHz', 'kanal' => 'CH 30', 'daya_kw' => 2.0, 'coverage' => 'Halmahera Utara', 'status' => 'On-Air', 'kualitas' => 4.6, 'latitude' => 1.7333, 'longitude' => 128.0000],
            ['nama' => 'Weda', 'lokasi' => 'Kab. Halmahera Tengah', 'frekuensi' => '530 MHz', 'kanal' => 'CH 28', 'daya_kw' => 1.0, 'coverage' => 'Halmahera Tengah', 'status' => 'On-Air', 'kualitas' => 4.4, 'latitude' => 0.3500, 'longitude' => 127.8833],
            ['nama' => 'Jailolo', 'lokasi' => 'Kab. Halmahera Barat', 'frekuensi' => '522 MHz', 'kanal' => 'CH 27', 'daya_kw' => 1.0, 'coverage' => 'Halmahera Barat', 'status' => 'On-Air', 'kualitas' => 4.5, 'latitude' => 1.0667, 'longitude' => 127.4833],
            ['nama' => 'Maba', 'lokasi' => 'Kab. Halmahera Timur', 'frekuensi' => '514 MHz', 'kanal' => 'CH 26', 'daya_kw' => 1.0, 'coverage' => 'Halmahera Timur', 'status' => 'On-Air', 'kualitas' => 4.3, 'latitude' => 0.7000, 'longitude' => 128.3000],
            ['nama' => 'Tidore', 'lokasi' => 'Kota Tidore Kepulauan', 'frekuensi' => '570 MHz', 'kanal' => 'CH 33', 'daya_kw' => 2.0, 'coverage' => 'Kota Tidore', 'status' => 'On-Air', 'kualitas' => 4.7, 'latitude' => 0.6833, 'longitude' => 127.4000],
            ['nama' => 'Ilwaki', 'lokasi' => 'Pulau Wetar (MBD)', 'frekuensi' => '506 MHz', 'kanal' => 'CH 25', 'daya_kw' => 0.5, 'coverage' => 'Pulau Wetar', 'status' => 'On-Air', 'kualitas' => 4.2, 'latitude' => -7.7833, 'longitude' => 126.1167],
            ['nama' => 'Wonreli', 'lokasi' => 'Pulau Kisar (MBD)', 'frekuensi' => '538 MHz', 'kanal' => 'CH 29', 'daya_kw' => 1.0, 'coverage' => 'Pulau Kisar', 'status' => 'On-Air', 'kualitas' => 4.5, 'latitude' => -8.0500, 'longitude' => 127.1833],
            ['nama' => 'Langgur', 'lokasi' => 'Kab. Maluku Tenggara', 'frekuensi' => '554 MHz', 'kanal' => 'CH 31', 'daya_kw' => 2.0, 'coverage' => 'Maluku Tenggara', 'status' => 'On-Air', 'kualitas' => 4.7, 'latitude' => -5.6500, 'longitude' => 132.7000],
            ['nama' => 'Elat', 'lokasi' => 'Pulau Kei Besar', 'frekuensi' => '522 MHz', 'kanal' => 'CH 27', 'daya_kw' => 0.5, 'coverage' => 'Kei Besar', 'status' => 'On-Air', 'kualitas' => 4.3, 'latitude' => -5.6500, 'longitude' => 133.0000],
            ['nama' => 'Geser', 'lokasi' => 'Kep. Seram Timur', 'frekuensi' => '530 MHz', 'kanal' => 'CH 28', 'daya_kw' => 0.5, 'coverage' => 'Pulau Geser', 'status' => 'On-Air', 'kualitas' => 4.4, 'latitude' => -3.8833, 'longitude' => 130.9000],
            ['nama' => 'Tehoru', 'lokasi' => 'Maluku Tengah Selatan', 'frekuensi' => '546 MHz', 'kanal' => 'CH 30', 'daya_kw' => 1.0, 'coverage' => 'Teluk Teluti', 'status' => 'On-Air', 'kualitas' => 4.4, 'latitude' => -3.3833, 'longitude' => 129.4000],
            ['nama' => 'Saparua', 'lokasi' => 'Kepulauan Lease', 'frekuensi' => '562 MHz', 'kanal' => 'CH 32', 'daya_kw' => 1.0, 'coverage' => 'Pulau Saparua & Haruku', 'status' => 'On-Air', 'kualitas' => 4.8, 'latitude' => -3.5833, 'longitude' => 128.6667],
        ];

        foreach ($stasiuns as $s) {
            TvriStasiunPemancar::create($s);
        }

        // 3. Alarm & Gangguan Terkini
        TvriGangguan::truncate();
        $gangguans = [
            ['tanggal' => '2025-09-02', 'site' => 'Sanana', 'jenis' => 'Loss of Signal', 'status' => 'Proses', 'keterangan' => 'Gangguan feeder akibat angin kencang (08:15 WIT)'],
            ['tanggal' => '2025-09-02', 'site' => 'Morotai', 'jenis' => 'Power Supply', 'status' => 'Proses', 'keterangan' => 'Listrik PLN padam, genset standby (07:40 WIT)'],
            ['tanggal' => '2025-09-01', 'site' => 'Bula', 'jenis' => 'High VSWR', 'status' => 'Selesai', 'keterangan' => 'Penyetelan matcher antena selesai (14:20 WIT)'],
            ['tanggal' => '2025-09-01', 'site' => 'Namlea', 'jenis' => 'Suhu Tinggi', 'status' => 'Selesai', 'keterangan' => 'Pembersihan heatsink TX & filter AC (11:05 WIT)'],
            ['tanggal' => '2025-08-31', 'site' => 'Dobo', 'jenis' => 'Interferensi', 'status' => 'Selesai', 'keterangan' => 'Filter bandpass terpasang optimal (19:35 WIT)'],
        ];

        foreach ($gangguans as $g) {
            TvriGangguan::create($g);
        }

        // 4. Pemeliharaan Terjadwal
        TvriTransmisiPemeliharaan::truncate();
        $pemeliharaans = [
            ['tanggal' => '2025-09-03', 'site' => 'Masohi', 'kegiatan' => 'Pembersihan Antena', 'status' => 'Terjadwal', 'keterangan' => 'Inspeksi konektor heliax dan pembersihan elemen antena'],
            ['tanggal' => '2025-09-05', 'site' => 'Ambon', 'kegiatan' => 'Kalibrasi Peralatan', 'status' => 'Terjadwal', 'keterangan' => 'Kalibrasi exciter digital & spectrum analyzer'],
            ['tanggal' => '2025-09-08', 'site' => 'Ternate', 'kegiatan' => 'Penggantian Modul TX', 'status' => 'Terjadwal', 'keterangan' => 'Penggantian modul power amplifier PA-02'],
            ['tanggal' => '2025-09-12', 'site' => 'Saumlaki', 'kegiatan' => 'Pemeriksaan Genset', 'status' => 'Terjadwal', 'keterangan' => 'Uji beban rutin genset 50 kVA dan ganti oli mesin'],
        ];

        foreach ($pemeliharaans as $p) {
            TvriTransmisiPemeliharaan::create($p);
        }

        // 5. Log Aktivitas Terkini
        TvriTransmisiLog::truncate();
        $logs = [
            ['waktu' => '2 Sep 10:23', 'site' => 'Ambon', 'aktivitas' => 'Cek status site Ambon', 'user' => 'admin', 'status' => 'Normal'],
            ['waktu' => '2 Sep 10:10', 'site' => 'Morotai', 'aktivitas' => 'Restart transmitter Morotai', 'user' => 'teknisi1', 'status' => 'Proses'],
            ['waktu' => '2 Sep 09:45', 'site' => 'Namlea', 'aktivitas' => 'Update parameter TX', 'user' => 'teknisi2', 'status' => 'Normal'],
            ['waktu' => '2 Sep 08:30', 'site' => 'All Sites', 'aktivitas' => 'Monitoring kualitas siaran', 'user' => 'operator', 'status' => 'Normal'],
            ['waktu' => '2 Sep 07:55', 'site' => 'Sanana', 'aktivitas' => 'Laporan gangguan Sanana', 'user' => 'operator', 'status' => 'Proses'],
        ];

        foreach ($logs as $l) {
            TvriTransmisiLog::create($l);
        }

        // 6. Dokumen & SOP Transmisi
        TvriTransmisiSop::truncate();
        $sops = [
            ['kode' => 'SOP-TX-01', 'judul' => 'SOP Pengoperasian Transmitter DVB-T2', 'kategori' => 'SOP Transmisi', 'versi' => 'v2.1', 'status' => 'Aktif', 'urutan' => 1, 'keterangan' => 'Prosedur baku start-up, shutdown, dan monitoring transmitter siaran digital DVB-T2.'],
            ['kode' => 'SOP-TX-02', 'judul' => 'SOP Penanganan Darurat Loss of Signal & Genset Failover', 'kategori' => 'SOP Transmisi', 'versi' => 'v2.0', 'status' => 'Aktif', 'urutan' => 2, 'keterangan' => 'Prosedur pemindahan otomatis ke genset cadangan dan penanganan link satelit down.'],
            ['kode' => 'SOP-TX-03', 'judul' => 'SOP Pemeliharaan Preventif Bulanan Site Pemancar', 'kategori' => 'SOP Transmisi', 'versi' => 'v1.5', 'status' => 'Aktif', 'urutan' => 3, 'keterangan' => 'Petunjuk teknis pembersihan heatsink, pengecekan VSWR, dan pengetesan grounding petir.'],
            ['kode' => 'DOC-TX-01', 'judul' => 'Format Laporan Harian Operasional Transmisi', 'kategori' => 'Laporan Harian', 'versi' => 'v2025', 'status' => 'Aktif', 'urutan' => 4, 'keterangan' => 'Formulir pencatatan log harian watt meter, suhu ruangan, voltase, dan jam mengudara.'],
            ['kode' => 'DOC-TX-02', 'judul' => 'Format Laporan Bulanan Efisiensi Daya & Kinerja TX', 'kategori' => 'Laporan Bulanan', 'versi' => 'v2025', 'status' => 'Aktif', 'urutan' => 5, 'keterangan' => 'Laporan rekapitulasi uptime siaran on-air bulanan dan konsumsi listrik kWH site.'],
            ['kode' => 'MAN-TX-01', 'judul' => 'Manual Peralatan Transmitter Rohde & Schwarz / GatesAir', 'kategori' => 'Manual Peralatan', 'versi' => 'v3.0', 'status' => 'Aktif', 'urutan' => 6, 'keterangan' => 'Manual book resmi pabrikan unit exciter, power amplifier, dan mask filter.'],
        ];

        foreach ($sops as $sp) {
            TvriTransmisiSop::create($sp);
        }
    }
}
