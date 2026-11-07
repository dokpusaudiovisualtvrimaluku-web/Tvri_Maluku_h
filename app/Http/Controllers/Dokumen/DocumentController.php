<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use App\Models\SimandatDocument;
use App\Models\SimandatDocumentFolder;
use App\Models\SimandatDocumentVersion;
use App\Models\SimandatSchool;
use App\Models\SimandatCulture;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents with search and multi-filtering.
     */
    public function index(Request $request)
    {
        $query = SimandatDocument::with(['folder', 'school', 'culture', 'uploader']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('document_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('folder_id')) {
            $query->where('folder_id', $request->folder_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('division')) {
            $query->where('division', $request->division);
        }

        if ($request->filled('confidentiality')) {
            $query->where('confidentiality', $request->confidentiality);
        }

        if ($request->filled('format')) {
            $query->where('file_type', strtoupper($request->format));
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Export to CSV if requested
        if ($request->get('export') === 'csv') {
            return $this->exportCsv($query->get());
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $folders = $this->getTvriFolders();
        $categories = SimandatDocument::KATEGORI_LIST;
        $divisions = SimandatDocument::BIDANG_LIST;
        $confidentialityOptions = SimandatDocument::CONFIDENTIALITY_LIST;

        return view('dokumen.documents.index', compact('documents', 'folders', 'categories', 'divisions', 'confidentialityOptions'));
    }

    /**
     * Dapatkan daftar 11 folder resmi TVRI Maluku sesuai urutan instruksi:
     * SOP, Tata Usaha, Berita, Umum, Program, KBM, promo, Pengembangan Usaha, Teknik, Teknik Transmisi, dan Keuangan.
     */
    private function getTvriFolders()
    {
        $order = [
            'SOP',
            'Tata Usaha',
            'Berita',
            'Umum',
            'Program',
            'KBM',
            'promo',
            'Pengembangan Usaha',
            'Teknik',
            'Teknik Transmisi',
            'Keuangan',
        ];

        if (SimandatDocumentFolder::count() === 0) {
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'TvriDokumenFolderSeeder']);
        }

        return SimandatDocumentFolder::all()->sortBy(function ($f) use ($order) {
            $idx = array_search($f->name, $order);
            return $idx !== false ? $idx : 999;
        })->values();
    }

    /**
     * Show form to upload new document.
     */
    public function create(Request $request)
    {
        $folders = $this->getTvriFolders();
        $categories = SimandatDocument::KATEGORI_LIST;
        $divisions = SimandatDocument::BIDANG_LIST;
        $schools = SimandatSchool::orderBy('nama')->get();
        $cultures = SimandatCulture::orderBy('nama')->get();

        return view('dokumen.documents.create', compact('folders', 'categories', 'divisions', 'schools', 'cultures'));
    }

    /**
     * Store newly uploaded document with auto-versioning and audit logging.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:100',
            'folder_id' => 'nullable|exists:simandat_document_folders,id',
            'category' => 'required|string|max:100',
            'division' => 'required|string|max:100',
            'description' => 'nullable|string',
            'document_date' => 'nullable|date',
            'year' => 'nullable|integer|min:1990|max:2050',
            'confidentiality' => 'required|in:PUBLIK,INTERNAL,TERBATAS,RAHASIA',
            'status' => 'required|in:AKTIF,INAKTIF,RETENSI,DIARSIPKAN',
            'school_id' => 'nullable|exists:simandat_schools,id',
            'culture_id' => 'nullable|exists:simandat_cultures,id',
            'tags' => 'nullable|string',
            'file' => 'required|file|max:51200', // Max 50MB
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileExt = strtoupper($file->getClientOriginalExtension());
        $fileSize = $this->formatBytes($file->getSize());
        $filePath = $file->store('documents/' . date('Y/m'), 'public');

        $user = Auth::user();
        $uploaderName = $user ? $user->name : 'Super Admin (Disdikbud)';
        $uploaderId = $user ? $user->id : null;

        $document = SimandatDocument::create([
            'folder_id' => $validated['folder_id'] ?? null,
            'document_number' => $validated['document_number'] ?? ('DOC/' . date('Y') . '/' . strtoupper(Str::random(6))),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'division' => $validated['division'],
            'description' => $validated['description'] ?? null,
            'document_date' => $validated['document_date'] ?? now()->toDateString(),
            'year' => $validated['year'] ?? (int)date('Y'),
            'confidentiality' => $validated['confidentiality'],
            'status' => $validated['status'],
            'current_version' => 'v1.0',
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileExt,
            'file_size' => $fileSize,
            'school_id' => $validated['school_id'] ?? null,
            'culture_id' => $validated['culture_id'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'qr_token' => (string) Str::uuid(),
            'uploader_id' => $uploaderId,
            'uploader_name' => $uploaderName,
        ]);

        // Save initial version record
        SimandatDocumentVersion::create([
            'document_id' => $document->id,
            'version_number' => 'v1.0',
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileExt,
            'file_size' => $fileSize,
            'changelog_note' => 'Unggahan awal naskah dokumen resmi.',
            'uploaded_by' => $uploaderId,
            'uploader_name' => $uploaderName,
        ]);

        // Audit Trail
        ActivityLogger::log(
            'DOCUMENT_UPLOAD',
            "Mengunggah dokumen baru '{$document->title}' ({$document->document_number}) dengan versi v1.0"
        );

        // Sinkronisasi otomatis ke Modul Teknik Transmisi & Teknik Studio
        $this->syncDocumentToTvriModules($document, $filePath);

        return redirect()->route('dokumen.documents.show', $document->id)
            ->with('success', "Dokumen '{$document->title}' berhasil diunggah ke Pangkalan Arsip Digital dengan QR Token resmi.");
    }

    /**
     * Display document details, preview, version history, and QR verification.
     */
    public function show($id)
    {
        $document = SimandatDocument::with(['folder', 'school', 'culture', 'versions.uploader', 'uploader'])->findOrFail($id);

        $user = Auth::user();
        if (!$document->canAccess($user)) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk melihat berkas bertanda Rahasia & Khusus.');
        }

        $document->increment('view_count');

        return view('dokumen.documents.show', compact('document'));
    }

    /**
     * Show form to edit document metadata.
     */
    public function edit($id)
    {
        $document = SimandatDocument::findOrFail($id);
        $folders = $this->getTvriFolders();
        $categories = SimandatDocument::KATEGORI_LIST;
        $divisions = SimandatDocument::BIDANG_LIST;
        $schools = SimandatSchool::orderBy('nama')->get();
        $cultures = SimandatCulture::orderBy('nama')->get();

        return view('dokumen.documents.edit', compact('document', 'folders', 'categories', 'divisions', 'schools', 'cultures'));
    }

    /**
     * Update document metadata in storage.
     */
    public function update(Request $request, $id)
    {
        $document = SimandatDocument::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:100',
            'folder_id' => 'nullable|exists:simandat_document_folders,id',
            'category' => 'required|string|max:100',
            'division' => 'required|string|max:100',
            'description' => 'nullable|string',
            'document_date' => 'nullable|date',
            'year' => 'nullable|integer|min:1990|max:2050',
            'confidentiality' => 'required|in:PUBLIK,INTERNAL,TERBATAS,RAHASIA',
            'status' => 'required|in:AKTIF,INAKTIF,RETENSI,DIARSIPKAN',
            'school_id' => 'nullable|exists:simandat_schools,id',
            'culture_id' => 'nullable|exists:simandat_cultures,id',
            'tags' => 'nullable|string',
        ]);

        $oldData = $document->toArray();
        $document->update($validated);

        // Audit Trail
        ActivityLogger::log(
            'DOCUMENT_UPDATE',
            "Memperbarui metadata dokumen '{$document->title}' ({$document->document_number})"
        );

        return redirect()->route('dokumen.documents.show', $document->id)
            ->with('success', "Metadata dokumen '{$document->title}' berhasil diperbarui.");
    }

    /**
     * Upload a new revision version for an existing document.
     */
    public function storeVersion(Request $request, $id)
    {
        $document = SimandatDocument::findOrFail($id);

        $validated = $request->validate([
            'version_number' => 'nullable|string|max:20',
            'changelog_note' => 'required|string|max:500',
            'file' => 'required|file|max:51200', // Max 50MB
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileExt = strtoupper($file->getClientOriginalExtension());
        $fileSize = $this->formatBytes($file->getSize());
        $filePath = $file->store('documents/' . date('Y/m'), 'public');

        $user = Auth::user();
        $uploaderName = $user ? $user->name : 'Super Admin (Disdikbud)';
        $uploaderId = $user ? $user->id : null;

        // Determine new version string if not provided
        $versionNumber = $validated['version_number'];
        if (empty($versionNumber)) {
            $lastVersion = $document->versions()->count();
            $versionNumber = 'v' . ($lastVersion + 1) . '.0';
        }

        // Create version record
        $version = SimandatDocumentVersion::create([
            'document_id' => $document->id,
            'version_number' => $versionNumber,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileExt,
            'file_size' => $fileSize,
            'changelog_note' => $validated['changelog_note'],
            'uploaded_by' => $uploaderId,
            'uploader_name' => $uploaderName,
        ]);

        // Update document active file and current_version
        $document->update([
            'current_version' => $versionNumber,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileExt,
            'file_size' => $fileSize,
        ]);

        // Audit Trail
        ActivityLogger::log(
            'DOCUMENT_VERSION_NEW',
            "Mengunggah revisi versi {$versionNumber} untuk dokumen '{$document->title}'"
        );

        // Sinkronisasi otomatis ke Modul Teknik Transmisi & Teknik Studio
        $this->syncDocumentToTvriModules($document, $filePath);

        return redirect()->route('dokumen.documents.show', $document->id)
            ->with('success', "Versi {$versionNumber} berhasil diunggah dan ditetapkan sebagai versi aktif dokumen.");
    }

    /**
     * Download active document file with download counter and audit log.
     */
    public function download($id)
    {
        $document = SimandatDocument::findOrFail($id);

        $user = Auth::user();
        if (!$document->canAccess($user)) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk mengunduh dokumen Rahasia.');
        }

        $document->increment('download_count');

        // Audit Trail
        ActivityLogger::log(
            'DOCUMENT_DOWNLOAD',
            "Mengunduh dokumen '{$document->title}' ({$document->document_number})"
        );

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->download($document->file_path, $document->file_name);
        }

        // Synthetic fallback if mock file
        return $this->generateFallbackDownload($document);
    }

    /**
     * Download a specific historic version of the document.
     */
    public function downloadVersion($versionId)
    {
        $version = SimandatDocumentVersion::with('document')->findOrFail($versionId);
        $document = $version->document;

        $user = Auth::user();
        if (!$document->canAccess($user)) {
            abort(403, 'Akses Ditolak: Dokumen bersifat Rahasia.');
        }

        // Audit Trail
        ActivityLogger::log(
            'DOCUMENT_VERSION_DOWNLOAD',
            "Mengunduh arsip versi historis {$version->version_number} untuk '{$document->title}'"
        );

        if ($version->file_path && Storage::disk('public')->exists($version->file_path)) {
            return Storage::disk('public')->download($version->file_path, $version->file_name);
        }

        return $this->generateFallbackDownload($document, $version);
    }

    /**
     * In-browser preview stream response (for PDF, images, video, audio).
     */
    public function previewFile($id)
    {
        $document = SimandatDocument::findOrFail($id);

        $user = Auth::user();
        if (!$document->canAccess($user)) {
            abort(403, 'Akses Ditolak: Dokumen bersifat Rahasia.');
        }

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            $path = Storage::disk('public')->path($document->file_path);
            $mime = Storage::disk('public')->mimeType($document->file_path);
            return response()->file($path, ['Content-Type' => $mime]);
        }

        // If file physically doesn't exist on disk (mock/seeder data), return a generated PDF or plain preview
        return response("Pratinjau konten dokumen: {$document->title} (No: {$document->document_number})", 200, [
            'Content-Type' => 'text/plain; charset=utf-8'
        ]);
    }

    /**
     * Soft delete document with audit trail.
     */
    public function destroy($id)
    {
        $document = SimandatDocument::findOrFail($id);

        // Audit Trail
        ActivityLogger::log(
            'DOCUMENT_DELETE',
            "Menghapus dokumen '{$document->title}' ({$document->document_number}) ke arsip sampah (soft delete)"
        );

        $document->delete();

        return redirect()->route('dokumen.documents.index')
            ->with('success', "Dokumen '{$document->title}' berhasil dipindahkan ke retensi penghapusan (Soft Deleted).");
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $docs = SimandatDocument::whereIn('id', $ids)->get();
            $count = $docs->count();
            foreach ($docs as $doc) {
                ActivityLogger::log('DOCUMENT_BULK_DELETE', "Bulk delete dokumen '{$doc->title}' ({$doc->document_number})");
                $doc->delete();
            }
            return redirect()->route('dokumen.documents.index')
                ->with('success', "{$count} berkas dokumen berhasil dipindahkan ke retensi penghapusan.");
        }
        return redirect()->route('dokumen.documents.index')
            ->with('error', 'Tidak ada dokumen yang dipilih untuk dihapus.');
    }

    /**
     * Export documents to CSV.
     */
    private function exportCsv($documents)
    {
        $filename = 'simandat_dokumen_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($documents) {
            $output = fopen('php://output', 'w');
            fputs($output, "\xEF\xBB\xBF"); // UTF-8 BOM
            fputcsv($output, [
                'ID', 'Nomor Dokumen', 'Judul Dokumen', 'Kategori', 'Bidang / Unit Kerja',
                'Folder', 'Tanggal Dokumen', 'Tahun', 'Tingkat Kerahasiaan', 'Status',
                'Versi Aktif', 'Format Berkas', 'Ukuran Berkas', 'Pengunggah', 'Total Unduhan',
                'Token QR Verifikasi', 'Tautan Verifikasi',
            ]);

            foreach ($documents as $doc) {
                fputcsv($output, [
                    $doc->id,
                    $doc->document_number,
                    $doc->title,
                    $doc->category,
                    $doc->division,
                    $doc->folder ? $doc->folder->name : '-',
                    $doc->document_date ? $doc->document_date->format('d/m/Y') : '-',
                    $doc->year,
                    $doc->confidentiality,
                    $doc->status,
                    $doc->current_version,
                    $doc->file_type,
                    $doc->file_size,
                    $doc->uploader_name,
                    $doc->download_count,
                    $doc->qr_token,
                    $doc->verification_url,
                ]);
            }
            fclose($output);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function generateFallbackDownload($document, $version = null)
    {
        $content = "======================================================================\n";
        $content .= "PEMERINTAH PROVINSI MALUKU\n";
        $content .= "DINAS PENDIDIKAN DAN KEBUDAYAAN\n";
        $content .= "PANGKALAN DATA DINAS PUPR MALUKU (SIMANDAT SIWALIMA)\n";
        $content .= "======================================================================\n\n";
        $content .= "ARSIP DIGITAL RESMI BERSTEMPEL VERIFIKASI QR\n";
        $content .= "----------------------------------------------------------------------\n";
        $content .= "NOMOR SURAT/SK  : " . ($document->document_number ?: 'DOC-' . $document->id) . "\n";
        $content .= "JUDUL DOKUMEN   : " . $document->title . "\n";
        $content .= "KLASIFIKASI     : " . $document->category . "\n";
        $content .= "BIDANG / UNIT   : " . $document->division . "\n";
        $content .= "FOLDER ARSIP    : " . ($document->folder ? $document->folder->name : 'Umum') . "\n";
        $content .= "TANGGAL DOKUMEN : " . ($document->document_date ? $document->document_date->format('d F Y') : '-') . "\n";
        $content .= "TINGKAT AKSES   : " . $document->confidentiality . "\n";
        $content .= "VERSI BERKAS    : " . ($version ? $version->version_number : $document->current_version) . "\n";
        $content .= "PENGUNGGAH      : " . ($version ? $version->uploader_name : $document->uploader_name) . "\n";
        $content .= "TOKEN QR KEASLIAN: " . $document->qr_token . "\n";
        $content .= "URL VERIFIKASI  : " . $document->verification_url . "\n";
        $content .= "----------------------------------------------------------------------\n\n";
        $content .= "DESKRIPSI & RINGKASAN NASKAH:\n";
        $content .= ($document->description ?: 'Naskah berkas resmi tersimpan dalam repositori kearsipan Dinas Pendidikan dan Kebudayaan Provinsi Maluku.') . "\n\n";
        $content .= "KEABSAHAN DIGITAL:\n";
        $content .= "[VALIDATED BY DISDIKBUD PROVINSI MALUKU - DIGITAL REPOSITORY CERTIFIED]\n";
        $content .= "Ambon, " . now()->setTimezone('Asia/Jayapura')->format('d F Y H:i:s') . " WIT\n";

        $downloadName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $document->file_name ?: ('Dokumen_' . $document->id . '.txt'));
        if (!str_ends_with(strtolower($downloadName), '.txt') && !str_ends_with(strtolower($downloadName), '.pdf')) {
            $downloadName .= '.txt';
        }

        return Response::make($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$downloadName}\"",
        ]);
    }

    /**
     * Sinkronisasi dokumen ke Modul Teknik Transmisi & Teknik Studio
     */
    private function syncDocumentToTvriModules(SimandatDocument $document, string $filePath): void
    {
        try {
            $divisionLower = strtolower($document->division ?? '');
            $isTransmisiDoc = str_contains($divisionLower, 'transmisi');
            $isTeknikDoc = str_contains($divisionLower, 'teknik');
            $fileAssetPath = 'storage/' . $filePath;

            if ($isTransmisiDoc) {
                if (class_exists(\App\Models\TvriTransmisiSop::class)) {
                    \App\Models\TvriTransmisiSop::updateOrCreate(
                        ['kode' => $document->document_number ?: ('DOC-TX-' . $document->id)],
                        [
                            'judul' => $document->title,
                            'kategori' => $document->category ?: 'Dokumen Laporan Transmisi',
                            'versi' => $document->current_version ?: 'v1.0',
                            'status' => 'Aktif',
                            'keterangan' => $document->description ?: ($document->category . ' - ' . $document->division),
                            'file_path' => $fileAssetPath,
                            'file_url' => $fileAssetPath,
                            'urutan' => 0,
                        ]
                    );
                }
            }

            if ($isTeknikDoc || $isTransmisiDoc) {
                if (class_exists(\App\Models\TvriTeknikSop::class)) {
                    \App\Models\TvriTeknikSop::updateOrCreate(
                        ['kode' => $document->document_number ?: ('DOC-TEK-' . $document->id)],
                        [
                            'judul' => $document->title,
                            'kategori' => $document->category ?: ($isTransmisiDoc ? 'Dokumen Laporan Transmisi' : 'Dokumen Teknik'),
                            'versi' => $document->current_version ?: 'v1.0',
                            'status' => 'Aktif',
                            'keterangan' => $document->description ?: ($document->category . ' - ' . $document->division),
                            'file_path' => $fileAssetPath,
                            'file_url' => $fileAssetPath,
                            'tanggal_efektif' => $document->document_date ?? now(),
                            'urutan' => 0,
                        ]
                    );
                }
            }
        } catch (\Throwable $e) {
            // Non-blocking sync
        }
    }

    private function formatBytes($bytes, $precision = 1)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
