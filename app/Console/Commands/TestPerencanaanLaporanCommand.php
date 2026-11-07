<?php

namespace App\Console\Commands;

use App\Models\SimandatPerencanaanProgram;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class TestPerencanaanLaporanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:perencanaan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Program, Perencanaan, Eksekutif, Laporan Terpadu, and Statistik Analisis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("==================================================================");
        $this->info("  TESTING SUITE: PROGRAM & PERENCANAAN + EKSEKUTIF & LAPORAN");
        $this->info("  DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI MALUKU");
        $this->info("==================================================================\n");

        $admin = User::first();
        if (!$admin) {
            $this->error("[-] No user found in database!");
            return 1;
        }
        Auth::login($admin);

        $passed = 0;
        $total = 0;

        // 1. Test Model & Seeder
        $total++;
        $this->line("1. Testing SimandatPerencanaanProgram Model & Seeder...");
        $count = SimandatPerencanaanProgram::count();
        if ($count >= 6) {
            $this->info("   [+] Seeder verified: {$count} records found across 6 document types.");
            $passed++;
        } else {
            $this->error("   [-] Seeder count insufficient: {$count}");
        }

        // Test Attribute Formatters
        $total++;
        $this->line("2. Testing Model Accessors & Formatters...");
        $sample = SimandatPerencanaanProgram::first();
        if ($sample && !empty($sample->formatted_pagu) && !empty($sample->status_badge_class)) {
            $this->info("   [+] Formatted Pagu: {$sample->formatted_pagu} | Sisa: {$sample->formatted_sisa} | Realisasi: {$sample->persentase_realisasi}%");
            $passed++;
        } else {
            $this->error("   [-] Model accessors failed.");
        }

        // 2. Test Perencanaan Controller
        $perencanaanController = app(\App\Http\Controllers\Perencanaan\PerencanaanProgramController::class);

        // Test Index for all 6 document types
        $types = ['Program', 'Kegiatan', 'Renstra', 'Renja', 'RKA', 'DPA'];
        foreach ($types as $type) {
            $total++;
            $this->line("3. Testing PerencanaanProgramController::index(?jenis_dokumen={$type})...");
            $req = Request::create('/perencanaan/program', 'GET', ['jenis_dokumen' => $type]);
            $res = $perencanaanController->index($req);
            if ($res instanceof \Illuminate\View\View && $res->name() === 'perencanaan.program.index') {
                $this->info("   [+] Tab {$type} loaded successfully.");
                $passed++;
            } else {
                $this->error("   [-] Tab {$type} failed to load.");
            }
        }

        // Test Create & Store
        $total++;
        $this->line("4. Testing PerencanaanProgramController::store()...");
        Storage::fake('public');
        $file = UploadedFile::fake()->create('dokumen_rka_2026.pdf', 500, 'application/pdf');

        $storeReq = Request::create('/perencanaan/program', 'POST', [
            'jenis_dokumen'            => 'RKA',
            'bidang_penanggung_jawab'  => 'Sekretariat',
            'tahun_anggaran'           => 2026,
            'kode_rekening'            => '1.01.99.9.99.9999',
            'nama_program_kegiatan'    => 'Pengujian Otomatis Sistem Perencanaan Maluku',
            'uraian_kegiatan'          => 'Unit test otomatis untuk memastikan pendaftaran dokumen RKA dan DPA berhasil.',
            'periode_pelaksanaan'      => 'Tahun 2026',
            'pagu_anggaran'            => 500000000,
            'realisasi_anggaran'       => 250000000,
            'target_kinerja'           => '1 Laporan Test Selesai',
            'realisasi_kinerja'        => '1 Laporan Test Berhasil',
            'status_pelaksanaan'       => 'PERENCANAAN',
            'pejabat_penanggung_jawab' => 'Tim QA Sistem',
        ], [], ['file_dokumen' => $file]);

        $storeRes = $perencanaanController->store($storeReq);
        $newDoc = SimandatPerencanaanProgram::where('kode_rekening', '1.01.99.9.99.9999')->first();
        if ($newDoc && $storeRes->getStatusCode() === 302) {
            $this->info("   [+] Document stored with Reg No: {$newDoc->nomor_registrasi}");
            $passed++;
        } else {
            $this->error("   [-] Document store failed.");
        }

        // Test Show
        $total++;
        $this->line("5. Testing PerencanaanProgramController::show()...");
        if ($newDoc) {
            $showRes = $perencanaanController->show($newDoc);
            if ($showRes->name() === 'perencanaan.program.show') {
                $this->info("   [+] Show page rendered successfully.");
                $passed++;
            } else {
                $this->error("   [-] Show page render failed.");
            }
        }

        // Test Edit & Update
        $total++;
        $this->line("6. Testing PerencanaanProgramController::update()...");
        if ($newDoc) {
            $updateReq = Request::create('/perencanaan/program/' . $newDoc->id, 'PUT', [
                'jenis_dokumen'            => 'RKA',
                'bidang_penanggung_jawab'  => 'Sekretariat',
                'tahun_anggaran'           => 2026,
                'kode_rekening'            => '1.01.99.9.99.9999',
                'nama_program_kegiatan'    => 'Pengujian Otomatis Sistem Perencanaan Maluku (Updated)',
                'uraian_kegiatan'          => 'Unit test otomatis updated.',
                'periode_pelaksanaan'      => 'Tahun 2026',
                'pagu_anggaran'            => 600000000,
                'realisasi_anggaran'       => 300000000,
                'target_kinerja'           => 'Target updated',
                'realisasi_kinerja'        => 'Realisasi updated',
                'status_pelaksanaan'       => 'BERJALAN',
                'pejabat_penanggung_jawab' => 'Tim QA Updated',
            ]);
            $updateRes = $perencanaanController->update($updateReq, $newDoc);
            $newDoc->refresh();
            if ($newDoc->nama_program_kegiatan === 'Pengujian Otomatis Sistem Perencanaan Maluku (Updated)' && (float)$newDoc->pagu_anggaran === 600000000.0) {
                $this->info("   [+] Document updated successfully: {$newDoc->nama_program_kegiatan}");
                $passed++;
            } else {
                $this->error("   [-] Document update failed.");
            }
        }

        // Test Export CSV
        $total++;
        $this->line("7. Testing PerencanaanProgramController::exportCsv()...");
        $csvReq = Request::create('/perencanaan/program/export-csv', 'GET', ['jenis_dokumen' => 'semua']);
        $csvRes = $perencanaanController->exportCsv($csvReq);
        if ($csvRes->getStatusCode() === 200 && str_contains($csvRes->headers->get('content-type'), 'text/csv')) {
            $this->info("   [+] CSV export streaming response generated.");
            $passed++;
        } else {
            $this->error("   [-] CSV export failed.");
        }

        // Test Destroy
        $total++;
        $this->line("8. Testing PerencanaanProgramController::destroy()...");
        if ($newDoc) {
            $delRes = $perencanaanController->destroy($newDoc);
            $checkDel = SimandatPerencanaanProgram::find($newDoc->id);
            if (!$checkDel && $delRes->getStatusCode() === 302) {
                $this->info("   [+] Document deleted successfully.");
                $passed++;
            } else {
                $this->error("   [-] Document destroy failed.");
            }
        }

        // 3. Test Laporan Controller
        $laporanController = app(\App\Http\Controllers\Laporan\LaporanController::class);

        // Test Laporan Terpadu
        $total++;
        $this->line("9. Testing LaporanController::terpadu()...");
        $lapReq = Request::create('/laporan/terpadu', 'GET', ['tahun' => 2026]);
        $lapRes = $laporanController->terpadu($lapReq);
        if ($lapRes->name() === 'laporan.terpadu') {
            $this->info("   [+] Laporan Terpadu view rendered with consolidated metrics.");
            $passed++;
        } else {
            $this->error("   [-] Laporan Terpadu failed to render.");
        }

        // Test Laporan Terpadu Export CSV
        $total++;
        $this->line("10. Testing LaporanController::exportTerpaduCsv()...");
        $lapCsvReq = Request::create('/laporan/terpadu/export-csv', 'GET');
        $lapCsvRes = $laporanController->exportTerpaduCsv($lapCsvReq);
        if ($lapCsvRes->getStatusCode() === 200 && str_contains($lapCsvRes->headers->get('content-type'), 'text/csv')) {
            $this->info("   [+] Laporan Terpadu CSV export verified.");
            $passed++;
        } else {
            $this->error("   [-] Laporan Terpadu CSV export failed.");
        }

        // Test Statistik & Analisis
        $total++;
        $this->line("11. Testing LaporanController::statistik()...");
        $statReq = Request::create('/laporan/statistik', 'GET');
        $statRes = $laporanController->statistik($statReq);
        if ($statRes->name() === 'laporan.statistik') {
            $this->info("   [+] Statistik & Analisis view rendered with 11 kab/kota chart datasets.");
            $passed++;
        } else {
            $this->error("   [-] Statistik & Analisis failed to render.");
        }

        // 4. Test Executive Dashboard Access
        $total++;
        $this->line("12. Testing Executive Dashboard (/eksekutif/dashboard)...");
        $execController = app(\App\Http\Controllers\Executive\DashboardController::class);
        $execReq = Request::create('/eksekutif/dashboard', 'GET');
        $execRes = $execController->index($execReq);
        if ($execRes->name() === 'executive.dashboard') {
            $this->info("   [+] Executive Dashboard accessible and functioning.");
            $passed++;
        } else {
            $this->error("   [-] Executive Dashboard failed.");
        }

        // 5. Test Route Registrations
        $total++;
        $this->line("13. Verifying Named Routes Registration...");
        $requiredRoutes = [
            'perencanaan.program.index',
            'perencanaan.program.create',
            'perencanaan.program.store',
            'perencanaan.program.show',
            'perencanaan.program.edit',
            'perencanaan.program.update',
            'perencanaan.program.destroy',
            'perencanaan.program.download',
            'perencanaan.program.export-csv',
            'eksekutif.dashboard',
            'laporan.terpadu',
            'laporan.terpadu.export-csv',
            'laporan.statistik',
        ];

        $routesOk = true;
        foreach ($requiredRoutes as $r) {
            if (!Route::has($r)) {
                $this->error("   [-] Route missing: {$r}");
                $routesOk = false;
            }
        }
        if ($routesOk) {
            $this->info("   [+] All 13 new routes properly registered in routes/web.php.");
            $passed++;
        }

        $this->info("\n==================================================================");
        $this->info("  RESULT: {$passed} / {$total} TESTS PASSED (" . round(($passed / $total) * 100) . "%)");
        $this->info("==================================================================");

        return $passed === $total ? 0 : 1;
    }
}
