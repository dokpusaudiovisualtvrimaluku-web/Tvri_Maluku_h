<?php

namespace App\Http\Controllers\Tvri;

use App\Http\Controllers\Controller;
use App\Models\TvriTransmisiRingkasan;
use App\Models\TvriTransmisiPemeliharaan;
use App\Models\TvriTransmisiLog;
use App\Models\TvriTransmisiSop;
use App\Models\TvriStasiunPemancar;
use App\Models\TvriGangguan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransmisiController extends Controller
{
    private function getRingkasan(): TvriTransmisiRingkasan
    {
        $ringkasan = TvriTransmisiRingkasan::first();
        if (!$ringkasan) {
            $seeder = new \Database\Seeders\TvriTransmisiDataSeeder();
            $seeder->run();
            $ringkasan = TvriTransmisiRingkasan::first();
        }
        return $ringkasan;
    }

    public function index()
    {
        $ringkasan = $this->getRingkasan();

        $sites = TvriStasiunPemancar::orderBy('status', 'asc')->get();
        $siteTotal = $sites->count() ?: $ringkasan->total_site;
        $siteOnAir = $sites->where('status', 'On-Air')->count();
        $siteGangguan = $sites->where('status', 'Gangguan')->count();
        $siteMaintenance = $sites->where('status', 'Maintenance')->count();
        $kualitasRata = $sites->avg('kualitas') ?? $ringkasan->kualitas_rata;
        $totalDaya = (float) $sites->sum('daya_kw');
        $pctOnAir = $siteTotal > 0 ? round($siteOnAir / $siteTotal * 100) : $ringkasan->on_air_pct;

        $gangguans = TvriGangguan::orderBy('id', 'desc')->get();
        $gangguanTotal = $gangguans->count();
        $gangguanAktif = $gangguans->where('status', 'Proses')->count();
        $gangguanSelesai = $gangguans->where('status', 'Selesai')->count();

        $pemeliharaans = TvriTransmisiPemeliharaan::orderByDesc('id')->get();
        $logs = TvriTransmisiLog::orderByDesc('id')->limit(10)->get();
        $transmisiSops = TvriTransmisiSop::orderBy('urutan')->get();

        $dokumenDms = collect();
        if (class_exists(\App\Models\SimandatDocument::class)) {
            try {
                $dokumenDms = \App\Models\SimandatDocument::where(function ($q) {
                    $q->where('division', 'Teknik Transmisi')
                      ->orWhere('division', 'transmisi')
                      ->orWhere('folder_id', 10)
                      ->orWhere('title', 'like', '%transmisi%')
                      ->orWhere('category', 'like', '%transmisi%');
                })->orderByDesc('id')->get();
            } catch (\Throwable $e) {}
        }

        $existingCodes = $transmisiSops->pluck('kode')->filter()->all();
        $existingTitles = $transmisiSops->pluck('judul')->map(fn($t) => strtolower(trim($t)))->all();

        $convertedDms = $dokumenDms->reject(function ($doc) use ($existingCodes, $existingTitles) {
            $codeMatch = $doc->document_number && in_array($doc->document_number, $existingCodes);
            $titleMatch = in_array(strtolower(trim($doc->title)), $existingTitles);
            return $codeMatch || $titleMatch;
        })->map(function ($doc) {
            $filePath = $doc->file_path;
            if ($filePath && !str_starts_with($filePath, 'storage/') && !str_starts_with($filePath, 'http')) {
                $filePath = 'storage/' . $filePath;
            }
            return (object) [
                'id' => $doc->id,
                'kode' => $doc->document_number ?: ('DOC-TX-' . $doc->id),
                'judul' => $doc->title,
                'kategori' => $doc->category ?: 'Dokumen Laporan',
                'versi' => $doc->current_version ?: 'v1.0',
                'status' => $doc->status ?: 'Aktif',
                'file_url' => $filePath,
                'file_path' => $filePath,
                'keterangan' => $doc->description ?: ($doc->category . ' - ' . $doc->division),
                'tanggal_efektif' => $doc->document_date ? \Carbon\Carbon::parse($doc->document_date)->format('Y-m-d') : null,
                'urutan' => 99,
                'is_laporan' => true,
            ];
        });

        $sops = $transmisiSops->concat($convertedDms);
        $dokumenLaporans = $sops;
        $isKetuaTimTransmisi = $this->isKetuaTimTransmisi();

        return view('tvri.transmisi.dashboard', compact(
            'ringkasan',
            'siteTotal', 'siteOnAir', 'siteGangguan', 'siteMaintenance',
            'kualitasRata', 'totalDaya', 'pctOnAir',
            'gangguanTotal', 'gangguanAktif', 'gangguanSelesai',
            'sites', 'gangguans', 'pemeliharaans', 'logs', 'sops', 'dokumenLaporans', 'isKetuaTimTransmisi'
        ));
    }

    private function isKetuaTimTransmisi(): bool
    {
        $user = auth()->user();
        return (bool) ($user && (
            in_array($user->username, ['transmisi', 'teknik.transmisi', 'kepala.stasiun']) ||
            $user->email === 'transmisi@tvri.go.id' ||
            $user->email === 'kepala.stasiun@tvri.go.id' ||
            (method_exists($user, 'hasRole') && ($user->hasRole('Teknik Transmisi') || $user->hasRole('SUPERADMIN') || $user->hasRole('ADMIN') || $user->hasRole('KEPALA_DINAS') || $user->hasRole('Kepala Stasiun'))) ||
            str_contains(strtolower($user->name ?? ''), 'transmisi') ||
            str_contains(strtolower($user->name ?? ''), 'kepala stasiun')
        ));
    }

    public function manage(Request $request)
    {
        if (!$this->isKetuaTimTransmisi()) {
            return redirect()->route('tvri.transmisi.dashboard')
                ->with('error', 'Akses ditolak. Fitur Input Data Transmisi hanya dapat diakses oleh akun Ketua Tim Teknik Transmisi.');
        }

        $ringkasan = $this->getRingkasan();
        $sites = TvriStasiunPemancar::orderBy('id')->get();
        $gangguans = TvriGangguan::orderByDesc('id')->get();
        $pemeliharaans = TvriTransmisiPemeliharaan::orderByDesc('id')->get();
        $logs = TvriTransmisiLog::orderByDesc('id')->get();
        $sops = TvriTransmisiSop::orderBy('urutan')->get();

        $tab = $request->query('tab', 'pemancar');

        return view('tvri.transmisi.manage', compact(
            'ringkasan', 'sites', 'gangguans', 'pemeliharaans', 'logs', 'sops', 'tab'
        ));
    }

    public function updateRingkasan(Request $request)
    {
        $ringkasan = $this->getRingkasan();

        $validated = $request->validate([
            'judul' => 'required|string',
            'slogan' => 'required|string',
            'slogan_tambahan' => 'nullable|string',
            'waktu_status' => 'required|string',
            'periode' => 'required|string',
            'total_site' => 'required|integer',
            'total_site_diff' => 'nullable|string',
            'on_air_pct' => 'required|integer',
            'on_air_target' => 'required|integer',
            'cakupan_wilayah_pct' => 'required|integer',
            'cakupan_diff' => 'nullable|string',
            'kualitas_rata' => 'required|numeric',
            'kualitas_diff' => 'nullable|string',
            'gangguan_aktif' => 'required|integer',
            'gangguan_diff' => 'nullable|string',
            'pemeliharaan_berjalan' => 'required|integer',
            'pemeliharaan_diff' => 'nullable|string',
            'efisiensi_daya_pct' => 'required|integer',
            'efisiensi_diff' => 'nullable|string',
            'footer_slogan' => 'nullable|string',
            'footer_quote' => 'nullable|string',
        ]);

        // 1. Update Status Siaran per Platform
        if ($request->has('platforms')) {
            $platforms = [];
            foreach ($request->input('platforms', []) as $p) {
                if (!empty($p['platform'])) {
                    $platforms[] = [
                        'platform' => $p['platform'],
                        'status' => $p['status'] ?? 'On-Air 100%',
                        'badge' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/30',
                        'ikon' => $p['ikon'] ?? 'tv',
                        'keterangan' => $p['keterangan'] ?? '',
                    ];
                }
            }
            if (!empty($platforms)) {
                $validated['platform_siaran_json'] = $platforms;
            }
        }

        // 2. Update Kualitas Sinyal Nasional & Regional
        if ($request->has('kualitas_sinyal')) {
            $ks = $request->input('kualitas_sinyal');
            $validated['kualitas_sinyal_json'] = [
                'skor' => (float) ($ks['skor'] ?? $validated['kualitas_rata']),
                'predikat' => $ks['predikat'] ?? 'Sangat Baik',
                'breakdown' => [
                    ['bintang' => 5, 'label' => '5 Excellent', 'persen' => (int) ($ks['b5'] ?? 52), 'color' => 'bg-emerald-500'],
                    ['bintang' => 4, 'label' => '4 Good', 'persen' => (int) ($ks['b4'] ?? 36), 'color' => 'bg-cyan-500'],
                    ['bintang' => 3, 'label' => '3 Fair', 'persen' => (int) ($ks['b3'] ?? 9), 'color' => 'bg-amber-500'],
                    ['bintang' => 2, 'label' => '2 Poor', 'persen' => (int) ($ks['b2'] ?? 2), 'color' => 'bg-orange-500'],
                    ['bintang' => 1, 'label' => '1 Bad', 'persen' => (int) ($ks['b1'] ?? 1), 'color' => 'bg-rose-500'],
                ],
            ];
        }

        // 3. Update Parameter Teknis Real-Time
        if ($request->has('params_teknis')) {
            $params = [];
            foreach ($request->input('params_teknis', []) as $pt) {
                if (!empty($pt['param'])) {
                    $status = $pt['status'] ?? 'Normal';
                    $badge = str_contains(strtolower($status), 'baik')
                        ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
                        : 'bg-blue-500/10 text-cyan-300 border border-blue-500/20';
                    $params[] = [
                        'param' => $pt['param'],
                        'nilai' => $pt['nilai'] ?? '-',
                        'status' => $status,
                        'badge' => $badge,
                    ];
                }
            }
            if (!empty($params)) {
                $validated['parameter_teknis_json'] = $params;
            }
        }

        // 4. Update Ketersediaan Infrastruktur
        if ($request->has('infrastruktur')) {
            $inf = $request->input('infrastruktur');
            $validated['infrastruktur_json'] = [
                ['item' => 'Menara Pemancar', 'persen' => (int) ($inf['menara'] ?? 96), 'color' => 'bg-cyan-500'],
                ['item' => 'Antena Pemancar', 'persen' => (int) ($inf['antena'] ?? 98), 'color' => 'bg-emerald-500'],
                ['item' => 'Perangkat TX', 'persen' => (int) ($inf['tx'] ?? 95), 'color' => 'bg-blue-500'],
                ['item' => 'Genset & Power Cadangan', 'persen' => (int) ($inf['genset'] ?? 93), 'color' => 'bg-amber-500'],
                ['item' => 'Catu Daya (UPS & PLN)', 'persen' => (int) ($inf['catu'] ?? 97), 'color' => 'bg-indigo-500'],
            ];
        }

        $ringkasan->update($validated);

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'ringkasan'])
            ->with('success', 'Seluruh Parameter, Telemetri & KPI Teknik Transmisi berhasil diperbarui.');
    }

    public function reseedDefaults()
    {
        $seeder = new \Database\Seeders\TvriTransmisiDataSeeder();
        $seeder->run();

        return redirect()->route('tvri.transmisi.manage')
            ->with('success', 'Seluruh data Teknik Transmisi berhasil disinkronkan sesuai data spesifikasi resmi!');
    }

    // ===== CRUD PEMANCAR =====
    public function storePemancar(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'lokasi' => 'required|string|max:150',
            'kanal' => 'required|string|max:50',
            'frekuensi' => 'required|string|max:50',
            'daya_kw' => 'required|numeric',
            'coverage' => 'nullable|string|max:100',
            'status' => 'required|in:On-Air,Maintenance,Gangguan',
            'kualitas' => 'required|numeric|min:1|max:5',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        TvriStasiunPemancar::create($validated);

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'pemancar'])
            ->with('success', 'Stasiun Pemancar baru berhasil ditambahkan.');
    }

    public function updatePemancar(Request $request, $id)
    {
        $pemancar = TvriStasiunPemancar::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'lokasi' => 'required|string|max:150',
            'kanal' => 'required|string|max:50',
            'frekuensi' => 'required|string|max:50',
            'daya_kw' => 'required|numeric',
            'coverage' => 'nullable|string|max:100',
            'status' => 'required|in:On-Air,Maintenance,Gangguan',
            'kualitas' => 'required|numeric|min:1|max:5',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $pemancar->update($validated);

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'pemancar'])
            ->with('success', "Data Stasiun Pemancar {$pemancar->nama} berhasil diperbarui.");
    }

    public function destroyPemancar($id)
    {
        $pemancar = TvriStasiunPemancar::findOrFail($id);
        $nama = $pemancar->nama;
        $pemancar->delete();

        $count = TvriStasiunPemancar::count();
        TvriTransmisiRingkasan::query()->update(['total_site' => $count]);

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'pemancar'])
            ->with('success', "Stasiun Pemancar {$nama} berhasil dihapus.");
    }

    public function bulkDestroyPemancar(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriStasiunPemancar::whereIn('id', $ids)->delete();
            $count = TvriStasiunPemancar::count();
            TvriTransmisiRingkasan::query()->update(['total_site' => $count]);

            return redirect()->route('tvri.transmisi.manage', ['tab' => 'pemancar'])
                ->with('success', count($ids) . ' Stasiun Pemancar berhasil dihapus.');
        }
        return redirect()->route('tvri.transmisi.manage', ['tab' => 'pemancar'])
            ->with('error', 'Tidak ada data pemancar yang dipilih.');
    }

    // ===== CRUD GANGGUAN =====
    public function storeGangguan(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|string',
            'site' => 'required|string',
            'jenis' => 'required|string',
            'status' => 'required|in:Proses,Selesai',
            'keterangan' => 'nullable|string',
        ]);

        TvriGangguan::create($validated);

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'gangguan'])
            ->with('success', 'Catatan alarm / gangguan transmisi berhasil ditambahkan.');
    }

    public function updateGangguan(Request $request, $id)
    {
        $gangguan = TvriGangguan::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|string',
            'site' => 'required|string',
            'jenis' => 'required|string',
            'status' => 'required|in:Proses,Selesai',
            'keterangan' => 'nullable|string',
        ]);

        $gangguan->update($validated);

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'gangguan'])
            ->with('success', 'Data alarm / gangguan transmisi berhasil diperbarui.');
    }

    public function destroyGangguan($id)
    {
        $gangguan = TvriGangguan::findOrFail($id);
        $gangguan->delete();

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'gangguan'])
            ->with('success', 'Catatan gangguan berhasil dihapus.');
    }

    public function bulkDestroyGangguan(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriGangguan::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.transmisi.manage', ['tab' => 'gangguan'])
                ->with('success', count($ids) . ' Catatan gangguan berhasil dihapus.');
        }
        return redirect()->route('tvri.transmisi.manage', ['tab' => 'gangguan'])
            ->with('error', 'Tidak ada data gangguan yang dipilih.');
    }

    // ===== CRUD PEMELIHARAAN =====
    public function storePemeliharaan(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|string',
            'site' => 'required|string',
            'kegiatan' => 'required|string',
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        TvriTransmisiPemeliharaan::create($validated);

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'pemeliharaan'])
            ->with('success', 'Jadwal pemeliharaan pemancar berhasil ditambahkan.');
    }

    public function updatePemeliharaan(Request $request, $id)
    {
        $item = TvriTransmisiPemeliharaan::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|string',
            'site' => 'required|string',
            'kegiatan' => 'required|string',
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $item->update($validated);

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'pemeliharaan'])
            ->with('success', 'Jadwal pemeliharaan berhasil diperbarui.');
    }

    public function destroyPemeliharaan($id)
    {
        $item = TvriTransmisiPemeliharaan::findOrFail($id);
        $item->delete();

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'pemeliharaan'])
            ->with('success', 'Jadwal pemeliharaan berhasil dihapus.');
    }

    public function bulkDestroyPemeliharaan(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriTransmisiPemeliharaan::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.transmisi.manage', ['tab' => 'pemeliharaan'])
                ->with('success', count($ids) . ' Jadwal pemeliharaan berhasil dihapus.');
        }
        return redirect()->route('tvri.transmisi.manage', ['tab' => 'pemeliharaan'])
            ->with('error', 'Tidak ada data yang dipilih.');
    }

    // ===== CRUD LOG AKTIVITAS =====
    public function storeLog(Request $request)
    {
        $validated = $request->validate([
            'waktu' => 'required|string',
            'site' => 'nullable|string',
            'aktivitas' => 'required|string',
            'user' => 'required|string',
            'status' => 'nullable|string',
        ]);

        TvriTransmisiLog::create($validated);

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'log'])
            ->with('success', 'Log aktivitas transmisi berhasil dicatat.');
    }

    public function updateLog(Request $request, $id)
    {
        $log = TvriTransmisiLog::findOrFail($id);

        $validated = $request->validate([
            'waktu' => 'required|string',
            'site' => 'nullable|string',
            'aktivitas' => 'required|string',
            'user' => 'required|string',
            'status' => 'nullable|string',
        ]);

        $log->update($validated);

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'log'])
            ->with('success', 'Log aktivitas berhasil diperbarui.');
    }

    public function destroyLog($id)
    {
        $log = TvriTransmisiLog::findOrFail($id);
        $log->delete();

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'log'])
            ->with('success', 'Log aktivitas berhasil dihapus.');
    }

    public function bulkDestroyLog(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriTransmisiLog::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.transmisi.manage', ['tab' => 'log'])
                ->with('success', count($ids) . ' Log aktivitas berhasil dihapus.');
        }
        return redirect()->route('tvri.transmisi.manage', ['tab' => 'log'])
            ->with('error', 'Tidak ada log yang dipilih.');
    }

    // ===== CRUD DOKUMEN & SOP =====
    private function syncSopToTeknikAndDms(TvriTransmisiSop $sop): void
    {
        try {
            // 1. Sinkronkan ke Dokumen & SOP Teknik (Dashboard Teknik)
            \App\Models\TvriTeknikSop::updateOrCreate(
                ['kode' => $sop->kode ?: ('SOP-TX-' . $sop->id)],
                [
                    'judul' => $sop->judul,
                    'kategori' => $sop->kategori ?: 'SOP Transmisi',
                    'versi' => $sop->versi ?: 'v1.0',
                    'status' => $sop->status ?: 'Aktif',
                    'keterangan' => $sop->keterangan ?: 'Dokumen resmi Teknik Transmisi',
                    'file_url' => $sop->file_url,
                    'file_path' => $sop->file_path,
                    'urutan' => $sop->urutan ?? 1,
                ]
            );

            // 2. Sinkronkan ke Pangkalan Dokumen Pusat (SimandatDocument) untuk Pimpinan & ECC
            if (class_exists(\App\Models\SimandatDocument::class)) {
                $folder = \App\Models\SimandatDocumentFolder::firstOrCreate(
                    ['id' => 10],
                    ['name' => 'Teknik Transmisi', 'code' => 'FLD-TX', 'icon' => 'broadcast', 'color' => 'cyan']
                );
                \App\Models\SimandatDocument::updateOrCreate(
                    ['document_number' => $sop->kode ?: ('SOP-TX-' . $sop->id)],
                    [
                        'folder_id' => $folder->id,
                        'title' => $sop->judul,
                        'category' => 'SOP & Regulasi Internal',
                        'division' => 'Teknik Transmisi',
                        'file_path' => $sop->file_path,
                        'file_url' => $sop->file_url,
                        'description' => $sop->keterangan,
                        'confidentiality' => 'Internal',
                        'status' => 'AKTIF',
                        'year' => (int) date('Y'),
                        'document_date' => now(),
                        'user_id' => auth()->id() ?? 1,
                    ]
                );
            }
        } catch (\Throwable $e) {
            // Non-blocking sync
        }
    }

    private function deleteSopFromTeknikAndDms(TvriTransmisiSop $sop): void
    {
        try {
            if ($sop->kode) {
                \App\Models\TvriTeknikSop::where('kode', $sop->kode)->delete();
                if (class_exists(\App\Models\SimandatDocument::class)) {
                    \App\Models\SimandatDocument::where('document_number', $sop->kode)->delete();
                }
            }
        } catch (\Throwable $e) {
            // Non-blocking delete
        }
    }

    public function storeSop(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'nullable|string|max:50',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'versi' => 'required|string|max:20',
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        if ($request->hasFile("file")) { $clean = time()."_".preg_replace("/[^a-zA-Z0-9_\.-]/","_", $request->file("file")->getClientOriginalName()); $path = $request->file("file")->storeAs("sops", $clean, "public"); $validated["file_url"] = "storage/".$path; $validated["file_path"] = "storage/".$path; }
        $sop = TvriTransmisiSop::create($validated);
        $this->syncSopToTeknikAndDms($sop);

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'sop'])
            ->with('success', 'Dokumen / SOP Transmisi berhasil ditambahkan dan disinkronkan ke Dashboard Teknik & Executive Command Center.');
    }

    public function updateSop(Request $request, $id)
    {
        $sop = TvriTransmisiSop::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'nullable|string|max:50',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'versi' => 'required|string|max:20',
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        if ($request->hasFile("file")) { $clean = time()."_".preg_replace("/[^a-zA-Z0-9_\.-]/","_", $request->file("file")->getClientOriginalName()); $path = $request->file("file")->storeAs("sops", $clean, "public"); $validated["file_url"] = "storage/".$path; $validated["file_path"] = "storage/".$path; }
        $sop->update($validated);
        $this->syncSopToTeknikAndDms($sop);

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'sop'])
            ->with('success', "Dokumen / SOP {$sop->judul} berhasil diperbarui dan disinkronkan ke Dashboard Teknik & Executive Command Center.");
    }

    public function destroySop($id)
    {
        $sop = TvriTransmisiSop::findOrFail($id);
        $judul = $sop->judul;
        $this->deleteSopFromTeknikAndDms($sop);
        if ($sop->file_path && file_exists(public_path($sop->file_path))) {
            @unlink(public_path($sop->file_path));
        }
        $sop->delete();

        return redirect()->route('tvri.transmisi.manage', ['tab' => 'sop'])
            ->with('success', "Dokumen / SOP {$judul} berhasil dihapus.");
    }

    public function bulkDestroySop(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriTransmisiSop::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.transmisi.manage', ['tab' => 'sop'])
                ->with('success', count($ids) . ' Dokumen / SOP berhasil dihapus.');
        }
        return redirect()->route('tvri.transmisi.manage', ['tab' => 'sop'])
            ->with('error', 'Tidak ada SOP yang dipilih.');
    }
}
