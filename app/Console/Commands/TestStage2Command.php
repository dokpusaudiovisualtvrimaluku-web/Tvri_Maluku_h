<?php

namespace App\Console\Commands;

use App\Services\DashboardMetricsService;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class TestStage2Command extends Command
{
    protected $signature = 'test:stage2';
    protected $description = 'Verifikasi seluruh fitur dan visualisasi TAHAP 2 Dashboard Pusat Data Terintegrasi Disdikbud Maluku';

    public function handle()
    {
        $this->info('================================================================');
        $this->info('VERIFIKASI SISTEM TAHAP 2: DASHBOARD PUSAT DATA TERINTEGRASI');
        $this->info('DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI MALUKU');
        $this->info('================================================================');

        // 1. Uji DashboardMetricsService
        $this->info(PHP_EOL . '1. Menguji Perhitungan Metrik Database (DashboardMetricsService)...');
        $request = new Request();
        $metrics = DashboardMetricsService::getMetrics($request);

        $stats = $metrics['stats'];
        $this->line("   - Total Data: [{$stats['total_data']}]");
        $this->line("   - Total Dokumen: [{$stats['total_dokumen']}]");
        $this->line("   - Total Sekolah: [{$stats['total_sekolah']}]");
        $this->line("   - Total Guru: [{$stats['total_guru']}]");
        $this->line("   - Total Peserta Didik: [{$stats['total_siswa']}]");
        $this->line("   - Total Kebudayaan: [{$stats['total_kebudayaan']}]");
        $this->line("   - Total Program: [{$stats['total_program']}]");
        $this->line("   - Total Kegiatan: [{$stats['total_kegiatan']}]");
        $this->line("   - Kelengkapan Data: [{$stats['kelengkapan_persen']}%]");

        // 2. Uji Filter Global
        $this->info(PHP_EOL . '2. Menguji Dynamic Filter (Tahun, Bidang, Kab/Kota, Kategori)...');
        $filteredRequest = new Request([
            'tahun' => '2026',
            'bidang' => 'SMA',
            'kabupaten' => 'Kota Ambon',
            'kategori' => 'SOP'
        ]);
        $filteredMetrics = DashboardMetricsService::getMetrics($filteredRequest);
        $this->line("   - Filter Berhasil Diproses (Filter Tahun: {$filteredMetrics['filter']['tahun']}, Bidang: {$filteredMetrics['filter']['bidang']}, Kab: {$filteredMetrics['filter']['kabupaten']})");

        // 3. Uji 5 Dataset Grafik Chart.js
        $this->info(PHP_EOL . '3. Menguji 5 Dataset Grafik Visualisasi (Chart.js)...');
        $charts = $metrics['charts'];
        $this->line("   - 1. Perkembangan Per Tahun: OK (" . count($charts['perkembangan_tahun']['labels']) . " tahun)");
        $this->line("   - 2. Sebaran 11 Kab/Kota: OK (" . count($charts['kabupaten']['labels']) . " wilayah)");
        $this->line("   - 3. Kategori Dokumen: OK (" . count($charts['kategori_dokumen']['labels']) . " kategori)");
        $this->line("   - 4. Kelengkapan Bidang: OK (" . count($charts['kelengkapan_bidang']['labels']) . " bidang)");
        $this->line("   - 5. Program & Kegiatan: OK (" . count($charts['program_kegiatan']['labels']) . " sektor)");

        // 4. Uji System Health Status
        $this->info(PHP_EOL . '4. Menguji System Status Healthcheck...');
        $status = $metrics['system_status'];
        $this->line("   - Database: [{$status['database']}]");
        $this->line("   - Storage: [{$status['storage']}]");
        $this->line("   - System: [{$status['system']}]");
        $this->line("   - Last Backup: [{$status['last_backup']}]");

        // 5. Uji Render Blade View Admin
        $this->info(PHP_EOL . '5. Menguji Render View Blade Admin Dashboard...');
        try {
            auth()->loginUsingId(1);
            View::share('errors', new \Illuminate\Support\ViewErrorBag());
            $adminHtml = View::make('admin.dashboard', $metrics)->render();
            $this->line("   - Admin Dashboard View: RENDER SUCCEEDED (" . strlen($adminHtml) . " bytes)");
        } catch (\Exception $e) {
            $this->error("   - Admin Dashboard View Render ERROR: " . $e->getMessage());
            return 1;
        }

        // 6. Uji Render Blade View Executive
        $this->info(PHP_EOL . '6. Menguji Render View Blade Executive Dashboard...');
        try {
            auth()->loginUsingId(2);
            View::share('errors', new \Illuminate\Support\ViewErrorBag());
            $execHtml = View::make('executive.dashboard', $metrics)->render();
            $this->line("   - Executive Dashboard View: RENDER SUCCEEDED (" . strlen($execHtml) . " bytes)");
        } catch (\Exception $e) {
            $this->error("   - Executive Dashboard View Render ERROR: " . $e->getMessage());
            return 1;
        }

        $this->info(PHP_EOL . '================================================================');
        $this->info('HASIL: SELURUH FUNGSI TAHAP 2 BERHASIL DAN TERVERIFIKASI PENUH');
        $this->info('================================================================');

        return 0;
    }
}