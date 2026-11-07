<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SimandatSmaLayanan;
use App\Models\SimandatSchool;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class TestSmaLayananCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:sma';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifikasi Modul Bidang Pembinaan SMA (11 Layanan & Dokumen Resmi) & Halaman Sekolah';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("================================================================");
        $this->info("VERIFIKASI SISTEM: BIDANG PEMBINAAN SMA & HALAMAN SEKOLAH");
        $this->info("DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI MALUKU");
        $this->info("================================================================\n");

        // 1. Check 11 SMA Services Configuration
        $this->info("1. Menguji Konfigurasi 11 Jenis Layanan & Dokumen SMA...");
        $serviceList = array_keys(SimandatSmaLayanan::getJenisLayananOptions());
        $this->line("   - Total Layanan Didefinisikan: " . count($serviceList));
        foreach ($serviceList as $idx => $svc) {
            $meta = SimandatSmaLayanan::getJenisLayananOptions()[$svc];
            $this->line("     " . ($idx + 1) . ". {$svc} [{$meta['prefix']}] - {$meta['seksi']}");
        }

        if (count($serviceList) !== 11) {
            $this->error("   [GAGAL] Jumlah layanan SMA harus tepat 11.");
            return 1;
        }
        $this->info("   - 11 Layanan Dokumen SMA: OK\n");

        // 2. Check Database Records Coverage
        $this->info("2. Menguji Data Permohonan Layanan SMA di Database...");
        $totalPermohonan = SimandatSmaLayanan::count();
        $this->line("   - Total Permohonan di Database: [{$totalPermohonan}]");

        foreach ($serviceList as $svc) {
            $c = SimandatSmaLayanan::where('jenis_layanan', $svc)->count();
            $this->line("     • [{$svc}]: {$c} data");
        }

        if ($totalPermohonan === 0) {
            $this->error("   [GAGAL] Data permohonan SMA kosong di database.");
            return 1;
        }
        $this->info("   - Sebaran Data 11 Layanan SMA: OK\n");

        // 3. Test Route Registrations
        $this->info("3. Menguji Pendaftaran Routing Web...");
        $routesToCheck = [
            'pendidikan.sma.layanan.index',
            'pendidikan.sma.layanan.create',
            'pendidikan.sma.layanan.store',
            'pendidikan.sma.layanan.show',
            'pendidikan.sma.layanan.edit',
            'pendidikan.sma.layanan.update',
            'pendidikan.sma.layanan.destroy',
            'pendidikan.sma.layanan.download',
            'pendidikan.sma.layanan.export-csv',
            'pendidikan.schools.index',
        ];

        foreach ($routesToCheck as $rName) {
            if (!Route::has($rName)) {
                $this->error("   [GAGAL] Route '{$rName}' belum terdaftar.");
                return 1;
            }
            $this->line("   - Route [{$rName}]: TERDAFTAR OK");
        }
        $this->info("   - Seluruh Route Layanan SMA: OK\n");

        // 4. Test Blade Views Render
        $this->info("4. Menguji Render Seluruh Blade Views SMA & Halaman Sekolah...");

        View::share('errors', new \Illuminate\Support\ViewErrorBag);

        $adminUser = User::first();
        if ($adminUser) {
            auth()->login($adminUser);
        }

        $sampleLayanan = SimandatSmaLayanan::first();
        $sampleSchool = SimandatSchool::first();

        $kabupatenOptions = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        // View 1: Enhanced Schools Index
        $view1 = View::make('pendidikan.schools.index', [
            'schools' => SimandatSchool::paginate(10),
            'kabupatenOptions' => $kabupatenOptions,
            'totalSekolah' => SimandatSchool::count(),
            'totalNegeri' => SimandatSchool::where('status_sekolah', 'Negeri')->count(),
            'totalSwasta' => SimandatSchool::where('status_sekolah', 'Swasta')->count(),
            'totalSiswa' => 5000,
            'totalGuru' => 600,
            'totalValidated' => 5,
            'jenjangCounts' => ['SMA' => 1, 'SMK' => 1, 'SLB' => 1, 'SMP' => 1, 'SD' => 1, 'TK' => 1, 'PAUD' => 1],
        ])->render();
        $this->line("   - View [pendidikan.schools.index (Enhanced)]: RENDER OK (" . strlen($view1) . " bytes)");

        // View 2: SMA Layanan Index
        $serviceCounts = [];
        foreach ($serviceList as $svc) {
            $serviceCounts[$svc] = SimandatSmaLayanan::where('jenis_layanan', $svc)->count();
        }
        $view2 = View::make('pendidikan.sma.index', [
            'layananList' => SimandatSmaLayanan::paginate(10),
            'serviceCounts' => $serviceCounts,
            'totalLayanan' => $totalPermohonan,
            'totalProses' => 2,
            'totalDisetujui' => 1,
            'totalSelesai' => 8,
            'allJenisLayanan' => SimandatSmaLayanan::getJenisLayananOptions(),
            'statusOptions' => SimandatSmaLayanan::STATUS_LIST,
            'kabupatenOptions' => $kabupatenOptions,
        ])->render();
        $this->line("   - View [pendidikan.sma.index]: RENDER OK (" . strlen($view2) . " bytes)");

        // View 3: SMA Layanan Create
        $view3 = View::make('pendidikan.sma.create', [
            'selectedService' => 'Rekomendasi Mutasi (Perpindahan) Siswa SMA',
            'allJenisLayanan' => SimandatSmaLayanan::getJenisLayananOptions(),
            'statusOptions' => SimandatSmaLayanan::STATUS_LIST,
            'kabupatenOptions' => $kabupatenOptions,
        ])->render();
        $this->line("   - View [pendidikan.sma.create]: RENDER OK (" . strlen($view3) . " bytes)");

        // View 4: SMA Layanan Show
        $view4 = View::make('pendidikan.sma.show', [
            'layanan' => $sampleLayanan,
            'allJenisLayanan' => SimandatSmaLayanan::getJenisLayananOptions(),
            'statusOptions' => SimandatSmaLayanan::STATUS_LIST,
        ])->render();
        $this->line("   - View [pendidikan.sma.show]: RENDER OK (" . strlen($view4) . " bytes)");

        // View 5: SMA Layanan Edit
        $view5 = View::make('pendidikan.sma.edit', [
            'layanan' => $sampleLayanan,
            'allJenisLayanan' => SimandatSmaLayanan::getJenisLayananOptions(),
            'statusOptions' => SimandatSmaLayanan::STATUS_LIST,
            'kabupatenOptions' => $kabupatenOptions,
        ])->render();
        $this->line("   - View [pendidikan.sma.edit]: RENDER OK (" . strlen($view5) . " bytes)");

        $this->info("   - Seluruh Blade Views SMA & Halaman Sekolah: OK\n");

        $this->info("================================================================");
        $this->info("HASIL: MODUL BIDANG PEMBINAAN SMA & HALAMAN SEKOLAH TERVERIFIKASI 100%");
        $this->info("================================================================");

        return 0;
    }
}
