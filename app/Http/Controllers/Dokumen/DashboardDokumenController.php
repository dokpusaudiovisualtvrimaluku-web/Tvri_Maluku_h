<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use App\Models\SimandatDocument;
use App\Models\SimandatDocumentFolder;
use App\Models\SimandatDocumentVersion;
use App\Models\TvriProgramSop;
use App\Models\TvriVisitorStat;
use App\Models\Dataset;
use App\Models\Urusan;
use App\Models\Tag;
use App\Models\Organization;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DashboardDokumenController extends Controller
{
    /**
     * Display Document & Digital Archive Executive Dashboard.
     */
    public function index(Request $request)
    {
        $yearFilter = $request->get('year');
        $divisionFilter = $request->get('division');

        $query = SimandatDocument::query();
        if ($yearFilter) {
            $query->where('year', $yearFilter);
        }
        if ($divisionFilter) {
            $query->where('division', $divisionFilter);
        }

        // 1. Metric Cards
        $totalDocuments = (clone $query)->count();
        $totalFolders = SimandatDocumentFolder::count();
        $totalDownloads = (clone $query)->sum('download_count');
        $totalViews = (clone $query)->sum('view_count');
        $totalVersions = SimandatDocumentVersion::count();
        $activeDocuments = (clone $query)->where('status', 'AKTIF')->count();
        $archivedDocuments = (clone $query)->where('status', 'DIARSIPKAN')->count();
        $publicDocuments = (clone $query)->where('confidentiality', 'PUBLIK')->count();
        $confidentialDocuments = (clone $query)->whereIn('confidentiality', ['TERBATAS', 'RAHASIA'])->count();

        // 2. Chart 1: Distribution by Division / Bidang
        $divisionData = (clone $query)
            ->select('division', DB::raw('count(*) as total'))
            ->groupBy('division')
            ->orderBy('total', 'desc')
            ->get();
        $divisionLabels = $divisionData->pluck('division')->toArray();
        $divisionCounts = $divisionData->pluck('total')->toArray();

        // 3. Chart 2: Distribution by File Format
        $formatData = (clone $query)
            ->select('file_type', DB::raw('count(*) as total'))
            ->groupBy('file_type')
            ->orderBy('total', 'desc')
            ->get();
        $formatLabels = $formatData->pluck('file_type')->toArray();
        $formatCounts = $formatData->pluck('total')->toArray();

        // 4. Chart 3: Distribution by Confidentiality
        $confData = (clone $query)
            ->select('confidentiality', DB::raw('count(*) as total'))
            ->groupBy('confidentiality')
            ->get();
        $confLabels = $confData->pluck('confidentiality')->toArray();
        $confCounts = $confData->pluck('total')->toArray();

        // 5. Chart 4: Distribution by Category
        $catData = (clone $query)
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->limit(7)
            ->get();
        $categoryLabels = $catData->pluck('category')->toArray();
        $categoryCounts = $catData->pluck('total')->toArray();

        // 6. Recent Documents
        $recentDocuments = SimandatDocument::with(['folder', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // 7. Recent Document Activity Logs
        $recentLogs = ActivityLog::with('user')
            ->where(function ($q) {
                $q->where('activity', 'like', '%DOCUMENT%')
                  ->orWhere('activity', 'like', '%FOLDER%')
                  ->orWhere('activity', 'like', '%KEUANGAN%')
                  ->orWhere('description', 'like', '%dokumen%')
                  ->orWhere('description', 'like', '%folder%')
                  ->orWhere('description', 'like', '%arsip%')
                  ->orWhere('description', 'like', '%keuangan%')
                  ->orWhere('description', 'like', '%anggaran%')
                  ->orWhere('description', 'like', '%pnbp%');
            })
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // 8. Standar Operasional Prosedur (SOP) TVRI Maluku
        $sops = Schema::hasTable('tvri_program_sops')
            ? TvriProgramSop::orderBy('urutan', 'asc')->get()
            : collect();

        // 9. Statistik Pengunjung TVRI Maluku Digital Hub
        $visitorStat = TvriVisitorStat::getOrCreate();

        // 10. Master Katalog Data Operasional TVRI Maluku
        $katalogDatasets = Dataset::with(['organization', 'urusan'])->orderBy('id')->get();
        $urusans = \App\Models\Urusan::orderBy('sort_order')->get();

        // Filters options
        $availableYears = SimandatDocument::select('year')->whereNotNull('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $availableDivisions = SimandatDocument::BIDANG_LIST;

        return view('dokumen.dashboard', compact(
            'totalDocuments', 'totalFolders', 'totalDownloads', 'totalViews', 'totalVersions',
            'activeDocuments', 'archivedDocuments', 'publicDocuments', 'confidentialDocuments',
            'divisionLabels', 'divisionCounts',
            'formatLabels', 'formatCounts',
            'confLabels', 'confCounts',
            'categoryLabels', 'categoryCounts',
            'recentDocuments', 'recentLogs',
            'availableYears', 'availableDivisions', 'yearFilter', 'divisionFilter',
            'sops', 'visitorStat', 'katalogDatasets', 'urusans'
        ));
    }

    /**
     * Update Live Visitor Stats (Super Admin Only)
     */
    public function updateVisitorStats(Request $request)
    {
        $request->validate([
            'hari_ini_kunjungan' => 'required|integer|min:0',
            'hari_ini_unik' => 'required|integer|min:0',
            'bulan_ini_kunjungan' => 'required|integer|min:0',
            'bulan_ini_unik' => 'required|integer|min:0',
            'total_kunjungan' => 'required|integer|min:0',
            'total_unik' => 'required|integer|min:0',
        ]);

        $stat = TvriVisitorStat::getOrCreate();
        $stat->update([
            'hari_ini_kunjungan' => $request->hari_ini_kunjungan,
            'hari_ini_unik' => $request->hari_ini_unik,
            'bulan_ini_kunjungan' => $request->bulan_ini_kunjungan,
            'bulan_ini_unik' => $request->bulan_ini_unik,
            'total_kunjungan' => $request->total_kunjungan,
            'total_unik' => $request->total_unik,
            'updated_by' => auth()->user()?->name ?? 'Super Admin',
        ]);

        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'activity' => 'UPDATE_STATISTIK_PENGUNJUNG',
            'description' => 'Super Admin memperbarui data Statistik Pengunjung TVRI Maluku Digital Hub (Hari Ini: ' . number_format($request->hari_ini_kunjungan) . ' / Unik: ' . number_format($request->hari_ini_unik) . ', Bulan Ini: ' . number_format($request->bulan_ini_kunjungan) . ' / Unik: ' . number_format($request->bulan_ini_unik) . ', Total: ' . number_format($request->total_kunjungan) . ' / Unik: ' . number_format($request->total_unik) . ')',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dokumen.dashboard')->with('success', 'Statistik Pengunjung TVRI Maluku Digital Hub berhasil diperbarui dan disinkronkan ke Portal Publik!');
    }

    /**
     * Reset / Clear Visitor Stats to 0 (Super Admin Only)
     */
    public function resetVisitorStats()
    {
        TvriVisitorStat::resetStats();

        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'activity' => 'RESET_STATISTIK_PENGUNJUNG',
            'description' => 'Super Admin mengosongkan / mereset data statistik pengunjung TVRI Maluku Digital Hub ke 0.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('dokumen.dashboard')->with('success', 'Data statistik pengunjung berhasil dikosongkan (mulai dari 0)! Real-time tracker kini akan mencatat kunjungan baru yang masuk.');
    }

    /**
     * Restore Default TVRI Baseline Stats (Super Admin Only)
     */
    public function patokanVisitorStats()
    {
        TvriVisitorStat::applyBaselineStats();

        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'activity' => 'PATOKAN_STATISTIK_PENGUNJUNG',
            'description' => 'Super Admin mengembalikan data statistik pengunjung TVRI Maluku Digital Hub ke data patokan (248 / 5,420 / 168,940).',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('dokumen.dashboard')->with('success', 'Data statistik pengunjung berhasil dikembalikan ke patokan awal TVRI Maluku (248 / 5,420 / 168,940)!');
    }

    /**
     * Public API endpoint for real-time visitor stats
     */
    public function getPublicVisitorStats()
    {
        $stat = TvriVisitorStat::getOrCreate();
        return response()->json($stat->toFormattedArray());
    }

    /**
     * Simpan Perubahan Cepat (Inline Bulk Update dari Tabel Katalog)
     */
    public function bulkUpdateDatasets(Request $request)
    {
        $datasetsData = $request->input('datasets', []);
        $updatedCount = 0;

        foreach ($datasetsData as $id => $data) {
            $dataset = Dataset::find($id);
            if ($dataset) {
                if (isset($data['title']) && trim($data['title']) !== '') {
                    $dataset->title = trim($data['title']);
                }
                if (isset($data['urusan_id'])) {
                    $dataset->urusan_id = (int)$data['urusan_id'];
                }
                if (isset($data['views'])) {
                    $dataset->views = max(0, (int)$data['views']);
                }
                if (isset($data['downloads'])) {
                    $dataset->downloads = max(0, (int)$data['downloads']);
                }
                if (isset($data['date']) && trim($data['date']) !== '') {
                    $dataset->date = trim($data['date']);
                }
                $dataset->save();
                $updatedCount++;
            }
        }

        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'activity' => 'BULK_UPDATE_KATALOG_DATASET',
            'description' => 'Super Admin memperbarui data secara serentak (' . $updatedCount . ' master dataset)',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dokumen.dashboard')->with('success', "Berhasil memperbarui {$updatedCount} dataset katalog operasional secara serentak!");
    }

    /**
     * Pulihkan 6 Master Dataset Bawaan TVRI Maluku
     */
    public function reseedDatasets()
    {
        try {
            $seeder = new \Database\Seeders\DatasetSeeder();
            $seeder->run();

            ActivityLog::create([
                'user_id' => auth()->id() ?? 1,
                'activity' => 'RESEED_KATALOG_DATASET',
                'description' => 'Super Admin memulihkan dataset katalog ke data master bawaan TVRI Maluku',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->route('dokumen.dashboard')->with('success', 'Berhasil memulihkan 6 master dataset bawaan TVRI Maluku!');
        } catch (\Throwable $e) {
            return redirect()->route('dokumen.dashboard')->with('error', 'Gagal memulihkan dataset bawaan: ' . $e->getMessage());
        }
    }

    /**
     * Unduh file CSV dataset katalog operasional
     */
    public function exportDatasetCsv($id)
    {
        $dataset = Dataset::findOrFail($id);
        $filename = ($dataset->slug ?: 'dataset-' . $dataset->id) . '.csv';

        $columns = is_array($dataset->columns) ? $dataset->columns : [];
        $rows = is_array($dataset->rows) ? $dataset->rows : [];

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($columns, $rows) {
            $out = fopen('php://output', 'w');
            // Write UTF-8 BOM for Microsoft Excel compatibility
            fputs($out, "\xEF\xBB\xBF");
            if (!empty($columns)) {
                fputcsv($out, $columns);
            }
            foreach ($rows as $row) {
                $line = [];
                foreach ($columns as $col) {
                    $line[] = $row[$col] ?? '';
                }
                fputcsv($out, $line);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Tambah Dataset Baru ke Katalog Operasional
     */
    public function storeDataset(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'urusan_id' => 'required|integer',
            'date' => 'required|string|max:100',
            'description' => 'nullable|string',
            'views' => 'nullable|integer|min:0',
            'downloads' => 'nullable|integer|min:0',
            'dataset_file' => 'nullable|file|max:15360',
        ]);

        $newId = (Dataset::max('id') ?? 100) + 1;
        $slug = Str::slug($request->slug ?: $request->title);

        $tagsArray = [];
        if ($request->filled('tags')) {
            $tagsArray = is_array($request->tags)
                ? $request->tags
                : array_map('trim', explode(',', (string)$request->tags));
            $tagsArray = array_values(array_filter($tagsArray));
            foreach ($tagsArray as $t) {
                Tag::firstOrCreate(['name' => $t]);
            }
        }

        $columns = ['No', 'Uraian Data', 'Keterangan'];
        $rows = [
            ['No' => '1', 'Uraian Data' => 'Data Sampel 1', 'Keterangan' => 'Keterangan operasional TVRI Maluku']
        ];

        if ($request->hasFile('dataset_file')) {
            $err = $this->parseDatasetFile($request->file('dataset_file'), $columns, $rows);
            if ($err) {
                return redirect()->back()->with('error', 'Gagal memproses file data: ' . $err);
            }
        } elseif ($request->filled('columns') && $request->filled('rows')) {
            $parsedCols = is_array($request->columns) ? $request->columns : json_decode($request->columns, true);
            $parsedRows = is_array($request->rows) ? $request->rows : json_decode($request->rows, true);
            if (is_array($parsedCols) && !empty($parsedCols)) {
                $columns = $parsedCols;
            }
            if (is_array($parsedRows) && !empty($parsedRows)) {
                $rows = $parsedRows;
            }
        } elseif ($request->filled('raw_data_json')) {
            $this->parseRawJson($request->raw_data_json, $columns, $rows);
        }

        $dataset = Dataset::create([
            'id' => $newId,
            'slug' => $slug,
            'title' => $request->title,
            'organization_id' => 'tvri-maluku',
            'urusan_id' => (int)$request->urusan_id,
            'date' => $request->date,
            'views' => (int)($request->views ?? 0),
            'downloads' => (int)($request->downloads ?? 0),
            'description' => $request->description ?? '',
            'tags' => $tagsArray,
            'columns' => $columns,
            'rows' => $rows,
            'chart_config' => null,
        ]);

        // Sinkronisasi counter dataset pada organisasi
        Organization::where('id', 'tvri-maluku')->update([
            'dataset_count' => Dataset::count(),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'activity' => 'TAMBAH_KATALOG_DATASET',
            'description' => 'Super Admin menambahkan master dataset baru: "' . $dataset->title . '" (' . count($rows) . ' baris data)',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dokumen.dashboard')->with('success', 'Dataset "' . $dataset->title . '" berhasil ditambahkan dan disajikan di Portal Publik!');
    }

    /**
     * Update Dataset & Ganti File Data Operasional
     */
    public function updateDataset(Request $request, $id)
    {
        $dataset = Dataset::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'urusan_id' => 'required|integer',
            'date' => 'required|string|max:100',
            'description' => 'nullable|string',
            'views' => 'nullable|integer|min:0',
            'downloads' => 'nullable|integer|min:0',
            'dataset_file' => 'nullable|file|max:15360',
        ]);

        $dataset->title = $request->title;
        if ($request->filled('slug')) {
            $dataset->slug = Str::slug($request->slug);
        }
        $dataset->urusan_id = (int)$request->urusan_id;
        $dataset->date = $request->date;
        if ($request->has('description')) {
            $dataset->description = $request->description ?? '';
        }
        if ($request->has('views')) {
            $dataset->views = (int)$request->views;
        }
        if ($request->has('downloads')) {
            $dataset->downloads = (int)$request->downloads;
        }

        if ($request->has('tags')) {
            $tagsArray = is_array($request->tags)
                ? $request->tags
                : array_map('trim', explode(',', (string)$request->tags));
            $tagsArray = array_values(array_filter($tagsArray));
            $dataset->tags = $tagsArray;
            foreach ($tagsArray as $t) {
                Tag::firstOrCreate(['name' => $t]);
            }
        }

        $fileChanged = false;
        if ($request->hasFile('dataset_file')) {
            $columns = [];
            $rows = [];
            $err = $this->parseDatasetFile($request->file('dataset_file'), $columns, $rows);
            if ($err) {
                return redirect()->back()->with('error', 'Gagal memproses file data baru: ' . $err);
            }
            if (!empty($columns) && !empty($rows)) {
                $dataset->columns = $columns;
                $dataset->rows = $rows;
                $fileChanged = true;
            }
        } elseif ($request->filled('columns') && $request->filled('rows')) {
            $parsedCols = is_array($request->columns) ? $request->columns : json_decode($request->columns, true);
            $parsedRows = is_array($request->rows) ? $request->rows : json_decode($request->rows, true);
            if (is_array($parsedCols) && !empty($parsedCols)) {
                $dataset->columns = $parsedCols;
                $fileChanged = true;
            }
            if (is_array($parsedRows) && !empty($parsedRows)) {
                $dataset->rows = $parsedRows;
                $fileChanged = true;
            }
        } elseif ($request->filled('raw_data_json')) {
            $columns = [];
            $rows = [];
            if ($this->parseRawJson($request->raw_data_json, $columns, $rows)) {
                $dataset->columns = $columns;
                $dataset->rows = $rows;
                $fileChanged = true;
            }
        }

        $dataset->save();

        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'activity' => 'UPDATE_KATALOG_DATASET',
            'description' => 'Super Admin memperbarui data Katalog Dataset: "' . $dataset->title . '"' . ($fileChanged ? ' (File & isi data diperbarui: ' . count($dataset->rows) . ' baris)' : ''),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $msg = 'Dataset "' . $dataset->title . '" berhasil diperbarui' . ($fileChanged ? ' beserta penggantian file data (' . count($dataset->rows) . ' baris) yang langsung tampil di Halaman Utama Portal Publik!' : ' dan disinkronkan ke Halaman Utama Portal Publik!');

        return redirect()->route('dokumen.dashboard')->with('success', $msg);
    }

    /**
     * Hapus Dataset dari Katalog Operasional
     */
    public function destroyDataset($id)
    {
        $dataset = Dataset::findOrFail($id);
        $title = $dataset->title;
        $dataset->delete();

        Organization::where('id', 'tvri-maluku')->update([
            'dataset_count' => Dataset::count(),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'activity' => 'HAPUS_KATALOG_DATASET',
            'description' => 'Super Admin menghapus master dataset: "' . $title . '"',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('dokumen.dashboard')->with('success', 'Dataset "' . $title . '" berhasil dihapus dari sistem dan katalog portal publik.');
    }

    /**
     * Helper: Parsing file CSV, XLSX, XLS, atau JSON menjadi kolom dan baris data
     */
    private function parseDatasetFile($file, &$columns, &$rows): ?string
    {
        $ext = strtolower($file->getClientOriginalExtension());

        // 1. JSON File
        if ($ext === 'json') {
            $content = file_get_contents($file->getRealPath());
            $data = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return 'Format JSON tidak valid: ' . json_last_error_msg();
            }
            if (isset($data['columns']) && isset($data['rows']) && is_array($data['columns']) && is_array($data['rows'])) {
                $columns = $data['columns'];
                $rows = $data['rows'];
                return null;
            }
            if (is_array($data) && count($data) > 0) {
                $first = reset($data);
                if (is_array($first)) {
                    $columns = array_keys($first);
                    $rows = array_values($data);
                    return null;
                }
            }
            return 'Struktur data JSON tidak memiliki array baris atau kolom yang sesuai.';
        }

        // 2. Excel (XLSX, XLS)
        if (in_array($ext, ['xlsx', 'xls'])) {
            try {
                $sheets = \Maatwebsite\Excel\Facades\Excel::toArray(new class implements \Maatwebsite\Excel\Concerns\ToArray {
                    public function array(array $array) { return $array; }
                }, $file);

                if (empty($sheets) || empty($sheets[0])) {
                    return 'File Excel kosong atau tidak terbaca.';
                }
                $sheet = $sheets[0];
                $headerRow = array_shift($sheet);
                $colHeaders = [];
                foreach ($headerRow as $idx => $val) {
                    $trimmed = trim((string)$val);
                    if ($trimmed !== '') {
                        $colHeaders[$idx] = $trimmed;
                    }
                }
                if (empty($colHeaders)) {
                    return 'Baris pertama Excel tidak memiliki header kolom yang valid.';
                }

                $rowList = [];
                foreach ($sheet as $line) {
                    $rowObj = [];
                    $hasData = false;
                    foreach ($colHeaders as $idx => $cName) {
                        $cVal = isset($line[$idx]) ? trim((string)$line[$idx]) : '';
                        if ($cVal !== '') {
                            $hasData = true;
                        }
                        $rowObj[$cName] = $cVal;
                    }
                    if ($hasData) {
                        $rowList[] = $rowObj;
                    }
                }
                $columns = array_values($colHeaders);
                $rows = $rowList;
                return null;
            } catch (\Throwable $e) {
                return 'Gagal membaca file Excel: ' . $e->getMessage();
            }
        }

        // 3. CSV / TXT File
        $content = file_get_contents($file->getRealPath());
        if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
            $content = substr($content, 3);
        }
        $lines = preg_split("/\r\n|\n|\r/", trim($content));
        if (empty($lines)) {
            return 'File CSV kosong.';
        }

        $firstLine = $lines[0];
        $delimiters = [',', ';', "\t"];
        $bestDelimiter = ',';
        $maxCount = 0;
        foreach ($delimiters as $delim) {
            $cnt = substr_count($firstLine, $delim);
            if ($cnt > $maxCount) {
                $maxCount = $cnt;
                $bestDelimiter = $delim;
            }
        }

        $parsedLines = [];
        foreach ($lines as $line) {
            if (trim($line) === '') continue;
            $parsedLines[] = str_getcsv($line, $bestDelimiter);
        }
        if (empty($parsedLines)) {
            return 'Tidak ada baris data yang valid di dalam CSV.';
        }

        $headerRow = array_shift($parsedLines);
        $colHeaders = [];
        foreach ($headerRow as $idx => $name) {
            $tName = trim((string)$name);
            if ($tName !== '') {
                $colHeaders[$idx] = $tName;
            }
        }
        if (empty($colHeaders)) {
            return 'Baris pertama file CSV harus berupa nama kolom (header).';
        }

        $rowList = [];
        foreach ($parsedLines as $line) {
            $rowObj = [];
            $hasData = false;
            foreach ($colHeaders as $idx => $cName) {
                $cVal = isset($line[$idx]) ? trim((string)$line[$idx]) : '';
                if ($cVal !== '') {
                    $hasData = true;
                }
                $rowObj[$cName] = $cVal;
            }
            if ($hasData) {
                $rowList[] = $rowObj;
            }
        }

        $columns = array_values($colHeaders);
        $rows = $rowList;
        return null;
    }

    /**
     * Helper: Parsing raw JSON text
     */
    private function parseRawJson($rawJson, &$columns, &$rows): bool
    {
        $data = json_decode($rawJson, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        if (isset($data['columns']) && isset($data['rows']) && is_array($data['columns']) && is_array($data['rows'])) {
            $columns = $data['columns'];
            $rows = $data['rows'];
            return true;
        }
        if (is_array($data) && count($data) > 0) {
            $first = reset($data);
            if (is_array($first)) {
                $columns = array_keys($first);
                $rows = array_values($data);
                return true;
            }
        }
        return false;
    }
}
