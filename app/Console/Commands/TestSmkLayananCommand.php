<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SimandatSmkLayanan;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class TestSmkLayananCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:smk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifikasi Modul Bidang Pembinaan SMK (Layanan Vokasi, Dokumen Resmi & CRUD)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("================================================================");
        $this->info("VERIFIKASI SISTEM: BIDANG PEMBINAAN SMK");
        $this->info("DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI MALUKU");
        $this->info("================================================================\n");

        // 1. Check SMK Services Configuration
        $this->info("1. Menguji Konfigurasi Layanan & Dokumen Resmi SMK...");
        $serviceList = array_keys(SimandatSmkLayanan::JENIS_LAYANAN_LIST);
        $this->line("   - Total Layanan Didefinisikan: " . count($serviceList));
        foreach ($serviceList as $idx => $svc) {
            $meta = SimandatSmkLayanan::JENIS_LAYANAN_LIST[$svc];
            $this->line("     " . ($idx + 1) . ". {$svc} [{$meta['prefix']}] - {$meta['seksi']}");
        }

        if (count($serviceList) < 6) {
            $this->error("   [GAGAL] Jumlah layanan SMK kurang dari standar.");
            return 1;
        }
        $this->info("   - Layanan Dokumen Vokasi SMK: OK\n");

        // 2. Check Database Records Coverage
        $this->info("2. Menguji Data Permohonan Layanan SMK di Database...");
        $totalPermohonan = SimandatSmkLayanan::count();
        $this->line("   - Total Permohonan di Database: [{$totalPermohonan}]");

        foreach ($serviceList as $svc) {
            $c = SimandatSmkLayanan::where('jenis_layanan', $svc)->count();
            $this->line("     • [{$svc}]: {$c} data");
        }

        if ($totalPermohonan === 0) {
            $this->error("   [GAGAL] Data permohonan SMK kosong di database.");
            return 1;
        }
        $this->info("   - Sebaran Data Layanan SMK: OK\n");

        // 3. Test Route Registrations
        $this->info("3. Menguji Pendaftaran Routing Web...");
        $routesToCheck = [
            'pendidikan.smk.layanan.index',
            'pendidikan.smk.layanan.create',
            'pendidikan.smk.layanan.store',
            'pendidikan.smk.layanan.show',
            'pendidikan.smk.layanan.edit',
            'pendidikan.smk.layanan.update',
            'pendidikan.smk.layanan.destroy',
            'pendidikan.smk.layanan.download',
            'pendidikan.smk.layanan.export-csv',
        ];

        foreach ($routesToCheck as $rName) {
            if (!Route::has($rName)) {
                $this->error("   [GAGAL] Route '{$rName}' belum terdaftar.");
                return 1;
            }
            $this->line("   - Route [{$rName}]: TERDAFTAR OK");
        }
        $this->info("   - Seluruh Route Layanan SMK: OK\n");

        // 4. Test Blade Views Render
        $this->info("4. Menguji Render Seluruh Blade Views SMK...");

        View::share('errors', new \Illuminate\Support\ViewErrorBag);

        $adminUser = User::first();
        if ($adminUser) {
            auth()->login($adminUser);
        }

        $sampleLayanan = SimandatSmkLayanan::first();

        $kabupatenOptions = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        // View 1: SMK Layanan Index
        $serviceCounts = [];
        foreach ($serviceList as $svc) {
            $serviceCounts[$svc] = SimandatSmkLayanan::where('jenis_layanan', $svc)->count();
        }
        $view1 = View::make('pendidikan.smk.index', [
            'layananList' => SimandatSmkLayanan::paginate(10),
            'serviceCounts' => $serviceCounts,
            'totalLayanan' => $totalPermohonan,
            'totalProses' => 3,
            'totalDisetujui' => 0,
            'totalSelesai' => 4,
            'allJenisLayanan' => SimandatSmkLayanan::JENIS_LAYANAN_LIST,
            'statusOptions' => SimandatSmkLayanan::STATUS_LIST,
            'kabupatenOptions' => $kabupatenOptions,
        ])->render();
        $this->line("   - View [pendidikan.smk.index]: RENDER OK (" . strlen($view1) . " bytes)");

        // View 2: SMK Layanan Create
        $view2 = View::make('pendidikan.smk.create', [
            'selectedService' => 'Rekomendasi Mutasi (Perpindahan) Siswa SMK',
            'allJenisLayanan' => SimandatSmkLayanan::JENIS_LAYANAN_LIST,
            'statusOptions' => SimandatSmkLayanan::STATUS_LIST,
            'konsentrasiList' => SimandatSmkLayanan::KONSENTRASI_KEAHLIAN_POPULER,
            'kabupatenOptions' => $kabupatenOptions,
        ])->render();
        $this->line("   - View [pendidikan.smk.create]: RENDER OK (" . strlen($view2) . " bytes)");

        // View 3: SMK Layanan Show
        $view3 = View::make('pendidikan.smk.show', [
            'layanan' => $sampleLayanan,
            'allJenisLayanan' => SimandatSmkLayanan::JENIS_LAYANAN_LIST,
            'statusOptions' => SimandatSmkLayanan::STATUS_LIST,
        ])->render();
        $this->line("   - View [pendidikan.smk.show]: RENDER OK (" . strlen($view3) . " bytes)");

        // View 4: SMK Layanan Edit
        $view4 = View::make('pendidikan.smk.edit', [
            'layanan' => $sampleLayanan,
            'allJenisLayanan' => SimandatSmkLayanan::JENIS_LAYANAN_LIST,
            'statusOptions' => SimandatSmkLayanan::STATUS_LIST,
            'konsentrasiList' => SimandatSmkLayanan::KONSENTRASI_KEAHLIAN_POPULER,
            'kabupatenOptions' => $kabupatenOptions,
        ])->render();
        $this->line("   - View [pendidikan.smk.edit]: RENDER OK (" . strlen($view4) . " bytes)");

        $this->info("   - Seluruh Blade Views SMK: OK\n");

        $this->info("================================================================");
        $this->info("HASIL: MODUL BIDANG PEMBINAAN SMK BERHASIL TERVERIFIKASI 100%");
        $this->info("================================================================");

        return 0;
    }
}
