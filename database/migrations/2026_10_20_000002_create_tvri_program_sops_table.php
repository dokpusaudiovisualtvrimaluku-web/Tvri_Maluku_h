<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tvri_program_sops')) {
            Schema::create('tvri_program_sops', function (Blueprint $t) {
                $t->id();
                $t->string('kode', 50)->default('SOP-PRG-001');
                $t->string('judul');
                $t->string('kategori', 100)->default('Produksi');
                $t->string('revisi', 50)->default('Rev. 02 / 2025');
                $t->string('status', 50)->default('Aktif');
                $t->string('file_url')->nullable();
                $t->text('keterangan')->nullable();
                $t->integer('urutan')->default(1);
                $t->timestamps();
            });

            $sops = [
                [
                    'kode' => 'SOP-PRG-001',
                    'judul' => 'SOP Perencanaan & Usulan Program Siaran Lokal',
                    'kategori' => 'Perencanaan',
                    'revisi' => 'Rev. 02 / 2025',
                    'status' => 'Aktif',
                    'keterangan' => 'Standar baku penyusunan rancangan program dan telaah anggaran produksi',
                    'urutan' => 1
                ],
                [
                    'kode' => 'SOP-PRG-002',
                    'judul' => 'SOP Pra-Produksi, Riset Tema & Penulisan Naskah',
                    'kategori' => 'Pra Produksi',
                    'revisi' => 'Rev. 02 / 2025',
                    'status' => 'Aktif',
                    'keterangan' => 'Prosedur penetapan narasumber, shooting list, dan izin lokasi syuting',
                    'urutan' => 2
                ],
                [
                    'kode' => 'SOP-PRG-003',
                    'judul' => 'SOP Produksi Taping & Siaran Langsung (Live OB Van/Studio)',
                    'kategori' => 'Produksi',
                    'revisi' => 'Rev. 01 / 2025',
                    'status' => 'Aktif',
                    'keterangan' => 'Pedoman teknis floor manager, switcher, audio engineer, dan pengarah acara',
                    'urutan' => 3
                ],
                [
                    'kode' => 'SOP-PRG-004',
                    'judul' => 'SOP Pasca Produksi, Video Editing & Standardisasi Grafis',
                    'kategori' => 'Pasca Produksi',
                    'revisi' => 'Rev. 02 / 2025',
                    'status' => 'Aktif',
                    'keterangan' => 'Ketentuan format master file, color grading, lower third, dan audio mixing',
                    'urutan' => 4
                ],
                [
                    'kode' => 'SOP-PRG-005',
                    'judul' => 'SOP Quality Control (QC) & Sensor Kelaikan Siar Internal',
                    'kategori' => 'QC',
                    'revisi' => 'Rev. 03 / 2025',
                    'status' => 'Aktif',
                    'keterangan' => 'Verifikasi kepatuhan P3SPS, audio loudness EBU R128, dan resolusi video',
                    'urutan' => 5
                ],
                [
                    'kode' => 'SOP-PRG-006',
                    'judul' => 'SOP Kepatuhan Master Rundown & Ingest Server On-Air',
                    'kategori' => 'Penayangan',
                    'revisi' => 'Rev. 02 / 2025',
                    'status' => 'Aktif',
                    'keterangan' => 'Ketepatan jam tayang, pengisian log penayangan, dan integrasi transmisi',
                    'urutan' => 6
                ],
                [
                    'kode' => 'SOP-PRG-007',
                    'judul' => 'SOP Penanganan Insiden, Delay & Kendala Teknis Siaran',
                    'kategori' => 'Penayangan',
                    'revisi' => 'Rev. 01 / 2025',
                    'status' => 'Aktif',
                    'keterangan' => 'Protokol pengalihan siaran cadangan filler/promo dan laporan insiden',
                    'urutan' => 7
                ],
                [
                    'kode' => 'SOP-PRG-008',
                    'judul' => 'SOP Evaluasi Rating, Share & Pengarsipan Digital Master',
                    'kategori' => 'Evaluasi',
                    'revisi' => 'Rev. 02 / 2025',
                    'status' => 'Aktif',
                    'keterangan' => 'Pengolahan performa kepemirsaan serta penyimpanan master ke server arsip',
                    'urutan' => 8
                ],
            ];

            foreach ($sops as $s) {
                DB::table('tvri_program_sops')->insert(array_merge($s, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tvri_program_sops');
    }
};
