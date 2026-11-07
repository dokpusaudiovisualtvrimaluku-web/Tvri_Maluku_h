<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TvriDataSeeder extends Seeder
{
    protected function rows(string $table, array $columns, array $data): void
    {
        DB::table($table)->truncate();
        if ($data) {
            DB::table($table)->insert(array_map(fn ($row) => array_combine($columns, $row) + [
                'created_at' => now(), 'updated_at' => now(),
            ], $data));
        }
    }

    public function run(): void
    {
        $now = now();

        // ===== TATA USAHA =====
        $kategoriSurat = [
            'Kementerian' => 32, 'LPP TVRI' => 24, 'Pemerintah Daerah' => 20, 'Instansi Vertikal' => 16,
            'Mitra Kerja' => 14, 'Masyarakat' => 12, 'Lainnya' => 10,
        ];
        $statusSurat = ['Baru', 'Disposisi', 'Proses', 'Selesai'];
        $pengirim = ['Kementerian Kominfo', 'Pemda Maluku', 'LPP TVRI Pusat', 'KPID Maluku', 'Universitas Pattimura', 'TNI/Polri', 'Bank Maluku Malut', 'PLN UIP Maluku', 'Telkom Indonesia', 'Masyarakat'];
        $perihal = ['Undangan Rapat', 'Kerja Sama Siaran', 'Pedoman Sarana Siaran', 'Monitoring Siaran', 'Permohonan Narasumber', 'Klarifikasi Data', 'Laporan Kegiatan', 'Rencana Kunjungan'];
        $sm = [];
        $n = 1;
        foreach ($kategoriSurat as $kat => $jumlah) {
            for ($i = 0; $i < $jumlah; $i++) {
                $sm[] = [
                    date('Y-m-d', strtotime($now->copy()->subDays($n % 90)->format('Y-m-d'))),
                    sprintf('B-%03d/PK/TVRIMALUKU/2025', 100 + $n),
                    $pengirim[$n % count($pengirim)],
                    $perihal[$n % count($perihal)],
                    $kat,
                    $statusSurat[$n % count($statusSurat)],
                ];
                $n++;
            }
        }
        $this->rows('tvri_surat_masuks', ['tanggal', 'nomor', 'pengirim', 'perihal', 'kategori', 'status'], $sm);

        $sk = [];
        for ($i = 1; $i <= 96; $i++) {
            $sk[] = [
                date('Y-m-d', strtotime($now->copy()->subDays($i % 90)->format('Y-m-d'))),
                sprintf('%03d/TVRIMALUKU/UM/2025', 200 + $i),
                $pengirim[$i % count($pengirim)],
                $perihal[$i % count($perihal)],
                $statusSurat[($i + 1) % count($statusSurat)],
            ];
        }
        $this->rows('tvri_surat_keluars', ['tanggal', 'nomor', 'tujuan', 'perihal', 'status'], $sk);

        $this->rows('tvri_layanan_adms', ['jenis', 'jumlah', 'keterangan'], [
            ['Surat Keterangan', 24, 'Penerbitan surat keterangan dinas'],
            ['Permohonan Data', 18, 'Layanan informasi publik & data'],
            ['Layanan Internal', 22, 'Naskah dinas internal'],
            ['Layanan Eksternal', 16, 'Korespondensi kelembagaan'],
            ['Lainnya', 6, 'Layanan administrasi pendukung'],
        ]);

        $agenda = [
            ['2025-09-02', '09:00', 'Rapat Koordinasi Pimpinan', 'Ruang Rapat', 'TU', 'Rapat Internal', 'Selesai'],
            ['2025-09-02', '11:00', 'Pembahasan PKS', 'Ruang Rapat', 'TU', 'Rapat Internal', 'Berlangsung'],
            ['2025-09-03', '13:00', 'Rapat Program', 'Ruang Rapat', 'Program', 'Kegiatan Siaran', 'Akan Datang'],
            ['2025-09-04', '15:00', 'Evaluasi Administrasi', 'Ruang TU', 'TU', 'Rapat Internal', 'Akan Datang'],
            ['2025-09-05', '16:00', 'Briefing Persiapan Event', 'Online', 'TU', 'Rapat Eksternal', 'Akan Datang'],
        ];
        for ($i = 6; $i <= 24; $i++) {
            $agenda[] = [date('Y-m-d', strtotime($now->copy()->subDays($i)->format('Y-m-d'))), '10:00', 'Kegiatan Bidang ke-' . $i, 'Ruang Rapat', 'Kabid', 'Kegiatan Siaran', $i % 2 ? 'Selesai' : 'Akan Datang'];
        }
        $this->rows('tvri_agenda_kegiatans', ['tanggal', 'waktu', 'kegiatan', 'lokasi', 'pic', 'jenis', 'status'], $agenda);

        // ===== UMUM & KEPEGAWAIAN =====
        $this->rows('tvri_asets', ['kode', 'nama', 'kategori', 'kondisi', 'jumlah', 'lokasi', 'tahun', 'nilai'], [
            ['AST-001', 'Kamera Broadcast', 'Peralatan Studio', 'Baik', 24, 'Studio 1', 2023, 2400000000],
            ['AST-002', 'Transmitter 5KW', 'Peralatan Transmisi', 'Baik', 8, 'Site Pemancar', 2022, 8000000000],
            ['AST-003', 'Meja & Kursi Kantor', 'Perlengkapan Kantor', 'Baik', 620, 'Gedung Kantor', 2019, 1240000000],
            ['AST-004', 'Komputer Kerja', 'Peralatan IT', 'Rusak Ringan', 180, 'Seluruh Bidang', 2020, 2700000000],
            ['AST-005', 'Genset 100 KVA', 'Peralatan Listrik', 'Baik', 6, 'Gedung & Site', 2021, 1800000000],
            ['AST-006', 'Peralatan Editing', 'Peralatan Studio', 'Baik', 12, 'Newsroom', 2023, 1500000000],
            ['AST-007', 'AC Kantor', 'Perlengkapan Kantor', 'Rusak Berat', 41, 'Gedung Kantor', 2016, 410000000],
            ['AST-008', 'Antena & Alokasi TX', 'Peralatan Transmisi', 'Baik', 254, '28 Site', 2022, 5100000000],
        ]);

        $this->rows('tvri_kendaraans', ['jenis', 'nama', 'plat_nomor', 'status'], array_merge(
            array_map(fn ($i) => ['Mobil', 'Kendaraan Operasional ' . $i, 'DE ' . (1000 + $i), $i <= 16 ? 'Siap Operasi' : 'Dalam Perbaikan'], range(1, 18)),
            array_map(fn ($i) => ['Motor', 'Motor Pengawasan ' . $i, 'DE ' . (2000 + $i), 'Siap Operasi'], range(1, 6)),
            array_map(fn ($i) => ['Bus', 'Bus OB/Personel ' . $i, 'DE ' . (3000 + $i), 'Siap Operasi'], range(1, 2)),
            array_map(fn ($i) => ['Truk', 'Truk Peralatan ' . $i, 'DE ' . (4000 + $i), $i === 2 ? 'Tidak Operasi' : 'Siap Operasi'], range(1, 2))
        ));

        $this->rows('tvri_sarpras', ['nama', 'kategori', 'jumlah_unit', 'kondisi_baik', 'lokasi'], [
            ['Studio', 'Ruang Produksi', 6, '100%', 'Kantor Pusat'],
            ['Gedung Kantor', 'Bangunan', 4, '90%', 'Ambon'],
            ['Kantor Loka', 'Bangunan', 12, '83%', 'Kabupaten/Kota'],
            ['Rumah Dinas', 'Bangunan', 6, '83%', 'Site Pemancar'],
            ['Tower & Transmisi', 'Infrastruktur', 8, '88%', 'Se-Maluku'],
            ['Fasilitas Lainnya', 'Pendukung', 20, '85%', 'Kantor Pusat'],
        ]);

        $pl = [];
        $statusPL = ['Selesai', 'Diproses', 'Ditunda', 'Dibatalkan'];
        $countPL = [37, 3, 1, 1];
        $idx = 0;
        foreach ($countPL as $si => $cnt) {
            for ($i = 0; $i < $cnt; $i++) {
                $pl[] = [sprintf('PL/%03d/2025', $idx + 1), 'Pemeliharaan Sarpras', 'Bidang ' . ['Umum', 'Teknik', 'Program'][$idx % 3], 'Umum', $statusPL[$si], date('Y-m-d', strtotime($now->copy()->subDays($idx + 1)->format('Y-m-d')))];
                $idx++;
            }
        }
        $this->rows('tvri_permintaan_layanans', ['nomor', 'jenis', 'pemohon', 'unit', 'status', 'tanggal'], $pl);

        $this->rows('tvri_pemeliharaans', ['bidang', 'tanggal', 'objek', 'jenis', 'pic', 'status'], [
            ['Umum', '2025-09-02', 'AC Kantor', 'Preventive', 'Tim Sarpras', 'Selesai'],
            ['Umum', '2025-09-03', 'Genset', 'Preventive', 'Tim Teknik', 'Proses'],
            ['Teknik', '2025-09-05', 'Kendaraan', 'Service Berkala', 'Tim Umum', 'Terjadwal'],
            ['Teknik', '2025-09-08', 'Lift Gedung', 'Inspeksi', 'Vendor', 'Terjadwal'],
            ['Transmisi', '2025-09-10', 'Tower', 'Inspeksi', 'Tim RF', 'Terjadwal'],
            ['Teknik', '2025-09-12', 'Antena Ternate', 'Inspeksi', 'Tim RF', 'Terjadwal'],
        ]);

        $this->rows('tvri_stok_barangs', ['kategori', 'nama', 'jumlah', 'satuan', 'status_stok'], [
            ['ATK', 'Kertas A4', 120, 'Rim', 'Aman'],
            ['Kebersihan', 'Sabun & Pembersih', 45, 'Pcs', 'Aman'],
            ['Sparepart', 'Kabel coaxial', 68, 'Roll', 'Menipis'],
            ['Lainnya', 'Tinta Printer', 32, 'Botol', 'Aman'],
            ['Cadangan', 'Head TX', 2, 'Set', 'Menipis'],
            ['Lainnya', 'Baterai Genset', 4, 'Unit', 'Habis'],
        ]);

        // ===== TRANSMISI & TEKNIK =====
        $this->rows('tvri_stasiun_pemancars', ['nama', 'lokasi', 'frekuensi', 'kanal', 'daya_kw', 'coverage', 'status', 'kualitas', 'latitude', 'longitude'], [
            ['Ambon', 'Kota Ambon', 'CH 28', '530 MHz', 5, 'Kota + Kab', 'On-Air', 4.8, -3.6954, 128.1814],
            ['Ternate', 'Kota Ternate', 'CH 32', '562 MHz', 5, 'Kota + Kab', 'On-Air', 4.7, 0.7833, 127.3667],
            ['Masohi', 'Kab. Maluku Tengah', 'CH 30', '546 MHz', 2, 'Kab. Maleng', 'On-Air', 4.5, -3.3072, 128.1748],
            ['Namlea', 'Kab. Buru', 'CH 26', '514 MHz', 1, 'Kab. Buru', 'On-Air', 4.4, -2.9778, 127.6958],
            ['Saumlaki', 'Kab. Tanimbar', 'CH 29', '538 MHz', 2, 'Kep. Tanimbar', 'On-Air', 4.6, -7.9833, 131.3167],
            ['Dobo', 'Kep. Aru', 'CH 27', '522 MHz', 1, 'Kep. Aru', 'On-Air', 4.3, -5.75, 134.2167],
            ['Sanana', 'Kep. Sula', 'CH 25', '506 MHz', 1, 'Kep. Sula', 'Maintenance', 3.9, -2.5, 125.5],
            ['Morotai', 'Kab. Pulau Morotai', 'CH 33', '570 MHz', 1, 'Kab. P. Morotai', 'Gangguan', 2.8, 2.4333, 128.3833],
            ['Bula', 'Seram Bagian Timur', 'CH 31', '554 MHz', 1, 'SBT', 'On-Air', 4.5, -3.1667, 130.4333],
        ]);

        $this->rows('tvri_gangguans', ['tanggal', 'site', 'jenis', 'status', 'keterangan'], [
            ['2025-09-02', 'Sanana', 'Loss of Signal', 'Proses', 'Gangguan feeder akibat angin kencang'],
            ['2025-09-02', 'Morotai', 'Power Supply', 'Proses', 'Listrik PLN padam, genset standby'],
            ['2025-09-01', 'Bula', 'High VSWR', 'Selesai', 'Pengecekan antena selesai'],
            ['2025-09-01', 'Namlea', 'Suhu Tinggi', 'Selesai', 'Pembersihan heatsink TX'],
            ['2025-08-31', 'Dobo', 'Interferensi', 'Selesai', 'Filter frekuensi dipasang'],
        ]);

        $this->rows('tvri_insiden_tekniks', ['tanggal', 'lokasi', 'deskripsi', 'status'], [
            ['2025-09-02', 'Studio 4', 'Audio tidak terdeteksi', 'Dalam Perbaikan'],
            ['2025-09-01', 'Transmitter Dobo', 'Sinyal drop (cuaca)', 'Selesai'],
            ['2025-08-31', 'Streaming', 'Latensi tinggi', 'Selesai'],
            ['2025-08-30', 'Genset', 'Alarm suhu tinggi', 'Selesai'],
            ['2025-08-28', 'Jaringan Kantor', 'Koneksi lambat', 'Selesai'],
        ]);

        $this->rows('tvri_peralatans', ['nama', 'kategori', 'jumlah', 'jumlah_baik', 'lokasi'], [
            ['Kamera', 'Produksi', 24, 21, 'Studio & OB'],
            ['Perangkat Editing', 'Pasca Produksi', 12, 11, 'Newsroom'],
            ['Server & Storage', 'IT', 8, 7, 'Data Center'],
            ['Perangkat Transmisi', 'Transmisi', 16, 15, 'Site Pemancar'],
            ['Genset', 'Pendukung', 6, 5, 'Gedung & Site'],
            ['Software Lisensi', 'IT', 12, 12, 'Seluruh Bidang'],
        ]);

        // ===== KEUANGAN =====
        $this->rows('tvri_anggarans', ['kode', 'uraian', 'pagu', 'realisasi', 'tahun'], [
            ['51', 'Belanja Pegawai', 19500000000, 16006500000, 2025],
            ['52', 'Belanja Barang', 17000000000, 12114000000, 2025],
            ['53', 'Belanja Modal', 8500000000, 5780000000, 2025],
            ['54', 'Belanja Lainnya', 750000000, 456000000, 2025],
        ]);

        $this->rows('tvri_pnbps', ['jenis', 'target', 'realisasi', 'tahun'], [
            ['Iklan TV & Radio', 1500000000, 1300000000, 2025],
            ['Sewa Aset & Fasilitas', 800000000, 650000000, 2025],
            ['Sponsorship Program', 550000000, 460000000, 2025],
            ['Event & Aktivasi', 350000000, 300000000, 2025],
            ['Lainnya', 200000000, 140000000, 2025],
        ]);

        $this->rows('tvri_pendapatans', ['sumber', 'bulan', 'tahun', 'target', 'realisasi'], collect(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'])->map(fn ($b, $i) => [
            'Iklan TV & Radio', $b, 2025, 125000000, 100000000 + $i * 12000000,
        ])->all());

        $this->rows('tvri_piutangs', ['klien', 'nilai', 'jatuh_tempo', 'status'], [
            ['Pemda Maluku', 150000000, '2025-11-25', 'Belum Bayar'],
            ['Telkom Indonesia', 100000000, '2025-11-30', 'Proses'],
            ['Bank Maluku Malut', 75000000, '2025-12-05', 'Belum Bayar'],
            ['Sponsorship Event', 100000000, '2025-12-10', 'Proses'],
        ]);

        // ===== PENGEMBANGAN USAHA =====
        $this->rows('tvri_mitras', ['nama', 'jenis_kerja_sama', 'nilai_kontrak', 'status', 'lokasi'], [
            ['Bank Maluku Malut', 'Iklan & Sponsorship', 600000000, 'Aktif', 'Ambon'],
            ['Telkom Indonesia', 'Program & Event', 450000000, 'Aktif', 'Ambon'],
            ['Pemda Maluku', 'Kampanye Publik', 400000000, 'Aktif', 'Ambon'],
            ['PLN UIP Maluku', 'Iklan Layanan', 300000000, 'Proses', 'Ambon'],
            ['Pelindo Regional', 'Event & Aktivasi', 250000000, 'Aktif', 'Ambon'],
        ]);

        $this->rows('tvri_kegiatan_usahas', ['tanggal', 'kegiatan', 'jenis', 'estimasi_nilai', 'status'], [
            ['2025-11-20', 'Maluku Expo 2025', 'Event', 150000000, 'Persiapan'],
            ['2025-11-25', 'Talkshow UMKM', 'Sponsorship', 100000000, 'Kontrak'],
            ['2025-11-28', 'Festival Budaya', 'Event', 200000000, 'Persiapan'],
            ['2025-12-02', 'Program Khusus Nataru', 'Sponsorship', 250000000, 'Negosiasi'],
            ['2025-12-10', 'Kampanye Layanan Publik', 'Iklan', 150000000, 'Kontrak'],
        ]);

        // ===== PROGRAM, BERITA, KONTEN, PROMO =====
        $prog = [
            // Berita (12)
            ['Maluku Hari Ini', 'Berita', 'Tayang', '2025-09-02', 'Tayang', 'Tim Berita', 420000, 2.9],
            ['Maluku Pagi Ini', 'Berita', 'Tayang', '2025-09-02', 'Tayang', 'Tim Berita', 280000, 2.8],
            ['Maluku Siang Ini', 'Berita', 'Tayang', '2025-09-02', 'Tayang', 'Tim Berita', 250000, 2.7],
            ['Maluku Malam Ini', 'Berita', 'Tayang', '2025-09-02', 'Tayang', 'Tim Berita', 350000, 2.8],
            ['Dunia Dalam Berita Lokal', 'Berita', 'Tayang', '2025-09-03', 'Tayang', 'Tim Berita', 210000, 2.6],
            ['Warta Ambon Manise', 'Berita', 'Tayang', '2025-09-03', 'Tayang', 'Tim Berita', 190000, 2.7],
            ['Berita Kepulauan', 'Berita', 'Tayang', '2025-09-04', 'Tayang', 'Tim Berita', 180000, 2.6],
            ['Kabar Siwalima', 'Berita', 'Tayang', '2025-09-04', 'Tayang', 'Tim Berita', 175000, 2.5],
            ['Kilas Berita 60 Detik', 'Berita', 'Tayang', '2025-09-05', 'Tayang', 'Tim Berita', 160000, 2.6],
            ['Berita Daerah Pilihan', 'Berita', 'Tayang', '2025-09-05', 'Tayang', 'Tim Berita', 155000, 2.5],
            ['Suara Parlemen Maluku', 'Berita', 'Tayang', '2025-09-06', 'Tayang', 'Tim Berita', 140000, 2.6],
            ['Berita Akhir Pekan', 'Berita', 'Tayang', '2025-09-07', 'Tayang', 'Tim Berita', 185000, 2.7],

            // Current Affairs (8)
            ['Maluku Menggugat', 'Current Affairs', 'Tayang', '2025-09-03', 'Tayang', 'Tim Program', 220000, 2.8],
            ['Catatan Demokrasi Maluku', 'Current Affairs', 'Tayang', '2025-09-04', 'Tayang', 'Tim Program', 190000, 2.7],
            ['Fokus Siwalima', 'Current Affairs', 'Tayang', '2025-09-05', 'Tayang', 'Tim Program', 210000, 2.7],
            ['Bedah Isu Maluku', 'Current Affairs', 'Tayang', '2025-09-06', 'Tayang', 'Tim Program', 180000, 2.6],
            ['Ruang Publik Maluku', 'Current Affairs', 'Tayang', '2025-09-07', 'Tayang', 'Tim Program', 175000, 2.6],
            ['Lensa Maritim Nusantara', 'Current Affairs', 'Tayang', '2025-09-08', 'Tayang', 'Tim Program', 195000, 2.7],
            ['Jejak Pembangunan Daerah', 'Current Affairs', 'Tayang', '2025-09-09', 'Tayang', 'Tim Program', 165000, 2.5],
            ['Suara Dari Pesisir', 'Current Affairs', 'Tayang', '2025-09-10', 'Tayang', 'Tim Program', 170000, 2.6],

            // Budaya (7)
            ['Jejak Budaya', 'Budaya', 'Tayang', '2025-09-02', 'Tayang', 'Tim Program', 310000, 2.8],
            ['Senandung Negeri Rempah', 'Budaya', 'Tayang', '2025-09-03', 'Tayang', 'Tim Program', 200000, 2.7],
            ['Pesona Adat Maluku', 'Budaya', 'Tayang', '2025-09-05', 'Tayang', 'Tim Program', 195000, 2.7],
            ['Irama Tradisi Tifa', 'Budaya', 'Tayang', '2025-09-06', 'Tayang', 'Tim Program', 180000, 2.6],
            ['Warisan Leluhur Maluku', 'Budaya', 'Tayang', '2025-09-08', 'Tayang', 'Tim Program', 220000, 2.8],
            ['Kidung Raja-Raja', 'Budaya', 'Tayang', '2025-09-09', 'Tayang', 'Tim Program', 175000, 2.6],
            ['Geliat Sanggar Seni', 'Budaya', 'Produksi', '2025-09-15', 'Terlambat', 'Tim Program', 150000, 2.4],

            // Pendidikan (6)
            ['Maluku Belajar', 'Pendidikan', 'Pra Produksi', '2025-09-10', 'On Schedule', 'Tim Program', 230000, 2.6],
            ['Cerdas Bersama TVRI', 'Pendidikan', 'Tayang', '2025-09-04', 'Tayang', 'Tim Program', 180000, 2.7],
            ['Sekolah Inovasi Pesisir', 'Pendidikan', 'Tayang', '2025-09-06', 'Tayang', 'Tim Program', 170000, 2.6],
            ['Anak Hebat Maluku', 'Pendidikan', 'Tayang', '2025-09-07', 'Tayang', 'Tim Program', 190000, 2.7],
            ['Jelajah Sains Maritim', 'Pendidikan', 'Tayang', '2025-09-09', 'Tayang', 'Tim Program', 160000, 2.5],
            ['Ruang Guru Inspiratif', 'Pendidikan', 'Pasca Produksi', '2025-09-18', 'Terlambat', 'Tim Program', 140000, 2.4],

            // Pariwisata (6)
            ['Pesona Maluku', 'Pariwisata', 'Produksi', '2025-09-05', 'Dalam Produksi', 'Tim Program', 360000, 2.8],
            ['Eksotika Banda Neira', 'Pariwisata', 'Tayang', '2025-09-03', 'Tayang', 'Tim Program', 290000, 2.9],
            ['Destinasi Bahari Raja-Raja', 'Pariwisata', 'Tayang', '2025-09-05', 'Tayang', 'Tim Program', 240000, 2.8],
            ['Surga Tersembunyi Seram', 'Pariwisata', 'Tayang', '2025-09-08', 'Tayang', 'Tim Program', 220000, 2.7],
            ['Kuliner Khas Siwalima', 'Pariwisata', 'Tayang', '2025-09-10', 'Tayang', 'Tim Program', 250000, 2.8],
            ['Petualangan Pulau Kei', 'Pariwisata', 'Tayang', '2025-09-12', 'Tayang', 'Tim Program', 270000, 2.9],

            // Talkshow (5)
            ['Dialog Maluku', 'Talkshow', 'Pasca Produksi', '2025-09-07', 'Editing', 'Tim Program', 320000, 2.7],
            ['Maluku Inspirasi', 'Talkshow', 'Tayang', '2025-09-04', 'Tayang', 'Tim Program', 280000, 2.7],
            ['Bincang Sore Siwalima', 'Talkshow', 'Tayang', '2025-09-06', 'Tayang', 'Tim Program', 210000, 2.6],
            ['Tokoh Kita di Timur', 'Talkshow', 'Tayang', '2025-09-08', 'Tayang', 'Tim Program', 230000, 2.7],
            ['Aspirasi Pemuda Maluku', 'Talkshow', 'QC', '2025-09-20', 'Terlambat', 'Tim Program', 160000, 2.5],

            // Religi (4)
            ['Mimbar Agama Maluku', 'Religi', 'Tayang', '2025-09-05', 'Tayang', 'Tim Program', 240000, 2.8],
            ['Cahaya Iman Nusantara', 'Religi', 'Tayang', '2025-09-07', 'Tayang', 'Tim Program', 220000, 2.7],
            ['Kasih Persaudaraan Pela Gandong', 'Religi', 'Tayang', '2025-09-08', 'Tayang', 'Tim Program', 260000, 2.9],
            ['Renungan Qolbu Pagi', 'Religi', 'Tayang', '2025-09-09', 'Tayang', 'Tim Program', 200000, 2.7],

            // Lainnya (3 => Total 51)
            ['Ruang Kreatif Komunitas', 'Lainnya', 'Tayang', '2025-09-11', 'Tayang', 'Tim Program', 180000, 2.6],
            ['Dokumenter Khusus Maluku', 'Lainnya', 'Tayang', '2025-09-14', 'Tayang', 'Tim Program', 210000, 2.7],
            ['Special Program HUT Maluku', 'Lainnya', 'Perencanaan', '2025-09-15', 'On Schedule', 'Tim Program', 450000, 3.0],
        ];
        $this->rows('tvri_programs', ['judul', 'kategori', 'tahap', 'target_tayang', 'status', 'pic', 'audience', 'rating'], $prog);

        $berita = [];
        $statusB = ['Selesai', 'Selesai', 'Selesai', 'Dalam Proses', 'Terkendala', 'Batal'];
        for ($i = 1; $i <= 120; $i++) {
            $berita[] = ['Liputan ' . $perihal[$i % count($perihal)] . ' #' . $i, ['Pemerintahan', 'Sosial Masyarakat', 'Ekonomi & UMKM', 'Pendidikan', 'Kesehatan', 'Pariwisata'][$i % 6], ['Berita Reguler', 'Current Affairs', 'Breaking News'][$i % 3], ['Ambon', 'Maluku Tengah', 'SBB', 'SBT', 'Buru'][$i % 5], $statusB[$i % 6], 'Tim ' . (1 + $i % 5), date('Y-m-d', strtotime($now->copy()->subDays($i % 30)->format('Y-m-d'))), $i % 6 === 0 ? date('Y-m-d', strtotime($now->copy()->subDays($i % 30)->format('Y-m-d'))) : null, $i % 6 === 0 ? 5000 + $i * 100 : 0];
        }
        $this->rows('tvri_beritas', ['judul', 'kategori', 'jenis', 'lokasi', 'status', 'reporter', 'tanggal_rencana', 'tanggal_tayang', 'views'], $berita);

        $konten = [];
        $platform = ['YouTube', 'Instagram', 'TikTok', 'Website'];
        for ($i = 1; $i <= 256; $i++) {
            $konten[] = ['Konten ' . $perihal[$i % count($perihal)] . ' #' . $i, $platform[$i % 4], ['Berita', 'Budaya', 'Pariwisata', 'Pendidikan', 'UMKM', 'Talkshow'][$i % 6], 10000 + $i * 500, 5000 + $i * 300, 800 + $i * 40, 120 + $i * 5, date('Y-m-d', strtotime($now->copy()->subDays($i % 60)->format('Y-m-d'))), 'Published'];
        }
        $this->rows('tvri_kontens', ['judul', 'platform', 'kategori', 'views', 'reach', 'engagement', 'watch_time', 'tanggal', 'status'], $konten);

        $materi = [];
        $statusM = ['Selesai', 'Dalam Produksi', 'Review', 'Revisi', 'Ditunda'];
        $cntM = [28, 15, 8, 3, 2];
        $idxM = 0;
        foreach ($cntM as $si => $cnt) {
            for ($i = 0; $i < $cnt; $i++) {
                $materi[] = ['Materi Promo ' . ($idxM + 1), ['Video Promo', 'Bumper Program', 'Identitas Stasiun'][$idxM % 3], [15, 30, 60][$idxM % 3], $statusM[$si]];
                $idxM++;
            }
        }
        $this->rows('tvri_materi_promos', ['nama', 'jenis', 'durasi_detik', 'status'], $materi);

        $kamp = [];
        for ($i = 1; $i <= 24; $i++) {
            $kamp[] = ['Kampanye ' . ['Maluku Bisa!', 'Pesona Maluku', 'Dialog Maluku', 'UMKM Maluku', 'Ayo Nonton TVRI'][$i % 5] . ' ' . $i, ['TV On-Air', 'YouTube', 'Instagram', 'TikTok'][$i % 4], 100, 76 + ($i * 2) % 44, 40000 + $i * 2000, 25000 + $i * 1500, $i % 3 ? 'Aktif' : 'Selesai'];
        }
        $this->rows('tvri_kampanyes', ['nama', 'media', 'target', 'realisasi', 'reach', 'engagement', 'status'], $kamp);
    }
}
