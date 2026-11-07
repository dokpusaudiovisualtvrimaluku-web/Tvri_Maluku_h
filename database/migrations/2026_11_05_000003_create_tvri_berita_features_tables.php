<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Produksi Paket Berita
        if (!Schema::hasTable('tvri_berita_produksis')) {
            Schema::create('tvri_berita_produksis', function (Blueprint $t) {
                $t->id();
                $t->string('kode', 30)->unique();
                $t->string('judul');
                $t->string('program', 100)->default('Maluku Hari Ini');
                $t->string('kategori', 100)->default('Pemerintahan');
                $t->string('reporter')->nullable();
                $t->string('kamerawan')->nullable();
                $t->string('editor')->nullable();
                $t->string('durasi', 20)->default('02:30');
                $t->date('tanggal_produksi')->nullable();
                $t->date('target_tayang')->nullable();
                $t->string('status', 50)->default('Editing'); // Editing, Review EIC, Siap Tayang, On Air, Selesai
                $t->string('prioritas', 30)->default('Biasa'); // Biasa, Penting, Mendesak
                $t->text('catatan')->nullable();
                $t->string('video_url')->nullable();
                $t->string('naskah_file')->nullable();
                $t->timestamps();
            });

            $produksis = [
                [
                    'kode' => 'PKT-202611-01',
                    'judul' => 'Paket Khusus Upacara & Tabur Bunga Hari Pahlawan di Teluk Ambon',
                    'program' => 'Maluku Hari Ini Sore',
                    'kategori' => 'Pemerintahan',
                    'reporter' => 'Monica Sipahelut',
                    'kamerawan' => 'Kurniawan Suwarno',
                    'editor' => 'Hendrik Latuputty',
                    'durasi' => '03:45',
                    'tanggal_produksi' => '2026-11-10',
                    'target_tayang' => '2026-11-10',
                    'status' => 'Siap Tayang',
                    'prioritas' => 'Mendesak',
                    'catatan' => 'Gabungkan visual drone Teluk Ambon dan wawancara Forkopimda.',
                    'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                    'naskah_file' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'PKT-202611-02',
                    'judul' => 'Investigasi Abrasi Pantai Pesisir Hitu dan Ketahanan Nelayan Tradisional',
                    'program' => 'Panorama Maluku',
                    'kategori' => 'Investigasi',
                    'reporter' => 'Sarles Bolaman',
                    'kamerawan' => 'Kurniawan Suwarno',
                    'editor' => 'Rony Nanlohy',
                    'durasi' => '05:20',
                    'tanggal_produksi' => '2026-11-11',
                    'target_tayang' => '2026-11-12',
                    'status' => 'Review EIC',
                    'prioritas' => 'Penting',
                    'catatan' => 'Sertakan data grafis BMKG dan tanggapan BPBD Maluku Tengah.',
                    'video_url' => null,
                    'naskah_file' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'PKT-202611-03',
                    'judul' => 'Jelajah Kuliner Pesisir: Tradisi Papeda Panas & Ikan Kuah Kuning Natsepa',
                    'program' => 'Pesona Budaya Maluku',
                    'kategori' => 'Budaya',
                    'reporter' => 'Christine Sipahelut',
                    'kamerawan' => 'Fernanda Rasyid',
                    'editor' => 'Grace Tentua',
                    'durasi' => '04:15',
                    'tanggal_produksi' => '2026-11-09',
                    'target_tayang' => '2026-11-14',
                    'status' => 'Selesai',
                    'prioritas' => 'Biasa',
                    'catatan' => 'Untuk segmen akhir pekan; perbanyak stok audio alami laut.',
                    'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
                    'naskah_file' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'PKT-202611-04',
                    'judul' => 'Dinamika KUA-PPAS APBD 2027: Sorotan Alokasi Pendidikan & Kesehatan Daerah',
                    'program' => 'Maluku Hari Ini Pagi',
                    'kategori' => 'Pemerintahan',
                    'reporter' => 'Sarles Bolaman',
                    'kamerawan' => 'Minggus Noya',
                    'editor' => 'Hendrik Latuputty',
                    'durasi' => '03:10',
                    'tanggal_produksi' => '2026-11-09',
                    'target_tayang' => '2026-11-10',
                    'status' => 'On Air',
                    'prioritas' => 'Penting',
                    'catatan' => 'Soundbite Ketua DPRD dan pandangan umum fraksi lengkap.',
                    'video_url' => null,
                    'naskah_file' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'PKT-202611-05',
                    'judul' => 'Kesiapan Angkutan Laut Pelni & ASDP Jelang Natal 2026 dan Tahun Baru 2027',
                    'program' => 'Berita Daerah',
                    'kategori' => 'Ekonomi & UMKM',
                    'reporter' => 'Peggy Nahumury',
                    'kamerawan' => 'Andre Uktolseja',
                    'editor' => 'Grace Tentua',
                    'durasi' => '02:50',
                    'tanggal_produksi' => '2026-11-11',
                    'target_tayang' => '2026-11-11',
                    'status' => 'Editing',
                    'prioritas' => 'Penting',
                    'catatan' => 'Wawancara KSOP dan PT Pelni Cabang Ambon.',
                    'video_url' => null,
                    'naskah_file' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'PKT-202611-06',
                    'judul' => 'Revitalisasi Benteng Victoria: Merawat Cagar Budaya Peninggalan Sejarah Ambon',
                    'program' => 'Panorama Maluku',
                    'kategori' => 'Budaya',
                    'reporter' => 'Yohanis Limba',
                    'kamerawan' => 'Minggus Noya',
                    'editor' => 'Rony Nanlohy',
                    'durasi' => '04:00',
                    'tanggal_produksi' => '2026-11-12',
                    'target_tayang' => '2026-11-13',
                    'status' => 'Editing',
                    'prioritas' => 'Biasa',
                    'catatan' => 'Izin Kodam XV/Pattimura dan footage arsip kolonial.',
                    'video_url' => null,
                    'naskah_file' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            foreach ($produksis as $p) {
                DB::table('tvri_berita_produksis')->insert($p);
            }
        }

        // 2. Tabel Monitoring Berita Harian
        if (!Schema::hasTable('tvri_berita_monitorings')) {
            Schema::create('tvri_berita_monitorings', function (Blueprint $t) {
                $t->id();
                $t->string('kode', 30)->unique();
                $t->date('tanggal');
                $t->string('program_siaran', 100);
                $t->string('jam_tayang', 20); // 07:00 WIT, 15:00 WIT
                $t->string('presenter')->nullable();
                $t->string('produser')->nullable();
                $t->string('rundown_fokus'); // Fokus berita utama
                $t->integer('jumlah_berita')->default(8);
                $t->string('status_siaran', 50)->default('Sesuai Jadwal'); // Sesuai Jadwal, On Air, Delay, Kendala Teknis, Selesai
                $t->string('kualitas_av', 50)->default('Sangat Baik'); // Sangat Baik, Baik, Cukup, Gangguan
                $t->text('evaluasi_catatan')->nullable();
                $t->timestamps();
            });

            $monitorings = [
                [
                    'kode' => 'MON-202611-01',
                    'tanggal' => '2026-11-09',
                    'program_siaran' => 'Maluku Hari Ini Pagi',
                    'jam_tayang' => '07:00 - 07:30 WIT',
                    'presenter' => 'Clara Lekatompessy',
                    'produser' => 'Nahrum',
                    'rundown_fokus' => 'Pembahasan KUA-PPAS APBD 2027 di DPRD Maluku & Pantauan Harga Pangan',
                    'jumlah_berita' => 9,
                    'status_siaran' => 'Selesai',
                    'kualitas_av' => 'Sangat Baik',
                    'evaluasi_catatan' => 'Transisi video server lancar, audio narasumber jernih.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'MON-202611-02',
                    'tanggal' => '2026-11-09',
                    'program_siaran' => 'Warta Nusantara (Relai Nasional)',
                    'jam_tayang' => '12:00 - 12:30 WIT',
                    'presenter' => 'Relai Nasional TVRI Pusat',
                    'produser' => 'Nahrum (Maluku Feed)',
                    'rundown_fokus' => 'Kontribusi 2 Berita Daerah TVRI Maluku Masuk Slot Berita Nasional',
                    'jumlah_berita' => 12,
                    'status_siaran' => 'Selesai',
                    'kualitas_av' => 'Sangat Baik',
                    'evaluasi_catatan' => 'Uplink satelit SNG ke TVRI Pusat berjalan sempurna tanpa jitter.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'MON-202611-03',
                    'tanggal' => '2026-11-10',
                    'program_siaran' => 'Breaking News & LIVE Upacara',
                    'jam_tayang' => '07:30 - 09:30 WIT',
                    'presenter' => 'Monica Sipahelut & Devy Bin Umar',
                    'produser' => 'Nahrum',
                    'rundown_fokus' => 'LIVE Upacara Peringatan Hari Pahlawan dari TMP Kapahaha Ambon',
                    'jumlah_berita' => 4,
                    'status_siaran' => 'Selesai',
                    'kualitas_av' => 'Sangat Baik',
                    'evaluasi_catatan' => 'Sinyal SNG stabil 1080i, koordinasi MCR dengan switcher lapangan prima.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'MON-202611-04',
                    'tanggal' => '2026-11-10',
                    'program_siaran' => 'Maluku Hari Ini Sore',
                    'jam_tayang' => '17:00 - 17:30 WIT',
                    'presenter' => 'Devy Bin Umar',
                    'produser' => 'Nahrum',
                    'rundown_fokus' => 'Liputan Tabur Bunga di Teluk Ambon & Profil Veteran Pejuang Maluku',
                    'jumlah_berita' => 10,
                    'status_siaran' => 'Selesai',
                    'kualitas_av' => 'Sangat Baik',
                    'evaluasi_catatan' => 'Grafis opening Hari Pahlawan tepat waktu, penonton live streaming meningkat.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'MON-202611-05',
                    'tanggal' => '2026-11-11',
                    'program_siaran' => 'Berita Daerah',
                    'jam_tayang' => '19:00 - 19:30 WIT',
                    'presenter' => 'Clara Lekatompessy',
                    'produser' => 'Nahrum',
                    'rundown_fokus' => 'Konferensi Pers Transportasi Nataru & Investigasi Abrasi Hitu',
                    'jumlah_berita' => 8,
                    'status_siaran' => 'Selesai',
                    'kualitas_av' => 'Baik',
                    'evaluasi_catatan' => 'VO paket abrasi pantai perlu sedikit penyesuaian gain audio.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'MON-202611-06',
                    'tanggal' => '2026-11-12',
                    'program_siaran' => 'Dialog LIVE "Maluku Membangun"',
                    'jam_tayang' => '13:00 - 14:00 WIT',
                    'presenter' => 'Devy Bin Umar',
                    'produser' => 'Nahrum',
                    'rundown_fokus' => 'Kesiapan Ketahanan Pangan & Inflasi Daerah Bersama BPS Maluku',
                    'jumlah_berita' => 3,
                    'status_siaran' => 'Selesai',
                    'kualitas_av' => 'Sangat Baik',
                    'evaluasi_catatan' => 'Interaksi telepon pemirsa aktif, lighting studio optimal.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            foreach ($monitorings as $m) {
                DB::table('tvri_berita_monitorings')->insert($m);
            }
        }

        // 3. Tabel Dokumentasi Berita Harian
        if (!Schema::hasTable('tvri_berita_dokumentasis')) {
            Schema::create('tvri_berita_dokumentasis', function (Blueprint $t) {
                $t->id();
                $t->string('kode', 30)->unique();
                $t->date('tanggal_liputan');
                $t->string('judul_dokumentasi');
                $t->string('lokasi', 100)->default('Ambon');
                $t->string('crew', 150)->nullable();
                $t->string('jenis_media', 50)->default('Footage Video'); // Footage Video, Audio VO, Foto Lapangan, Naskah Berita, Master Siaran
                $t->string('format_file', 30)->default('MP4 1080p');
                $t->string('ukuran_file', 30)->default('1.2 GB');
                $t->string('link_media')->nullable();
                $t->string('status_arsip', 50)->default('Tersimpan DMS'); // Tersimpan DMS, Terdistribusi Digital, Master Tape
                $t->text('keterangan')->nullable();
                $t->timestamps();
            });

            $dokumentasis = [
                [
                    'kode' => 'DOK-202611-01',
                    'tanggal_liputan' => '2026-11-09',
                    'judul_dokumentasi' => 'Master Footage Rapat Paripurna KUA-PPAS APBD 2027 DPRD Maluku',
                    'lokasi' => 'Gedung DPRD Provinsi Maluku, Karang Panjang',
                    'crew' => 'Sarles Bolaman (Rep) & Minggus Noya (Cam)',
                    'jenis_media' => 'Footage Video',
                    'format_file' => 'XDCAM 50Mbps 1080i',
                    'ukuran_file' => '4.8 GB',
                    'link_media' => 'https://jadwal-liputan-tvri-maluku-750b1535.vercel.app/',
                    'status_arsip' => 'Tersimpan DMS',
                    'keterangan' => 'Arsip master mentah 2 jam wawancara dan suasana sidang.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'DOK-202611-02',
                    'tanggal_liputan' => '2026-11-09',
                    'judul_dokumentasi' => 'Dokumentasi Visual Pemantauan Harga Bahan Pokok Pasar Mardika',
                    'lokasi' => 'Pasar Mardika, Ambon',
                    'crew' => 'Yaser Tuarita (Rep) & Junaedi Asril (Cam)',
                    'jenis_media' => 'Footage Video',
                    'format_file' => 'MP4 1080p',
                    'ukuran_file' => '2.1 GB',
                    'link_media' => 'https://jadwal-liputan-tvri-maluku-750b1535.vercel.app/',
                    'status_arsip' => 'Terdistribusi Digital',
                    'keterangan' => 'Klip potongan untuk medsos Instagram dan YouTube Shorts.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'DOK-202611-03',
                    'tanggal_liputan' => '2026-11-10',
                    'judul_dokumentasi' => 'Master Broadcast LIVE Peringatan Hari Pahlawan 10 November TMP Kapahaha',
                    'lokasi' => 'Taman Makam Pahlawan Kapahaha, Ambon',
                    'crew' => 'Monica Sipahelut, Devy Bin Umar & Tim SNG',
                    'jenis_media' => 'Master Siaran',
                    'format_file' => 'ProRes 422 1080p',
                    'ukuran_file' => '18.4 GB',
                    'link_media' => 'https://www.youtube.com/@tvrimaluku',
                    'status_arsip' => 'Master Tape',
                    'keterangan' => 'Rekaman penuh upacara bendera dan tabur bunga Teluk Ambon.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'DOK-202611-04',
                    'tanggal_liputan' => '2026-11-10',
                    'judul_dokumentasi' => 'Audio VO & Naskah Profil Veteran Pejuang Kemerdekaan Asal Maluku',
                    'lokasi' => 'Kota Ambon',
                    'crew' => 'Yohanis Limba (Rep) & Junaedi Asril (Cam)',
                    'jenis_media' => 'Audio VO',
                    'format_file' => 'WAV 24-bit 48kHz',
                    'ukuran_file' => '320 MB',
                    'link_media' => null,
                    'status_arsip' => 'Tersimpan DMS',
                    'keterangan' => 'Audio narasi sejarah dan testimoni saksi pejuang 1945.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'DOK-202611-05',
                    'tanggal_liputan' => '2026-11-11',
                    'judul_dokumentasi' => 'Foto Dokumentasi & Naskah Investigasi Kerusakan Pesisir Negeri Hitu',
                    'lokasi' => 'Negeri Hitu, Maluku Tengah',
                    'crew' => 'Sarles Bolaman (Rep) & Kurniawan Suwarno (Cam)',
                    'jenis_media' => 'Foto Lapangan',
                    'format_file' => 'RAW & JPG High-Res',
                    'ukuran_file' => '1.5 GB',
                    'link_media' => null,
                    'status_arsip' => 'Tersimpan DMS',
                    'keterangan' => '35 foto dokumentasi abrasi dan wawancara kepala negeri.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'DOK-202611-06',
                    'tanggal_liputan' => '2026-11-12',
                    'judul_dokumentasi' => 'Master Video Dialog Interaktif "Maluku Membangun" Episode 46',
                    'lokasi' => 'Studio 1 TVRI Maluku',
                    'crew' => 'Devy Bin Umar & Tim Produksi Studio',
                    'jenis_media' => 'Master Siaran',
                    'format_file' => 'MP4 1080p',
                    'ukuran_file' => '3.8 GB',
                    'link_media' => 'https://www.youtube.com/@tvrimaluku',
                    'status_arsip' => 'Terdistribusi Digital',
                    'keterangan' => 'Tayang di YouTube resmi TVRI Maluku dan Portal Berita.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            foreach ($dokumentasis as $d) {
                DB::table('tvri_berita_dokumentasis')->insert($d);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tvri_berita_dokumentasis');
        Schema::dropIfExists('tvri_berita_monitorings');
        Schema::dropIfExists('tvri_berita_produksis');
    }
};
