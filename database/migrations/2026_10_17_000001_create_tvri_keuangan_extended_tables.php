<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ringkasan Keuangan Utama & Posisi Aset / Kewajiban
        if (!Schema::hasTable('tvri_keuangan_ringkasans')) {
            Schema::create('tvri_keuangan_ringkasans', function (Blueprint $t) {
                $t->id();
                $t->smallInteger('tahun')->default(2024);
                $t->decimal('pagu_anggaran', 18, 2)->default(45750000000);
                $t->decimal('realisasi_anggaran', 18, 2)->default(34860500000);
                $t->decimal('target_pnbp', 18, 2)->default(3400000000);
                $t->decimal('realisasi_pnbp', 18, 2)->default(2850000000);
                $t->decimal('piutang_tagihan', 18, 2)->default(375000000);
                $t->decimal('ikpa_skor', 5, 2)->default(92.13);
                $t->string('ikpa_predikat')->default('Sangat Baik');
                
                // Posisi Aset (dalam Miliar / Nominal rupiah)
                $t->decimal('aset_lancar', 18, 2)->default(12500000000);
                $t->decimal('aset_tetap', 18, 2)->default(68300000000);
                $t->decimal('aset_lainnya', 18, 2)->default(5200000000);
                
                // Kewajiban & Ekuitas (Pasiva)
                $t->decimal('kewajiban_lancar', 18, 2)->default(3800000000);
                $t->decimal('kewajiban_panjang', 18, 2)->default(1200000000);
                $t->decimal('ekuitas', 18, 2)->default(81000000000);
                
                $t->timestamps();
            });

            // Seed initial row
            DB::table('tvri_keuangan_ringkasans')->insert([
                'tahun' => 2024,
                'pagu_anggaran' => 45750000000,
                'realisasi_anggaran' => 34860500000,
                'target_pnbp' => 3400000000,
                'realisasi_pnbp' => 2850000000,
                'piutang_tagihan' => 375000000,
                'ikpa_skor' => 92.13,
                'ikpa_predikat' => 'Sangat Baik',
                'aset_lancar' => 12500000000,
                'aset_tetap' => 68300000000,
                'aset_lainnya' => 5200000000,
                'kewajiban_lancar' => 3800000000,
                'kewajiban_panjang' => 1200000000,
                'ekuitas' => 81000000000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Arus Kas Bulanan (Jan - Des)
        if (!Schema::hasTable('tvri_keuangan_arus_kas')) {
            Schema::create('tvri_keuangan_arus_kas', function (Blueprint $t) {
                $t->id();
                $t->smallInteger('tahun')->default(2024);
                $t->smallInteger('urutan')->default(1);
                $t->string('bulan', 10);
                $t->decimal('penerimaan', 18, 2)->default(0);
                $t->decimal('pengeluaran', 18, 2)->default(0);
                $t->decimal('saldo', 18, 2)->default(0);
                $t->timestamps();
            });

            // Seed initial 12 bulan
            $cashData = [
                ['bulan' => 'Jan', 'urutan' => 1, 'penerimaan' => 3200000000, 'pengeluaran' => 2800000000, 'saldo' => 400000000],
                ['bulan' => 'Feb', 'urutan' => 2, 'penerimaan' => 3500000000, 'pengeluaran' => 3000000000, 'saldo' => 500000000],
                ['bulan' => 'Mar', 'urutan' => 3, 'penerimaan' => 4100000000, 'pengeluaran' => 3600000000, 'saldo' => 500000000],
                ['bulan' => 'Apr', 'urutan' => 4, 'penerimaan' => 3800000000, 'pengeluaran' => 3400000000, 'saldo' => 400000000],
                ['bulan' => 'Mei', 'urutan' => 5, 'penerimaan' => 4500000000, 'pengeluaran' => 3900000000, 'saldo' => 600000000],
                ['bulan' => 'Jun', 'urutan' => 6, 'penerimaan' => 5200000000, 'pengeluaran' => 4500000000, 'saldo' => 700000000],
                ['bulan' => 'Jul', 'urutan' => 7, 'penerimaan' => 4800000000, 'pengeluaran' => 4200000000, 'saldo' => 600000000],
                ['bulan' => 'Agt', 'urutan' => 8, 'penerimaan' => 5000000000, 'pengeluaran' => 4300000000, 'saldo' => 700000000],
                ['bulan' => 'Sep', 'urutan' => 9, 'penerimaan' => 4600000000, 'pengeluaran' => 4000000000, 'saldo' => 600000000],
                ['bulan' => 'Okt', 'urutan' => 10, 'penerimaan' => 5500000000, 'pengeluaran' => 4800000000, 'saldo' => 700000000],
                ['bulan' => 'Nov', 'urutan' => 11, 'penerimaan' => 4900000000, 'pengeluaran' => 4200000000, 'saldo' => 700000000],
                ['bulan' => 'Des', 'urutan' => 12, 'penerimaan' => 5800000000, 'pengeluaran' => 5000000000, 'saldo' => 800000000],
            ];
            foreach ($cashData as $c) {
                DB::table('tvri_keuangan_arus_kas')->insert(array_merge($c, [
                    'tahun' => 2024,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // 3. Rencana & Realisasi Kegiatan Keuangan
        if (!Schema::hasTable('tvri_keuangan_kegiatans')) {
            Schema::create('tvri_keuangan_kegiatans', function (Blueprint $t) {
                $t->id();
                $t->smallInteger('tahun')->default(2024);
                $t->smallInteger('no_urut')->default(1);
                $t->string('kegiatan');
                $t->string('target')->nullable();
                $t->string('realisasi')->nullable();
                $t->string('status')->default('On Track');
                $t->string('badge')->default('success');
                $t->timestamps();
            });

            $kegiatanData = [
                ['no_urut' => 1, 'kegiatan' => 'Rekonsiliasi BMN', 'target' => 'Nov 2024', 'realisasi' => 'Dalam Proses', 'status' => 'On Track', 'badge' => 'success'],
                ['no_urut' => 2, 'kegiatan' => 'Penyusunan LK Semester II', 'target' => 'Des 2024', 'realisasi' => '-', 'status' => 'Belum Mulai', 'badge' => 'neutral'],
                ['no_urut' => 3, 'kegiatan' => 'Review Tarif PNBP', 'target' => 'Des 2024', 'realisasi' => 'Draft', 'status' => 'On Track', 'badge' => 'success'],
                ['no_urut' => 4, 'kegiatan' => 'Peningkatan IKPA', 'target' => 'Des 2024', 'realisasi' => '85%', 'status' => 'On Track', 'badge' => 'success'],
                ['no_urut' => 5, 'kegiatan' => 'Digitalisasi Pembayaran', 'target' => 'Des 2024', 'realisasi' => 'Implementasi', 'status' => 'On Track', 'badge' => 'success'],
            ];
            foreach ($kegiatanData as $k) {
                DB::table('tvri_keuangan_kegiatans')->insert(array_merge($k, [
                    'tahun' => 2024,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // 4. Indikator IKPA
        if (!Schema::hasTable('tvri_keuangan_ikpas')) {
            Schema::create('tvri_keuangan_ikpas', function (Blueprint $t) {
                $t->id();
                $t->smallInteger('tahun')->default(2024);
                $t->smallInteger('urutan')->default(1);
                $t->string('indikator');
                $t->decimal('nilai', 5, 2)->default(100);
                $t->string('bobot', 10)->default('10%');
                $t->string('skor', 10)->default('10,00');
                $t->timestamps();
            });

            $ikpaData = [
                ['urutan' => 1, 'indikator' => 'Revisi DIPA', 'nilai' => 100, 'bobot' => '10%', 'skor' => '10,00'],
                ['urutan' => 2, 'indikator' => 'Deviasi Hal III DIPA', 'nilai' => 90, 'bobot' => '15%', 'skor' => '13,50'],
                ['urutan' => 3, 'indikator' => 'Penyerapan Anggaran', 'nilai' => 93, 'bobot' => '20%', 'skor' => '18,60'],
                ['urutan' => 4, 'indikator' => 'Belanja Kontraktual', 'nilai' => 88, 'bobot' => '10%', 'skor' => '8,80'],
                ['urutan' => 5, 'indikator' => 'Penyelesaian Tagihan', 'nilai' => 95, 'bobot' => '15%', 'skor' => '14,25'],
                ['urutan' => 6, 'indikator' => 'Pengelolaan UP/TUP', 'nilai' => 90, 'bobot' => '10%', 'skor' => '9,00'],
                ['urutan' => 7, 'indikator' => 'Capaian Output', 'nilai' => 100, 'bobot' => '20%', 'skor' => '20,00'],
            ];
            foreach ($ikpaData as $ik) {
                DB::table('tvri_keuangan_ikpas')->insert(array_merge($ik, [
                    'tahun' => 2024,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tvri_keuangan_ikpas');
        Schema::dropIfExists('tvri_keuangan_kegiatans');
        Schema::dropIfExists('tvri_keuangan_arus_kas');
        Schema::dropIfExists('tvri_keuangan_ringkasans');
    }
};
