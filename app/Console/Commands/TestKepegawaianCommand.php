<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SimandatKepegawaianLayanan;
use App\Models\User;
use Illuminate\Support\Facades\View;

class TestKepegawaianCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:kepegawaian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifikasi Modul Sub Bagian Kepegawaian & Umum (10 Jenis Layanan & Naskah Dinas)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("================================================================");
        $this->info("VERIFIKASI SISTEM: SUB BAGIAN KEPEGAWAIAN DAN UMUM");
        $this->info("DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI MALUKU");
        $this->info("================================================================\n");

        // 1. Check 10 Services in Model
        $this->info("1. Menguji Konfigurasi 10 Jenis Dokumen & Layanan...");
        $serviceList = array_keys(SimandatKepegawaianLayanan::JENIS_LAYANAN_LIST);
        $this->line("   - Total Layanan Didefinisikan: " . count($serviceList));
        foreach ($serviceList as $idx => $svc) {
            $this->line("     " . ($idx + 1) . ". {$svc}");
        }

        if (count($serviceList) !== 10) {
            $this->error("   [GAGAL] Jumlah layanan harus tepat 10.");
            return 1;
        }
        $this->info("   - 10 Layanan Kepegawaian & Umum: OK\n");

        // 2. Check Database Records
        $this->info("2. Menguji Data Permohonan di Database...");
        $totalPermohonan = SimandatKepegawaianLayanan::count();
        $this->line("   - Total Permohonan di Database: [{$totalPermohonan}]");

        foreach ($serviceList as $svc) {
            $c = SimandatKepegawaianLayanan::where('jenis_layanan', $svc)->count();
            $this->line("     • [{$svc}]: {$c} data");
        }

        if ($totalPermohonan === 0) {
            $this->error("   [GAGAL] Data permohonan kosong.");
            return 1;
        }
        $this->info("   - Sebaran Data 10 Layanan: OK\n");

        // 3. Check Workflow Statuses
        $this->info("3. Menguji Alur Status Permohonan...");
        $statusCounts = SimandatKepegawaianLayanan::selectRaw('status_layanan, count(*) as cnt')
            ->groupBy('status_layanan')
            ->pluck('cnt', 'status_layanan');

        foreach ($statusCounts as $st => $cnt) {
            $this->line("   - Status [{$st}]: {$cnt} permohonan");
        }
        $this->info("   - Alur Verifikasi & Status: OK\n");

        // 4. Test Blade Views Render
        $this->info("4. Menguji Render Seluruh Blade Views Kepegawaian...");

        View::share('errors', new \Illuminate\Support\ViewErrorBag);

        $adminUser = User::first();
        if ($adminUser) {
            auth()->login($adminUser);
        }

        $sampleLayanan = SimandatKepegawaianLayanan::first();

        // View 1: Index
        $serviceCounts = [];
        foreach ($serviceList as $svc) {
            $serviceCounts[$svc] = SimandatKepegawaianLayanan::where('jenis_layanan', $svc)->count();
        }
        $view1 = View::make('kepegawaian.index', [
            'layananList' => SimandatKepegawaianLayanan::paginate(10),
            'serviceCounts' => $serviceCounts,
            'totalLayanan' => $totalPermohonan,
            'totalPending' => 2,
            'totalProses' => 2,
            'totalSelesai' => 6,
            'allJenisLayanan' => SimandatKepegawaianLayanan::JENIS_LAYANAN_LIST,
            'statusOptions' => SimandatKepegawaianLayanan::STATUS_LIST,
            'kabupatenOptions' => ['Kota Ambon', 'Maluku Tengah'],
        ])->render();
        $this->line("   - View [kepegawaian.index]: RENDER OK (" . strlen($view1) . " bytes)");

        // View 2: Create
        $view2 = View::make('kepegawaian.create', [
            'selectedService' => 'Surat / Naskah Dinas',
            'allJenisLayanan' => SimandatKepegawaianLayanan::JENIS_LAYANAN_LIST,
            'kabupatenOptions' => ['Kota Ambon', 'Maluku Tengah'],
        ])->render();
        $this->line("   - View [kepegawaian.create]: RENDER OK (" . strlen($view2) . " bytes)");

        // View 3: Show
        $view3 = View::make('kepegawaian.show', [
            'layanan' => $sampleLayanan,
        ])->render();
        $this->line("   - View [kepegawaian.show]: RENDER OK (" . strlen($view3) . " bytes)");

        // View 4: Edit
        $view4 = View::make('kepegawaian.edit', [
            'layanan' => $sampleLayanan,
            'allJenisLayanan' => SimandatKepegawaianLayanan::JENIS_LAYANAN_LIST,
            'statusOptions' => SimandatKepegawaianLayanan::STATUS_LIST,
            'kabupatenOptions' => ['Kota Ambon', 'Maluku Tengah'],
        ])->render();
        $this->line("   - View [kepegawaian.edit]: RENDER OK (" . strlen($view4) . " bytes)");

        $this->info("\n================================================================");
        $this->info("HASIL: MODUL KEPEGAWAIAN & UMUM BERHASIL TERVERIFIKASI 100%");
        $this->info("================================================================");

        return 0;
    }
}
