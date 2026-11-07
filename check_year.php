<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$currentYear = (int) date('Y');
echo "Current real-time year: " . $currentYear . "\n";

foreach ([$currentYear, 2024, 2025] as $yr) {
    echo "--- TAHUN {$yr} ---\n";
    $r = App\Models\TvriKeuanganRingkasan::where('tahun', $yr)->first();
    echo "Ringkasan: " . ($r ? "Pagu={$r->pagu_anggaran}, Real={$r->realisasi_anggaran}, TgtPNBP={$r->target_pnbp}, RealPNBP={$r->realisasi_pnbp}" : "NULL") . "\n";
    echo "Anggarans: " . App\Models\TvriAnggaran::where('tahun', $yr)->count() . " rows\n";
    echo "PNBP: " . App\Models\TvriPnbp::where('tahun', $yr)->count() . " rows\n";
    echo "Kegiatan: " . App\Models\TvriKeuanganKegiatan::where('tahun', $yr)->count() . " rows\n";
    echo "IKPA: " . App\Models\TvriKeuanganIkpa::where('tahun', $yr)->count() . " rows\n";
}
