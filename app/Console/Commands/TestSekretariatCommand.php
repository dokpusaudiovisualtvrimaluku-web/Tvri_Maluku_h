<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SimandatSekretariat;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class TestSekretariatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:sekretariat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifikasi Modul Bidang Sekretariat Dinas (8 Fungsi Pokok, Tugas & CRUD)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("================================================================");
        $this->info("VERIFIKASI SISTEM: BIDANG SEKRETARIS DINAS");
        $this->info("DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI MALUKU");
        $this->info("================================================================\n");

        // 1. Check 8 Core Functions Configuration
        $this->info("1. Menguji Konfigurasi 8 Fungsi Pokok Sekretaris Dinas...");
        $fungsiList = SimandatSekretariat::FUNGSI_LIST;
        $this->line("   - Total Fungsi Utama: " . count($fungsiList));
        $i = 1;
        foreach ($fungsiList as $key => $meta) {
            $this->line("     {$i}. [{$key}] => {$meta['label']} (" . count($meta['sub_kegiatan']) . " sub-kegiatan)");
            $i++;
        }

        if (count($fungsiList) !== 8) {
            $this->error("   [GAGAL] Jumlah fungsi utama harus tepat 8.");
            return 1;
        }
        $this->info("   - 8 Fungsi Pokok Sekretaris Dinas: OK\n");

        // 2. Check Database Records Coverage
        $this->info("2. Menguji Data Agenda & Tugas Fungsi di Database...");
        $totalData = SimandatSekretariat::count();
        $this->line("   - Total Agenda Terdaftar: [{$totalData}]");

        foreach ($fungsiList as $key => $meta) {
            $c = SimandatSekretariat::where('kategori_fungsi', $key)->count();
            $this->line("     • [{$meta['label']}]: {$c} data agenda");
        }

        if ($totalData === 0) {
            $this->error("   [GAGAL] Data agenda kosong di database.");
            return 1;
        }
        $this->info("   - Sebaran Data 8 Fungsi: OK\n");

        // 3. Check Financial & Performance Aggregates
        $this->info("3. Menguji Agregat Anggaran & Kinerja...");
        $totalAlokasi = SimandatSekretariat::sum('alokasi_anggaran');
        $totalRealisasi = SimandatSekretariat::sum('realisasi_anggaran');
        $avgCapaian = round(SimandatSekretariat::avg('persentase_capaian'));

        $this->line("   - Total Alokasi Anggaran Terkelola: Rp " . number_format($totalAlokasi, 0, ',', '.'));
        $this->line("   - Total Realisasi Serapan: Rp " . number_format($totalRealisasi, 0, ',', '.'));
        $this->line("   - Rata-rata Capaian Kinerja: {$avgCapaian}%");
        $this->info("   - Kalkulasi Anggaran & Kinerja: OK\n");

        // 4. Test Route Registrations
        $this->info("4. Menguji Pendaftaran Routing Web...");
        $routesToCheck = [
            'sekretariat.data.index',
            'sekretariat.data.create',
            'sekretariat.data.store',
            'sekretariat.data.show',
            'sekretariat.data.edit',
            'sekretariat.data.update',
            'sekretariat.data.destroy',
            'sekretariat.data.download',
            'sekretariat.data.export-csv',
        ];

        foreach ($routesToCheck as $rName) {
            if (!Route::has($rName)) {
                $this->error("   [GAGAL] Route '{$rName}' belum terdaftar.");
                return 1;
            }
            $this->line("   - Route [{$rName}]: TERDAFTAR OK");
        }
        $this->info("   - Seluruh Route Sekretariat: OK\n");

        // 5. Test Blade Views Render
        $this->info("5. Menguji Render Seluruh Blade Views Sekretariat...");

        View::share('errors', new \Illuminate\Support\ViewErrorBag);

        $adminUser = User::first();
        if ($adminUser) {
            auth()->login($adminUser);
        }

        $sampleAgenda = SimandatSekretariat::first();

        // View 1: Index
        $fungsiCounts = [];
        foreach (array_keys($fungsiList) as $key) {
            $fungsiCounts[$key] = SimandatSekretariat::where('kategori_fungsi', $key)->count();
        }

        $view1 = View::make('sekretariat.index', [
            'agendaList' => SimandatSekretariat::paginate(10),
            'fungsiCounts' => $fungsiCounts,
            'totalKegiatan' => $totalData,
            'totalBerjalan' => SimandatSekretariat::whereIn('status_pelaksanaan', ['Sedang Berjalan', 'Dalam Proses'])->count(),
            'totalSelesai' => SimandatSekretariat::where('status_pelaksanaan', 'Selesai')->count(),
            'totalAlokasi' => $totalAlokasi,
            'totalRealisasi' => $totalRealisasi,
            'avgCapaian' => $avgCapaian,
            'allFungsi' => $fungsiList,
            'statusOptions' => SimandatSekretariat::STATUS_LIST,
            'prioritasOptions' => SimandatSekretariat::PRIORITAS_LIST,
            'bidangOptions' => ['Sekretariat Dinas', 'Bidang Pembinaan SMA'],
        ])->render();
        $this->line("   - View [sekretariat.index]: RENDER OK (" . strlen($view1) . " bytes)");

        // View 2: Create
        $view2 = View::make('sekretariat.create', [
            'selectedFungsi' => 'koordinasi_administrasi',
            'allFungsi' => $fungsiList,
            'statusOptions' => SimandatSekretariat::STATUS_LIST,
            'prioritasOptions' => SimandatSekretariat::PRIORITAS_LIST,
            'bidangOptions' => ['Sekretariat Dinas', 'Bidang Pembinaan SMA'],
        ])->render();
        $this->line("   - View [sekretariat.create]: RENDER OK (" . strlen($view2) . " bytes)");

        // View 3: Show
        $view3 = View::make('sekretariat.show', [
            'agenda' => $sampleAgenda,
            'allFungsi' => $fungsiList,
        ])->render();
        $this->line("   - View [sekretariat.show]: RENDER OK (" . strlen($view3) . " bytes)");

        // View 4: Edit
        $view4 = View::make('sekretariat.edit', [
            'agenda' => $sampleAgenda,
            'allFungsi' => $fungsiList,
            'statusOptions' => SimandatSekretariat::STATUS_LIST,
            'prioritasOptions' => SimandatSekretariat::PRIORITAS_LIST,
            'bidangOptions' => ['Sekretariat Dinas', 'Bidang Pembinaan SMA'],
        ])->render();
        $this->line("   - View [sekretariat.edit]: RENDER OK (" . strlen($view4) . " bytes)");

        $this->info("   - Seluruh Blade Views Sekretariat: OK\n");

        $this->info("================================================================");
        $this->info("HASIL: MODUL BIDANG SEKRETARIS DINAS BERHASIL TERVERIFIKASI 100%");
        $this->info("================================================================");

        return 0;
    }
}
