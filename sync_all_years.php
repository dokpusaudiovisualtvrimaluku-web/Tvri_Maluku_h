<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$currentYear = (int) date('Y'); // 2026
echo "Syncing data for year: {$currentYear} and 2024...\n";

foreach ([$currentYear, 2024] as $yr) {
    // 1. Ringkasan
    $ringkasan = App\Models\TvriKeuanganRingkasan::firstOrCreate(['tahun' => $yr]);
    $ringkasan->update([
        'pagu_anggaran' => 45750000000,
        'realisasi_anggaran' => 34356500000,
        'target_pnbp' => 5000000,
        'realisasi_pnbp' => 400000000,
        'piutang_tagihan' => 425000000,
        'ikpa_skor' => 92.13,
        'ikpa_predikat' => 'Sangat Baik',
        'aset_lancar' => 12500000000,
        'aset_tetap' => 68300000000,
        'aset_lainnya' => 5200000000,
        'kewajiban_lancar' => 3800000000,
        'kewajiban_panjang' => 1200000000,
        'ekuitas' => 81000000000,
    ]);

    // 2. Anggaran Belanja
    $anggarans = [
        ['kode' => '51', 'uraian' => 'Belanja Pegawai', 'pagu' => 19500000000, 'realisasi' => 16006500000, 'tahun' => $yr],
        ['kode' => '52', 'uraian' => 'Belanja Barang', 'pagu' => 17000000000, 'realisasi' => 12114000000, 'tahun' => $yr],
        ['kode' => '53', 'uraian' => 'Belanja Modal', 'pagu' => 8500000000, 'realisasi' => 5780000000, 'tahun' => $yr],
        ['kode' => '54', 'uraian' => 'Belanja Lainnya', 'pagu' => 750000000, 'realisasi' => 456000000, 'tahun' => $yr],
    ];
    foreach ($anggarans as $ang) {
        App\Models\TvriAnggaran::updateOrCreate(
            ['tahun' => $yr, 'kode' => $ang['kode']],
            $ang
        );
    }

    // 3. PNBP
    App\Models\TvriPnbp::where('tahun', $yr)->delete();
    App\Models\TvriPnbp::create([
        'jenis' => 'Infotorial',
        'target' => 5000000,
        'realisasi' => 400000000,
        'tahun' => $yr,
    ]);

    // 4. IKPA
    $ikpas = [
        ['urutan' => 1, 'indikator' => 'Revisi DIPA', 'nilai' => 100, 'bobot' => '10%', 'skor' => '10,00', 'tahun' => $yr],
        ['urutan' => 2, 'indikator' => 'Deviasi Hal III DIPA', 'nilai' => 90, 'bobot' => '15%', 'skor' => '13,50', 'tahun' => $yr],
        ['urutan' => 3, 'indikator' => 'Penyerapan Anggaran', 'nilai' => 93, 'bobot' => '20%', 'skor' => '18,60', 'tahun' => $yr],
        ['urutan' => 4, 'indikator' => 'Belanja Kontraktual', 'nilai' => 88, 'bobot' => '10%', 'skor' => '8,80', 'tahun' => $yr],
        ['urutan' => 5, 'indikator' => 'Penyelesaian Tagihan', 'nilai' => 95, 'bobot' => '15%', 'skor' => '14,25', 'tahun' => $yr],
        ['urutan' => 6, 'indikator' => 'Pengelolaan UP/TUP', 'nilai' => 90, 'bobot' => '10%', 'skor' => '9,00', 'tahun' => $yr],
        ['urutan' => 7, 'indikator' => 'Capaian Output', 'nilai' => 100, 'bobot' => '20%', 'skor' => '20,00', 'tahun' => $yr],
    ];
    foreach ($ikpas as $ik) {
        App\Models\TvriKeuanganIkpa::updateOrCreate(
            ['tahun' => $yr, 'urutan' => $ik['urutan']],
            $ik
        );
    }

    // 5. Kegiatan
    $kegiatans = [
        ['no_urut' => 1, 'kegiatan' => 'Rekonsiliasi BMN', 'target' => 'Nov ' . $yr, 'realisasi' => 'Dalam Proses', 'status' => 'On Track', 'badge' => 'success', 'tahun' => $yr],
        ['no_urut' => 2, 'kegiatan' => 'Penyusunan LK Semester II', 'target' => 'Des ' . $yr, 'realisasi' => '-', 'status' => 'Belum Mulai', 'badge' => 'neutral', 'tahun' => $yr],
        ['no_urut' => 3, 'kegiatan' => 'Review Tarif PNBP', 'target' => 'Des ' . $yr, 'realisasi' => 'Draft', 'status' => 'On Track', 'badge' => 'success', 'tahun' => $yr],
        ['no_urut' => 4, 'kegiatan' => 'Peningkatan IKPA', 'target' => 'Des ' . $yr, 'realisasi' => '85%', 'status' => 'On Track', 'badge' => 'success', 'tahun' => $yr],
        ['no_urut' => 5, 'kegiatan' => 'Digitalisasi Pembayaran', 'target' => 'Des ' . $yr, 'realisasi' => 'Implementasi', 'status' => 'On Track', 'badge' => 'success', 'tahun' => $yr],
    ];
    foreach ($kegiatans as $k) {
        App\Models\TvriKeuanganKegiatan::updateOrCreate(
            ['tahun' => $yr, 'no_urut' => $k['no_urut']],
            $k
        );
    }

    // 6. Arus Kas
    $defaultCash = [
        ['bulan' => 'Jan', 'urutan' => 1, 'penerimaan' => 3200000000, 'pengeluaran' => 2800000000, 'saldo' => 400000000, 'tahun' => $yr],
        ['bulan' => 'Feb', 'urutan' => 2, 'penerimaan' => 3500000000, 'pengeluaran' => 3000000000, 'saldo' => 500000000, 'tahun' => $yr],
        ['bulan' => 'Mar', 'urutan' => 3, 'penerimaan' => 4100000000, 'pengeluaran' => 3600000000, 'saldo' => 500000000, 'tahun' => $yr],
        ['bulan' => 'Apr', 'urutan' => 4, 'penerimaan' => 3800000000, 'pengeluaran' => 3400000000, 'saldo' => 400000000, 'tahun' => $yr],
        ['bulan' => 'Mei', 'urutan' => 5, 'penerimaan' => 4500000000, 'pengeluaran' => 3900000000, 'saldo' => 600000000, 'tahun' => $yr],
        ['bulan' => 'Jun', 'urutan' => 6, 'penerimaan' => 5200000000, 'pengeluaran' => 4500000000, 'saldo' => 700000000, 'tahun' => $yr],
        ['bulan' => 'Jul', 'urutan' => 7, 'penerimaan' => 4800000000, 'pengeluaran' => 4200000000, 'saldo' => 600000000, 'tahun' => $yr],
        ['bulan' => 'Agt', 'urutan' => 8, 'penerimaan' => 5000000000, 'pengeluaran' => 4300000000, 'saldo' => 700000000, 'tahun' => $yr],
        ['bulan' => 'Sep', 'urutan' => 9, 'penerimaan' => 4600000000, 'pengeluaran' => 4000000000, 'saldo' => 600000000, 'tahun' => $yr],
        ['bulan' => 'Okt', 'urutan' => 10, 'penerimaan' => 5500000000, 'pengeluaran' => 4800000000, 'saldo' => 700000000, 'tahun' => $yr],
        ['bulan' => 'Nov', 'urutan' => 11, 'penerimaan' => 4900000000, 'pengeluaran' => 4200000000, 'saldo' => 700000000, 'tahun' => $yr],
        ['bulan' => 'Des', 'urutan' => 12, 'penerimaan' => 5800000000, 'pengeluaran' => 5000000000, 'saldo' => 800000000, 'tahun' => $yr],
    ];
    foreach ($defaultCash as $dc) {
        App\Models\TvriKeuanganArusKas::updateOrCreate(
            ['tahun' => $yr, 'urutan' => $dc['urutan']],
            $dc
        );
    }

    echo "Year {$yr} synced successfully.\n";
}
