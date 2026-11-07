<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ringkasan & Metrik Program
        if (!Schema::hasTable('tvri_program_ringkasans')) {
            Schema::create('tvri_program_ringkasans', function (Blueprint $t) {
                $t->id();
                $t->string('periode')->default('September 2025');
                
                // Metrik Utama Atas
                $t->integer('total_direncanakan')->default(51);
                $t->string('diff_total', 20)->default('+6%');
                $t->string('comp_total', 50)->default('vs Agustus 2025');
                $t->integer('dalam_produksi')->default(18);
                $t->string('diff_produksi', 20)->default('+20%');
                $t->integer('selesai_produksi')->default(28);
                $t->string('diff_selesai', 20)->default('+12%');
                $t->integer('tayang')->default(48);
                $t->string('diff_tayang', 20)->default('+14%');
                $t->integer('terlambat')->default(3);
                $t->string('diff_terlambat', 20)->default('-50%');
                $t->integer('schedule_compliance')->default(94);
                $t->integer('target_compliance')->default(90);

                // Tahap Produksi Program
                $t->integer('tahap_perencanaan_count')->default(51);
                $t->integer('tahap_perencanaan_pct')->default(100);
                $t->integer('tahap_pra_produksi_count')->default(45);
                $t->integer('tahap_pra_produksi_pct')->default(88);
                $t->integer('tahap_produksi_count')->default(36);
                $t->integer('tahap_produksi_pct')->default(71);
                $t->integer('tahap_pasca_produksi_count')->default(28);
                $t->integer('tahap_pasca_produksi_pct')->default(55);
                $t->integer('tahap_qc_count')->default(28);
                $t->integer('tahap_qc_pct')->default(55);
                $t->integer('tahap_tayang_count')->default(48);
                $t->integer('tahap_tayang_pct')->default(94);

                // Performa Penayangan
                $t->string('total_audience', 50)->default('1,2 Juta');
                $t->string('diff_audience', 20)->default('+18%');
                $t->string('rating_rata', 20)->default('2,8');
                $t->string('diff_rating', 20)->default('+12%');
                $t->string('share', 20)->default('12%');
                $t->string('diff_share', 20)->default('+20%');
                $t->string('durasi_tonton', 50)->default('18 Menit');
                $t->string('diff_durasi', 20)->default('+15%');

                // Metadata & Slogan
                $t->string('header_waktu')->default('Selasa, 2 September 2025 | 10:24 WIT');
                $t->string('live_status')->default('Live System | Periode: September 2025');
                $t->string('slogan_header')->default('KONTEN BERKUALITAS, MENGANGKAT POTENSI MALUKU, UNTUK INDONESIA');
                $t->string('slogan_sub')->default('Dari Maluku untuk Indonesia');
                $t->string('footer_slogan')->default('Maluku Bisa! Indonesia Maju!');
                $t->string('footer_nilai')->default('Profesional | Konten Lokal, Dampak Global | Kolaboratif | Inovatif | Berkelanjutan');

                $t->timestamps();
            });

            DB::table('tvri_program_ringkasans')->insert([
                'periode' => 'September 2025',
                'total_direncanakan' => 51,
                'diff_total' => '+6%',
                'comp_total' => 'vs Agustus 2025',
                'dalam_produksi' => 18,
                'diff_produksi' => '+20%',
                'selesai_produksi' => 28,
                'diff_selesai' => '+12%',
                'tayang' => 48,
                'diff_tayang' => '+14%',
                'terlambat' => 3,
                'diff_terlambat' => '-50%',
                'schedule_compliance' => 94,
                'target_compliance' => 90,

                'tahap_perencanaan_count' => 51,
                'tahap_perencanaan_pct' => 100,
                'tahap_pra_produksi_count' => 45,
                'tahap_pra_produksi_pct' => 88,
                'tahap_produksi_count' => 36,
                'tahap_produksi_pct' => 71,
                'tahap_pasca_produksi_count' => 28,
                'tahap_pasca_produksi_pct' => 55,
                'tahap_qc_count' => 28,
                'tahap_qc_pct' => 55,
                'tahap_tayang_count' => 48,
                'tahap_tayang_pct' => 94,

                'total_audience' => '1,2 Juta',
                'diff_audience' => '+18%',
                'rating_rata' => '2,8',
                'diff_rating' => '+12%',
                'share' => '12%',
                'diff_share' => '+20%',
                'durasi_tonton' => '18 Menit',
                'diff_durasi' => '+15%',

                'header_waktu' => 'Selasa, 2 September 2025 | 10:24 WIT',
                'live_status' => 'Live System | Periode: September 2025',
                'slogan_header' => 'KONTEN BERKUALITAS, MENGANGKAT POTENSI MALUKU, UNTUK INDONESIA',
                'slogan_sub' => 'Dari Maluku untuk Indonesia',
                'footer_slogan' => 'Maluku Bisa! Indonesia Maju!',
                'footer_nilai' => 'Profesional | Konten Lokal, Dampak Global | Kolaboratif | Inovatif | Berkelanjutan',

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Agenda Kalender Penayangan
        if (!Schema::hasTable('tvri_program_agendas')) {
            Schema::create('tvri_program_agendas', function (Blueprint $t) {
                $t->id();
                $t->string('tanggal', 50);
                $t->string('kegiatan');
                $t->string('tipe', 50)->default('Tayang'); // Tayang, Produksi, Deadline, Event Khusus
                $t->integer('urutan')->default(1);
                $t->timestamps();
            });

            $agendas = [
                ['tanggal' => '2 Sep 2025', 'kegiatan' => 'Tayang: Maluku Hari Ini (Live)', 'tipe' => 'Tayang', 'urutan' => 1],
                ['tanggal' => '5 Sep 2025', 'kegiatan' => 'Syuting: Pesona Maluku (Pantai Ora)', 'tipe' => 'Produksi', 'urutan' => 2],
                ['tanggal' => '7 Sep 2025', 'kegiatan' => 'Tayang: Dialog Maluku', 'tipe' => 'Tayang', 'urutan' => 3],
                ['tanggal' => '10 Sep 2025', 'kegiatan' => 'Produksi: Maluku Belajar', 'tipe' => 'Produksi', 'urutan' => 4],
                ['tanggal' => '15 Sep 2025', 'kegiatan' => 'Special Program: HUT Maluku', 'tipe' => 'Event Khusus', 'urutan' => 5],
                ['tanggal' => '20 Sep 2025', 'kegiatan' => 'Tayang: Jejak Budaya', 'tipe' => 'Tayang', 'urutan' => 6],
                ['tanggal' => '25 Sep 2025', 'kegiatan' => 'Evaluasi Program Triwulan III', 'tipe' => 'Deadline', 'urutan' => 7],
            ];

            foreach ($agendas as $a) {
                DB::table('tvri_program_agendas')->insert(array_merge($a, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // 3. Program Highlight
        if (!Schema::hasTable('tvri_program_highlights')) {
            Schema::create('tvri_program_highlights', function (Blueprint $t) {
                $t->id();
                $t->string('judul');
                $t->string('tagline');
                $t->string('icon', 50)->default('tv');
                $t->integer('urutan')->default(1);
                $t->timestamps();
            });

            $highlights = [
                ['judul' => 'MALUKU HARI INI', 'tagline' => 'Berita Terpercaya untuk Anda', 'icon' => 'newspaper', 'urutan' => 1],
                ['judul' => 'PESONA MALUKU', 'tagline' => 'Keindahan Tiada Akhir', 'icon' => 'camera', 'urutan' => 2],
                ['judul' => 'DIALOG MALUKU', 'tagline' => 'Ruang Aspirasi Masyarakat', 'icon' => 'chat', 'urutan' => 3],
                ['judul' => 'JEJAK BUDAYA', 'tagline' => 'Merawat Warisan Leluhur', 'icon' => 'sparkles', 'urutan' => 4],
                ['judul' => 'MALUKU BELAJAR', 'tagline' => 'Untuk Generasi Emas', 'icon' => 'academic', 'urutan' => 5],
            ];

            foreach ($highlights as $h) {
                DB::table('tvri_program_highlights')->insert(array_merge($h, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tvri_program_highlights');
        Schema::dropIfExists('tvri_program_agendas');
        Schema::dropIfExists('tvri_program_ringkasans');
    }
};
