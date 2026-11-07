<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ringkasan & Metrik Newsroom
        if (!Schema::hasTable('tvri_berita_ringkasans')) {
            Schema::create('tvri_berita_ringkasans', function (Blueprint $t) {
                $t->id();
                $t->string('periode')->default('September 2025');
                $t->integer('rencana_liputan')->default(120);
                $t->integer('liputan_dilakukan')->default(102);
                $t->integer('berita_diproduksi')->default(95);
                $t->integer('berita_tayang')->default(92);
                $t->bigInteger('views_digital')->default(1200000);
                $t->integer('performance_index')->default(92);
                $t->integer('kecepatan_publikasi_menit')->default(45);
                
                // Distribusi Media
                $t->integer('dist_tv')->default(92);
                $t->integer('dist_website')->default(95);
                $t->integer('dist_youtube')->default(82);
                $t->integer('dist_instagram')->default(60);
                $t->integer('dist_tiktok')->default(48);

                // Indikator Kualitas
                $t->integer('kualitas_fakta')->default(98);
                $t->integer('kualitas_etik')->default(96);
                $t->integer('kualitas_akurasi')->default(97);
                $t->integer('kualitas_visual')->default(94);
                $t->integer('kualitas_sop')->default(95);

                $t->timestamps();
            });

            DB::table('tvri_berita_ringkasans')->insert([
                'periode' => 'September 2025',
                'rencana_liputan' => 120,
                'liputan_dilakukan' => 102,
                'berita_diproduksi' => 95,
                'berita_tayang' => 92,
                'views_digital' => 1200000,
                'performance_index' => 92,
                'kecepatan_publikasi_menit' => 45,
                'dist_tv' => 92,
                'dist_website' => 95,
                'dist_youtube' => 82,
                'dist_instagram' => 60,
                'dist_tiktok' => 48,
                'kualitas_fakta' => 98,
                'kualitas_etik' => 96,
                'kualitas_akurasi' => 97,
                'kualitas_visual' => 94,
                'kualitas_sop' => 95,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Real Time Newsroom Live Feeds
        if (!Schema::hasTable('tvri_berita_realtime_feeds')) {
            Schema::create('tvri_berita_realtime_feeds', function (Blueprint $t) {
                $t->id();
                $t->string('waktu', 10);
                $t->string('kegiatan');
                $t->integer('urutan')->default(1);
                $t->string('status', 30)->default('LIVE');
                $t->timestamps();
            });

            $feeds = [
                ['waktu' => '10:24', 'kegiatan' => 'Tim liputan di Ambon kirim footage - Festival Budaya', 'urutan' => 1],
                ['waktu' => '10:12', 'kegiatan' => 'Editing berita - Penyaluran Bantuan UMKM', 'urutan' => 2],
                ['waktu' => '09:50', 'kegiatan' => 'Konfirmasi narasumber - Kebijakan Pendidikan', 'urutan' => 3],
                ['waktu' => '09:30', 'kegiatan' => 'Liputan langsung - Pelabuhan Yos Sudarso', 'urutan' => 4],
                ['waktu' => '09:15', 'kegiatan' => 'VO dalam proses - Harga Pangan di Ambon', 'urutan' => 5],
                ['waktu' => '08:40', 'kegiatan' => 'Rapat redaksi pagi selesai', 'urutan' => 6],
                ['waktu' => '08:10', 'kegiatan' => 'Penugasan tim - Monitoring Infrastruktur SBB', 'urutan' => 7],
            ];
            foreach ($feeds as $f) {
                DB::table('tvri_berita_realtime_feeds')->insert(array_merge($f, [
                    'status' => 'LIVE',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // 3. Agenda Liputan Hari Ini
        if (!Schema::hasTable('tvri_berita_agenda_liputans')) {
            Schema::create('tvri_berita_agenda_liputans', function (Blueprint $t) {
                $t->id();
                $t->string('waktu', 10);
                $t->string('agenda');
                $t->string('lokasi');
                $t->string('pic');
                $t->string('status'); // Selesai, On Air, Proses, Terjadwal
                $t->integer('urutan')->default(1);
                $t->timestamps();
            });

            $agendas = [
                ['waktu' => '08:00', 'agenda' => 'Rapat koordinasi redaksi', 'lokasi' => 'Ambon', 'pic' => 'Tim Redaksi', 'status' => 'Selesai', 'urutan' => 1],
                ['waktu' => '09:00', 'agenda' => 'Festival Budaya', 'lokasi' => 'Ambon', 'pic' => 'Tim 1', 'status' => 'On Air', 'urutan' => 2],
                ['waktu' => '11:00', 'agenda' => 'UMKM dan Ekonomi', 'lokasi' => 'Maluku Tengah', 'pic' => 'Tim 2', 'status' => 'Proses', 'urutan' => 3],
                ['waktu' => '13:00', 'agenda' => 'Pendidikan', 'lokasi' => 'SBB', 'pic' => 'Tim 3', 'status' => 'Terjadwal', 'urutan' => 4],
                ['waktu' => '15:00', 'agenda' => 'Infrastruktur', 'lokasi' => 'Maluku Tenggara', 'pic' => 'Tim 4', 'status' => 'Terjadwal', 'urutan' => 5],
                ['waktu' => '17:00', 'agenda' => 'Pariwisata', 'lokasi' => 'Banda Neira', 'pic' => 'Tim 5', 'status' => 'Terjadwal', 'urutan' => 6],
            ];
            foreach ($agendas as $ag) {
                DB::table('tvri_berita_agenda_liputans')->insert(array_merge($ag, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // 4. Catatan & Peringatan Redaksi
        if (!Schema::hasTable('tvri_berita_catatans')) {
            Schema::create('tvri_berita_catatans', function (Blueprint $t) {
                $t->id();
                $t->string('tipe', 20)->default('warning'); // warning, info, success
                $t->string('pesan');
                $t->integer('urutan')->default(1);
                $t->timestamps();
            });

            $catatans = [
                ['tipe' => 'warning', 'pesan' => '1 liputan tertunda (kendala cuaca)', 'urutan' => 1],
                ['tipe' => 'warning', 'pesan' => 'Footage dari SBT belum masuk', 'urutan' => 2],
                ['tipe' => 'info', 'pesan' => 'Verifikasi tambahan untuk berita ekonomi', 'urutan' => 3],
                ['tipe' => 'info', 'pesan' => 'Agenda liputan besok sudah disetujui', 'urutan' => 4],
                ['tipe' => 'success', 'pesan' => 'Semua berita tayang sesuai standar', 'urutan' => 5],
            ];
            foreach ($catatans as $c) {
                DB::table('tvri_berita_catatans')->insert(array_merge($c, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tvri_berita_catatans');
        Schema::dropIfExists('tvri_berita_agenda_liputans');
        Schema::dropIfExists('tvri_berita_realtime_feeds');
        Schema::dropIfExists('tvri_berita_ringkasans');
    }
};
