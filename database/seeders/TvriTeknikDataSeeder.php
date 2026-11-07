<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TvriTeknikRingkasan;
use App\Models\TvriTeknikJadwal;
use App\Models\TvriTeknikMaintenance;
use App\Models\TvriTeknikInsiden;
use App\Models\TvriTeknikTransmitter;
use App\Models\TvriTeknikSop;

class TvriTeknikDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ringkasan Parameter Dashboard Teknik
        TvriTeknikRingkasan::updateOrCreate(
            ['id' => 1],
            [
                'judul' => 'DASHBOARD TEKNIK TVRI MALUKU',
                'sub_judul' => 'TVRI MALUKU Media Pemersatu Bangsa di Bumi Raja-Raja',
                'slogan' => 'SIARAN ANDAL • TEKNOLOGI TERDEPAN • LAYANAN TANPA HENTI',
                'kutipan' => 'Infrastruktur Kuat, Siaran Berkualitas, untuk Maluku dan Indonesia',
                'tagline' => 'Maluku Bisa Lebih Jauh!',
                'waktu_status' => 'Selasa, 2 September 2025 | 10:24 WIT | Online',
                'periode' => 'September 2025',

                // KPI
                'on_air_pct' => 100,
                'transmitter_on' => 8,
                'transmitter_total' => 8,
                'studio_ready' => 4,
                'studio_total' => 4,
                'peralatan_kritis_pct' => 96,
                'insiden_hari_ini' => 1,
                'insiden_diff' => -67,
                'maintenance_pct' => 92,
                'maintenance_target' => 90,
                'efisiensi_energi_pct' => 87,
                'efisiensi_diff' => -5,

                // Status Siaran Live
                'siaran_program' => 'MALUKU HARI INI',
                'siaran_tagline' => 'Informasi untuk Semua',
                'running_text' => 'TVRI 10:24 WIT MALUKU: POTENSI MARITIM UNTUK MASA DEPAN >>',
                'siaran_sumber' => 'Studio 1',
                'siaran_resolusi' => '1080i HD',
                'siaran_audio' => 'Stereo',
                'siaran_bitrate' => '8 Mbps',
                'siaran_durasi' => '02:14:25',
                'siaran_status' => 'Normal',

                // Technical Readiness Index
                'readiness_overall' => 94,
                'readiness_predikat' => 'Sangat Baik',
                'readiness_items_json' => [
                    ['aspek' => 'Transmisi', 'skor' => 96, 'color' => 'bg-cyan-500'],
                    ['aspek' => 'Studio', 'skor' => 100, 'color' => 'bg-emerald-500'],
                    ['aspek' => 'IT & Network', 'skor' => 92, 'color' => 'bg-blue-500'],
                    ['aspek' => 'Power & Genset', 'skor' => 90, 'color' => 'bg-amber-500'],
                    ['aspek' => 'Peralatan Produksi', 'skor' => 93, 'color' => 'bg-indigo-500'],
                    ['aspek' => 'Infrastruktur', 'skor' => 95, 'color' => 'bg-teal-500'],
                ],

                // Peta Jangkauan & Transmisi
                'tx_locations_text' => 'Ternate, Sanana, Namlea, Ambon, Masohi, Bula, Saumlaki',
                'coverage_area_pct' => 92,
                'estimasi_pemirsa' => '1,2 Juta Estimasi Pemirsa',

                // Monitoring Sistem
                'video_pct' => 100,
                'audio_pct' => 100,
                'playout_pct' => 100,
                'streaming_pct' => 99,
                'uplink_pct' => 100,
                'network_pct' => 96,
                'sinyal_trend' => 'Sinyal Stabil',

                // Status Studio
                'status_studio_json' => [
                    ['nama' => 'Studio 1', 'status' => 'ON AIR', 'badge' => 'bg-rose-500 text-white animate-pulse', 'keterangan' => 'Siaran Langsung Maluku Hari Ini'],
                    ['nama' => 'Studio 2', 'status' => 'Siap Produksi (Siap)', 'badge' => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20', 'keterangan' => 'Standby untuk Dialog Maluku'],
                    ['nama' => 'Studio 3', 'status' => 'Siap Produksi (Siap)', 'badge' => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20', 'keterangan' => 'Standby untuk Program Budaya'],
                    ['nama' => 'Studio 4', 'status' => 'Maintenance (Dalam Perbaikan)', 'badge' => 'bg-amber-500/10 text-amber-700 border border-amber-500/20', 'keterangan' => 'Inspeksi & penanganan audio tidak terdeteksi'],
                ],

                // Inventaris Utama
                'inventaris_json' => [
                    ['nama' => 'Kamera', 'kategori' => 'Produksi', 'total' => 24, 'baik' => 21, 'status_label' => 'Baik: 21', 'persen' => 88],
                    ['nama' => 'Perangkat Editing', 'kategori' => 'Pasca Produksi', 'total' => 12, 'baik' => 11, 'status_label' => 'Baik: 11', 'persen' => 92],
                    ['nama' => 'Server & Storage', 'kategori' => 'IT Data Center', 'total' => 8, 'baik' => 7, 'status_label' => 'Baik: 7', 'persen' => 88],
                    ['nama' => 'Perangkat Transmisi', 'kategori' => 'Transmisi', 'total' => 16, 'baik' => 15, 'status_label' => 'Baik: 15', 'persen' => 94],
                    ['nama' => 'Genset', 'kategori' => 'Power & Utility', 'total' => 6, 'baik' => 5, 'status_label' => 'Baik: 5', 'persen' => 83],
                    ['nama' => 'Software Lisensi', 'kategori' => 'IT & Broadcast', 'total' => 12, 'baik' => 12, 'status_label' => 'Aktif: 12', 'persen' => 100],
                ],

                // Footer Points
                'footer_points_json' => [
                    'Siap Siar, Siap Melayani',
                    'Teknologi untuk Negeri',
                    'Andal, Profesional, Kolaboratif',
                    'Siaran Berkualitas, Masyarakat Cerdas',
                    'Maluku Bersiaran Untuk Indonesia'
                ],
                'quote_penutup' => 'Maluku Bisa! Indonesia Maju!'
            ]
        );

        // 2. Jadwal Produksi & Teknik Hari Ini
        $jadwals = [
            ['waktu' => '06:00', 'program' => 'Maluku Pagi Ini', 'lokasi' => 'Studio 1', 'kebutuhan' => 'Kamera, Audio, CG', 'status' => 'Selesai', 'badge' => 'bg-slate-100 text-slate-700', 'urutan' => 1],
            ['waktu' => '09:00', 'program' => 'Dialog Maluku', 'lokasi' => 'Studio 2', 'kebutuhan' => 'Kamera, Audio, VT', 'status' => 'Sedang Berjalan', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200 animate-pulse', 'urutan' => 2],
            ['waktu' => '13:00', 'program' => 'Liputan Khusus', 'lokasi' => 'OB Van', 'kebutuhan' => 'Kamera, LiveU', 'status' => 'Siap', 'badge' => 'bg-blue-50 text-blue-700 border border-blue-200', 'urutan' => 3],
            ['waktu' => '16:00', 'program' => 'Maluku Memilih', 'lokasi' => 'Studio 1', 'kebutuhan' => 'Multi Camera', 'status' => 'Terjadwal', 'badge' => 'bg-amber-50 text-amber-700 border border-amber-200', 'urutan' => 4],
            ['waktu' => '19:00', 'program' => 'Maluku Hari Ini', 'lokasi' => 'Studio 1', 'kebutuhan' => 'Kamera, CG, Playout', 'status' => 'Terjadwal', 'badge' => 'bg-amber-50 text-amber-700 border border-amber-200', 'urutan' => 5],
            ['waktu' => '21:00', 'program' => 'Program Budaya', 'lokasi' => 'Studio 3', 'kebutuhan' => 'Kamera, Audio', 'status' => 'Terjadwal', 'badge' => 'bg-amber-50 text-amber-700 border border-amber-200', 'urutan' => 6],
        ];

        TvriTeknikJadwal::truncate();
        foreach ($jadwals as $j) {
            TvriTeknikJadwal::create($j);
        }

        // 3. Maintenance & Servis Peralatan
        $maintenances = [
            ['tanggal' => '20-10-2026', 'peralatan' => 'Transimi', 'jenis' => '1200000', 'pic' => 'usma', 'status' => 'Terjadwal'],
            ['tanggal' => '2025-09-02', 'peralatan' => 'Transmitter Ambon', 'jenis' => 'Preventive', 'pic' => 'Tim RF', 'status' => 'Selesai'],
            ['tanggal' => '2025-09-03', 'peralatan' => 'Kamera Studio 1', 'jenis' => 'Cleaning', 'pic' => 'Tim Studio', 'status' => 'On Progress'],
            ['tanggal' => '2025-09-05', 'peralatan' => 'Genset', 'jenis' => 'Servis Berkala', 'pic' => 'Tim Power', 'status' => 'Terjadwal'],
            ['tanggal' => '2025-09-08', 'peralatan' => 'UPS', 'jenis' => 'Penggantian Baterai', 'pic' => 'Tim Elektrikal', 'status' => 'Terjadwal'],
            ['tanggal' => '2025-09-10', 'peralatan' => 'AC Studio', 'jenis' => 'Perawatan', 'pic' => 'Tim Sarpras', 'status' => 'Terjadwal'],
            ['tanggal' => '2025-09-12', 'peralatan' => 'Antena Ternate', 'jenis' => 'Inspeksi', 'pic' => 'Tim RF', 'status' => 'Terjadwal'],
        ];

        TvriTeknikMaintenance::truncate();
        foreach ($maintenances as $m) {
            TvriTeknikMaintenance::create($m);
        }

        // 4. Insiden & Gangguan
        $insidens = [
            ['waktu' => '2 Sep 08:15', 'lokasi' => 'Studio 4', 'deskripsi' => 'Studio 4 Audio - Inspeksi & penanganan audio tidak terdeteksi', 'status' => 'Dalam Perbaikan', 'badge' => 'bg-amber-100 text-amber-800 border-amber-200'],
            ['waktu' => '1 Sep 14:20', 'lokasi' => 'Transmitter Dobo', 'deskripsi' => 'Sinyal drop (cuaca)', 'status' => 'Selesai', 'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
            ['waktu' => '31 Aug 10:05', 'lokasi' => 'Streaming', 'deskripsi' => 'Latensi tinggi', 'status' => 'Selesai', 'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
            ['waktu' => '30 Aug 18:40', 'lokasi' => 'Genset', 'deskripsi' => 'Alarm suhu tinggi', 'status' => 'Selesai', 'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
            ['waktu' => '28 Aug 09:10', 'lokasi' => 'Jaringan Kantor', 'deskripsi' => 'Koneksi lambat', 'status' => 'Selesai', 'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
        ];

        TvriTeknikInsiden::truncate();
        foreach ($insidens as $ins) {
            TvriTeknikInsiden::create($ins);
        }

        // 5. Status Transmitter
        $transmitters = [
            ['lokasi' => 'Ambon', 'frekuensi' => '528 MHz', 'daya' => '5 kW', 'status' => 'Aktif', 'sinyal_warna' => 'Hijau'],
            ['lokasi' => 'Masohi', 'frekuensi' => '530 MHz', 'daya' => '2 kW', 'status' => 'Aktif', 'sinyal_warna' => 'Hijau'],
            ['lokasi' => 'Namlea', 'frekuensi' => '522 MHz', 'daya' => '1 kW', 'status' => 'Aktif', 'sinyal_warna' => 'Hijau'],
            ['lokasi' => 'Saumlaki', 'frekuensi' => '536 MHz', 'daya' => '1 kW', 'status' => 'Aktif', 'sinyal_warna' => 'Hijau'],
            ['lokasi' => 'Dobo', 'frekuensi' => '538 MHz', 'daya' => '1 kW', 'status' => 'Siaga', 'sinyal_warna' => 'Kuning'],
            ['lokasi' => 'Bula', 'frekuensi' => '524 MHz', 'daya' => '1 kW', 'status' => 'Aktif', 'sinyal_warna' => 'Hijau'],
            ['lokasi' => 'Sanana', 'frekuensi' => '526 MHz', 'daya' => '1 kW', 'status' => 'Aktif', 'sinyal_warna' => 'Hijau'],
            ['lokasi' => 'Ternate', 'frekuensi' => '532 MHz', 'daya' => '2 kW', 'status' => 'Aktif', 'sinyal_warna' => 'Hijau'],
        ];

        TvriTeknikTransmitter::truncate();
        foreach ($transmitters as $tx) {
            TvriTeknikTransmitter::create($tx);
        }

        // 6. Dokumen & SOP Teknik
        $sops = [
            [
                'kode' => 'SOP-TEK-01',
                'judul' => 'SOP Operasional Siaran Master Control Room (MCR)',
                'kategori' => 'Operasional Siaran',
                'versi' => 'v2.0',
                'tanggal_efektif' => '2025-01-10',
                'status' => 'Aktif',
                'keterangan' => 'Prosedur switching playout siaran, monitoring audio/video line feed, switching line satelit/uplink, dan tata laksana darurat off-air.',
                'urutan' => 1
            ],
            [
                'kode' => 'SOP-TEK-02',
                'judul' => 'SOP Pemeliharaan & Kalibrasi Peralatan Studio Produksi',
                'kategori' => 'Studio & Fasilitas',
                'versi' => 'v1.5',
                'tanggal_efektif' => '2025-01-25',
                'status' => 'Aktif',
                'keterangan' => 'Uji rutin color balance kamera studio, audio mixer digital, lighting console, switcher vision mixer, dan mikrofon wireless studio.',
                'urutan' => 2
            ],
            [
                'kode' => 'SOP-TEK-03',
                'judul' => 'SOP Operasional & Monitoring Pemancar Transmisi',
                'kategori' => 'Transmisi & RF',
                'versi' => 'v2.1',
                'tanggal_efektif' => '2025-02-01',
                'status' => 'Aktif',
                'keterangan' => 'Pencatatan VSWR transmitter, output power RF, suhu modulasi solid-state, dan protokol failover otomatis ke backup transmitter.',
                'urutan' => 3
            ],
            [
                'kode' => 'SOP-TEK-04',
                'judul' => 'SOP Maintenance Preventif Berkala Utility & Kelistrikan (Genset/UPS)',
                'kategori' => 'Power & Utility',
                'versi' => 'v1.8',
                'tanggal_efektif' => '2025-02-15',
                'status' => 'Aktif',
                'keterangan' => 'Pemanasan genset mingguan, pengecekan baterai bank UPS MCR, penggantian oli berkala, serta pengecekan panel ATS otomatis.',
                'urutan' => 4
            ],
            [
                'kode' => 'SOP-TEK-05',
                'judul' => 'Manual Peralatan & Prosedur Tanggap Darurat Insiden Siaran',
                'kategori' => 'Emergency & Recovery',
                'versi' => 'v2.0',
                'tanggal_efektif' => '2025-03-01',
                'status' => 'Aktif',
                'keterangan' => 'Tata cara penanganan cepat saat video freeze/audio lost, formulir pelaporan gangguan teknik, eskalasi ke vendor dan pimpinan stasiun.',
                'urutan' => 5
            ]
        ];

        TvriTeknikSop::truncate();
        foreach ($sops as $sop) {
            TvriTeknikSop::create($sop);
        }
    }
}
