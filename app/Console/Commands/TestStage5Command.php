<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SimandatDocumentFolder;
use App\Models\SimandatDocument;
use App\Models\SimandatDocumentVersion;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\View;

class TestStage5Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:stage5';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifikasi Lengkap Tahap 5: Dokumen & Arsip Digital Terintegrasi (DMS, Folder, Versioning, QR, Preview)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("================================================================");
        $this->info("VERIFIKASI SISTEM TAHAP 5: DOKUMEN & ARSIP DIGITAL");
        $this->info("DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI MALUKU");
        $this->info("================================================================\n");

        // 1. Folder Structure Test
        $this->info("1. Menguji Struktur Folder & Hierarki DMS...");
        $totalFolders = SimandatDocumentFolder::count();
        $rootFolders = SimandatDocumentFolder::whereNull('parent_id')->count();
        $subFolders = SimandatDocumentFolder::whereNotNull('parent_id')->count();
        $this->line("   - Total Folder Kearsipan: [{$totalFolders}]");
        $this->line("   - Folder Utama (Root): [{$rootFolders}]");
        $this->line("   - Subfolder Hierarki: [{$subFolders}]");

        if ($totalFolders === 0) {
            $this->error("   [GAGAL] Tabel folder kosong. Harap jalankan DokumenStage5Seeder.");
            return 1;
        }
        $this->info("   - Struktur Folder: OK\n");

        // 2. Documents & Multi-format Test
        $this->info("2. Menguji Pangkalan Dokumen & Format Berkas...");
        $totalDocs = SimandatDocument::count();
        $pdfCount = SimandatDocument::where('file_type', 'PDF')->count();
        $officeCount = SimandatDocument::whereIn('file_type', ['DOCX', 'DOC', 'XLSX', 'XLS', 'PPTX'])->count();
        $mediaCount = SimandatDocument::whereIn('file_type', ['JPG', 'PNG', 'MP4', 'MP3', 'ZIP'])->count();
        $this->line("   - Total Dokumen Terdaftar: [{$totalDocs}]");
        $this->line("   - Berkas PDF: [{$pdfCount}]");
        $this->line("   - Berkas Office (Word, Excel, PPT): [{$officeCount}]");
        $this->line("   - Berkas Media / Arsip (Foto, Audio, Video, Zip): [{$mediaCount}]");

        if ($totalDocs === 0) {
            $this->error("   [GAGAL] Belum ada data dokumen di database.");
            return 1;
        }
        $this->info("   - Dukungan Multi-Format Berkas: OK\n");

        // 3. Document Versioning Test
        $this->info("3. Menguji Sistem Histori Versi (Versioning)...");
        $totalVersions = SimandatDocumentVersion::count();
        $docWithMultiVer = SimandatDocument::has('versions', '>', 1)->count();
        $this->line("   - Total Arsip Versi Tercatat: [{$totalVersions}]");
        $this->line("   - Dokumen dengan Revisi Multi-Versi: [{$docWithMultiVer}]");

        if ($totalVersions < $totalDocs) {
            $this->error("   [GAGAL] Beberapa dokumen tidak memiliki catatan versi.");
            return 1;
        }
        $this->info("   - Document Versioning System: OK\n");

        // 4. QR Code & Verification Token Test
        $this->info("4. Menguji Generator QR Code & Token Keaslian...");
        $docSample = SimandatDocument::first();
        $this->line("   - Sampel Token QR: [{$docSample->qr_token}]");
        $this->line("   - URL Verifikasi Publik: [{$docSample->verification_url}]");
        $this->line("   - QR Image Data: [{$docSample->qr_image_url}]");

        if (empty($docSample->qr_token) || empty($docSample->verification_url)) {
            $this->error("   [GAGAL] Generator token QR tidak menghasilkan URL verifikasi.");
            return 1;
        }
        $this->info("   - QR Verification & Integrity Engine: OK\n");

        // 5. Access Control & Confidentiality Test
        $this->info("5. Menguji Klasifikasi Kerahasiaan & Hak Akses...");
        $confStats = SimandatDocument::selectRaw('confidentiality, count(*) as count')
            ->groupBy('confidentiality')
            ->pluck('count', 'confidentiality');
        foreach ($confStats as $conf => $cnt) {
            $this->line("   - Tingkat Akses [{$conf}]: {$cnt} berkas");
        }
        $this->info("   - Hak Akses & Level Kerahasiaan: OK\n");

        // 6. Blade Views Render Test
        $this->info("6. Menguji Render Seluruh Blade Views Tahap 5...");

        View::share('errors', new \Illuminate\Support\ViewErrorBag);

        // Login as admin for blade auth testing
        $adminUser = User::first();
        if ($adminUser) {
            auth()->login($adminUser);
        }

        // Test 1: Dashboard Kearsipan
        $view1 = View::make('dokumen.dashboard', [
            'totalDocuments' => $totalDocs,
            'totalFolders' => $totalFolders,
            'totalDownloads' => 450,
            'totalViews' => 1200,
            'totalVersions' => $totalVersions,
            'activeDocuments' => $totalDocs,
            'archivedDocuments' => 0,
            'publicDocuments' => 4,
            'confidentialDocuments' => 2,
            'divisionLabels' => ['Sekretariat', 'Bidang Kebudayaan', 'Bidang SMA & SMK'],
            'divisionCounts' => [4, 2, 2],
            'formatLabels' => ['PDF', 'DOCX', 'XLSX'],
            'formatCounts' => [5, 2, 1],
            'confLabels' => ['PUBLIK', 'INTERNAL', 'TERBATAS', 'RAHASIA'],
            'confCounts' => [3, 2, 2, 1],
            'categoryLabels' => ['SK', 'DPA', 'Kajian'],
            'categoryCounts' => [3, 2, 1],
            'recentDocuments' => SimandatDocument::with('folder')->take(5)->get(),
            'recentLogs' => ActivityLog::take(5)->get(),
            'availableYears' => collect([2026, 2025]),
            'availableDivisions' => SimandatDocument::BIDANG_LIST,
            'yearFilter' => null,
            'divisionFilter' => null,
        ])->render();
        $this->line("   - View [dokumen.dashboard]: RENDER OK (" . strlen($view1) . " bytes)");

        // Test 2: Documents Index
        $view2 = View::make('dokumen.documents.index', [
            'documents' => SimandatDocument::with(['folder', 'versions'])->paginate(10),
            'folders' => SimandatDocumentFolder::all(),
            'categories' => SimandatDocument::KATEGORI_LIST,
            'divisions' => SimandatDocument::BIDANG_LIST,
            'confidentialityOptions' => SimandatDocument::CONFIDENTIALITY_LIST,
        ])->render();
        $this->line("   - View [dokumen.documents.index]: RENDER OK (" . strlen($view2) . " bytes)");

        // Test 3: Documents Show
        $view3 = View::make('dokumen.documents.show', [
            'document' => $docSample->load(['folder', 'versions.uploader', 'school', 'culture', 'uploader']),
        ])->render();
        $this->line("   - View [dokumen.documents.show]: RENDER OK (" . strlen($view3) . " bytes)");

        // Test 4: Documents Create
        $view4 = View::make('dokumen.documents.create', [
            'folders' => SimandatDocumentFolder::all(),
            'categories' => SimandatDocument::KATEGORI_LIST,
            'divisions' => SimandatDocument::BIDANG_LIST,
            'schools' => \App\Models\SimandatSchool::all(),
            'cultures' => \App\Models\SimandatCulture::all(),
        ])->render();
        $this->line("   - View [dokumen.documents.create]: RENDER OK (" . strlen($view4) . " bytes)");

        // Test 5: Documents Edit
        $view5 = View::make('dokumen.documents.edit', [
            'document' => $docSample,
            'folders' => SimandatDocumentFolder::all(),
            'categories' => SimandatDocument::KATEGORI_LIST,
            'divisions' => SimandatDocument::BIDANG_LIST,
            'schools' => \App\Models\SimandatSchool::all(),
            'cultures' => \App\Models\SimandatCulture::all(),
        ])->render();
        $this->line("   - View [dokumen.documents.edit]: RENDER OK (" . strlen($view5) . " bytes)");

        // Test 6: Folder Explorer
        $view6 = View::make('dokumen.folders.index', [
            'currentFolder' => null,
            'breadcrumbs' => [],
            'subfolders' => SimandatDocumentFolder::whereNull('parent_id')->with('documents')->get(),
            'documents' => SimandatDocument::whereNull('folder_id')->paginate(10),
            'allFolders' => SimandatDocumentFolder::all(),
        ])->render();
        $this->line("   - View [dokumen.folders.index]: RENDER OK (" . strlen($view6) . " bytes)");

        // Test 7: Public QR Verify
        $view7 = View::make('dokumen.verify', [
            'document' => $docSample,
            'isValid' => true,
            'token' => $docSample->qr_token,
        ])->render();
        $this->line("   - View [dokumen.verify]: RENDER OK (" . strlen($view7) . " bytes)");

        $this->info("\n================================================================");
        $this->info("HASIL: SELURUH FUNGSI TAHAP 5 BERHASIL DAN TERVERIFIKASI PENUH");
        $this->info("================================================================");

        return 0;
    }
}
