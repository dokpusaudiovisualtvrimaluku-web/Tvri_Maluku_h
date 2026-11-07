<?php

namespace App\Http\Controllers\Tvri;

use App\Http\Controllers\Controller;
use App\Models\TvriTeknikRingkasan;
use App\Models\TvriTeknikJadwal;
use App\Models\TvriTeknikMaintenance;
use App\Models\TvriTeknikInsiden;
use App\Models\TvriTeknikTransmitter;
use App\Models\TvriTeknikSop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TeknikController extends Controller
{
    private function getRingkasan(): TvriTeknikRingkasan
    {
        $ringkasan = TvriTeknikRingkasan::first();
        if (!$ringkasan || TvriTeknikJadwal::count() === 0 || TvriTeknikSop::count() === 0) {
            $seeder = new \Database\Seeders\TvriTeknikDataSeeder();
            $seeder->run();
            $ringkasan = TvriTeknikRingkasan::first();
        }
        return $ringkasan;
    }

    private function getMergedSops()
    {
        $teknikSops = TvriTeknikSop::orderBy('urutan')->get();
        $transmisiSops = \App\Models\TvriTransmisiSop::orderBy('urutan')->get();
        $sops = $teknikSops->concat($transmisiSops->reject(function ($ts) use ($teknikSops) {
            return $teknikSops->contains('kode', $ts->kode);
        }));

        if (class_exists(\App\Models\SimandatDocument::class)) {
            try {
                $dokumenDms = \App\Models\SimandatDocument::where(function ($q) {
                    $q->whereIn('division', ['Teknik', 'Teknik Studio', 'Teknik Transmisi', 'transmisi'])
                      ->orWhereIn('folder_id', [9, 10])
                      ->orWhere('title', 'like', '%teknik%')
                      ->orWhere('title', 'like', '%transmisi%')
                      ->orWhere('category', 'like', '%teknik%')
                      ->orWhere('category', 'like', '%transmisi%');
                })->orderByDesc('id')->get();

                $existingCodes = $sops->pluck('kode')->filter()->all();
                $existingTitles = $sops->pluck('judul')->map(fn($t) => strtolower(trim($t)))->all();

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
                        'kode' => $doc->document_number ?: ('DOC-TEK-' . $doc->id),
                        'judul' => $doc->title,
                        'kategori' => $doc->category ?: 'Dokumen Laporan Teknik',
                        'versi' => $doc->current_version ?: 'v1.0',
                        'status' => $doc->status ?: 'Aktif',
                        'file_url' => $filePath,
                        'file_path' => $filePath,
                        'keterangan' => $doc->description ?: ($doc->category . ' - ' . $doc->division),
                        'tanggal_efektif' => $doc->document_date ? \Carbon\Carbon::parse($doc->document_date)->format('Y-m-d') : null,
                        'urutan' => 99,
                    ];
                });
                $sops = $sops->concat($convertedDms);
            } catch (\Throwable $e) {}
        }

        return $sops->sortBy('urutan');
    }

    public function index()
    {
        $ringkasan = $this->getRingkasan();

        $jadwals = TvriTeknikJadwal::orderBy('urutan')->get();
        $maintenances = TvriTeknikMaintenance::orderByDesc('tanggal')->get();
        $insidens = TvriTeknikInsiden::orderByDesc('id')->get();
        $transmitters = TvriTeknikTransmitter::all();
        $sops = $this->getMergedSops();

        // 1. Ringkasan Kinerja Utama (KPI)
        $kpi = [
            'on_air' => $ringkasan->on_air_pct,
            'transmitter_on' => $ringkasan->transmitter_on,
            'transmitter_total' => $ringkasan->transmitter_total,
            'studio_ready' => $ringkasan->studio_ready,
            'studio_total' => $ringkasan->studio_total,
            'peralatan_kritis_pct' => $ringkasan->peralatan_kritis_pct,
            'insiden_hari_ini' => $ringkasan->insiden_hari_ini,
            'insiden_diff' => $ringkasan->insiden_diff,
            'maintenance_pct' => $ringkasan->maintenance_pct,
            'maintenance_target' => $ringkasan->maintenance_target,
            'efisiensi_energi_pct' => $ringkasan->efisiensi_energi_pct,
            'efisiensi_diff' => $ringkasan->efisiensi_diff,
        ];

        // 2. Status Siaran Saat Ini (LIVE)
        $siaranSaatIni = [
            'program' => $ringkasan->siaran_program,
            'tagline' => $ringkasan->siaran_tagline,
            'is_live' => true,
            'sumber' => $ringkasan->siaran_sumber,
            'resolusi' => $ringkasan->siaran_resolusi,
            'audio' => $ringkasan->siaran_audio,
            'bitrate' => $ringkasan->siaran_bitrate,
            'durasi' => $ringkasan->siaran_durasi,
            'status' => $ringkasan->siaran_status,
            'running_text' => $ringkasan->running_text,
        ];

        // 3. Technical Readiness Index
        $readiness = [
            'overall' => $ringkasan->readiness_overall,
            'predikat' => $ringkasan->readiness_predikat,
            'items' => is_array($ringkasan->readiness_items_json) ? $ringkasan->readiness_items_json : [
                ['aspek' => 'Transmisi', 'skor' => 96, 'color' => 'bg-cyan-500'],
                ['aspek' => 'Studio', 'skor' => 100, 'color' => 'bg-emerald-500'],
                ['aspek' => 'IT & Network', 'skor' => 92, 'color' => 'bg-blue-500'],
                ['aspek' => 'Power & Genset', 'skor' => 90, 'color' => 'bg-amber-500'],
                ['aspek' => 'Peralatan Produksi', 'skor' => 93, 'color' => 'bg-indigo-500'],
                ['aspek' => 'Infrastruktur', 'skor' => 95, 'color' => 'bg-teal-500'],
            ]
        ];

        // 4. Status Studio
        $statusStudio = is_array($ringkasan->status_studio_json) ? $ringkasan->status_studio_json : [
            ['nama' => 'Studio 1', 'status' => 'ON AIR', 'badge' => 'bg-rose-500 text-white animate-pulse', 'keterangan' => 'Siaran Langsung Maluku Hari Ini'],
            ['nama' => 'Studio 2', 'status' => 'Siap Produksi (Siap)', 'badge' => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20', 'keterangan' => 'Standby untuk Dialog Maluku'],
            ['nama' => 'Studio 3', 'status' => 'Siap Produksi (Siap)', 'badge' => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20', 'keterangan' => 'Standby untuk Program Budaya'],
            ['nama' => 'Studio 4', 'status' => 'Maintenance (Dalam Perbaikan)', 'badge' => 'bg-amber-500/10 text-amber-700 border border-amber-500/20', 'keterangan' => 'Inspeksi & penanganan audio'],
        ];

        // 5. Inventaris Peralatan Utama
        $peralatans = is_array($ringkasan->inventaris_json) ? $ringkasan->inventaris_json : [
            ['nama' => 'Kamera', 'kategori' => 'Produksi', 'total' => 24, 'baik' => 21, 'status_label' => 'Baik: 21', 'persen' => 88],
            ['nama' => 'Perangkat Editing', 'kategori' => 'Pasca Produksi', 'total' => 12, 'baik' => 11, 'status_label' => 'Baik: 11', 'persen' => 92],
            ['nama' => 'Server & Storage', 'kategori' => 'IT Data Center', 'total' => 8, 'baik' => 7, 'status_label' => 'Baik: 7', 'persen' => 88],
            ['nama' => 'Perangkat Transmisi', 'kategori' => 'Transmisi', 'total' => 16, 'baik' => 15, 'status_label' => 'Baik: 15', 'persen' => 94],
            ['nama' => 'Genset', 'kategori' => 'Power & Utility', 'total' => 6, 'baik' => 5, 'status_label' => 'Baik: 5', 'persen' => 83],
            ['nama' => 'Software Lisensi', 'kategori' => 'IT & Broadcast', 'total' => 12, 'baik' => 12, 'status_label' => 'Aktif: 12', 'persen' => 100],
        ];

        // Metadata Header
        $meta = [
            'waktu' => $ringkasan->waktu_status,
            'status' => 'Online',
            'periode' => $ringkasan->periode,
        ];

        return view('tvri.teknik.dashboard', compact(
            'ringkasan', 'kpi', 'siaranSaatIni', 'readiness', 'statusStudio',
            'jadwals', 'maintenances', 'insidens', 'transmitters', 'peralatans', 'sops', 'meta'
        ));
    }

    public function manage(Request $request)
    {
        $ringkasan = $this->getRingkasan();
        $jadwals = TvriTeknikJadwal::orderBy('urutan')->get();
        $maintenances = TvriTeknikMaintenance::orderByDesc('tanggal')->get();
        $insidens = TvriTeknikInsiden::orderByDesc('id')->get();
        $transmitters = TvriTeknikTransmitter::all();
        $sops = $this->getMergedSops();

        $tab = $request->query('tab', 'jadwal');

        return view('tvri.teknik.manage', compact(
            'ringkasan',
            'jadwals',
            'maintenances',
            'insidens',
            'transmitters',
            'sops',
            'tab'
        ));
    }

    public function updateRingkasan(Request $request)
    {
        $ringkasan = $this->getRingkasan();
        $data = $request->except(['_token']);

        if ($request->has('footer_points_text')) {
            $data['footer_points_json'] = array_filter(array_map('trim', explode("\n", $request->input('footer_points_text'))));
            unset($data['footer_points_text']);
        }

        // Update Readiness 6 Aspek
        if ($request->has('readiness_transmisi')) {
            $data['readiness_items_json'] = [
                ['aspek' => 'Transmisi', 'skor' => (int) $request->input('readiness_transmisi', 96), 'color' => 'bg-cyan-500'],
                ['aspek' => 'Studio', 'skor' => (int) $request->input('readiness_studio', 100), 'color' => 'bg-emerald-500'],
                ['aspek' => 'IT & Network', 'skor' => (int) $request->input('readiness_it', 92), 'color' => 'bg-blue-500'],
                ['aspek' => 'Power & Genset', 'skor' => (int) $request->input('readiness_power', 90), 'color' => 'bg-amber-500'],
                ['aspek' => 'Peralatan Produksi', 'skor' => (int) $request->input('readiness_peralatan', 93), 'color' => 'bg-indigo-500'],
                ['aspek' => 'Infrastruktur', 'skor' => (int) $request->input('readiness_infrastruktur', 95), 'color' => 'bg-teal-500'],
            ];
            unset($data['readiness_transmisi'], $data['readiness_studio'], $data['readiness_it'], $data['readiness_power'], $data['readiness_peralatan'], $data['readiness_infrastruktur']);
        }

        // Update Status Studio 1 - 4
        if ($request->has('studio_1_status')) {
            $data['status_studio_json'] = [
                ['nama' => 'Studio 1', 'status' => $request->input('studio_1_status', 'ON AIR'), 'badge' => 'bg-rose-500 text-white animate-pulse', 'keterangan' => $request->input('studio_1_ket', 'Siaran Langsung Maluku Hari Ini')],
                ['nama' => 'Studio 2', 'status' => $request->input('studio_2_status', 'Siap Produksi (Siap)'), 'badge' => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20', 'keterangan' => $request->input('studio_2_ket', 'Standby untuk Dialog Maluku')],
                ['nama' => 'Studio 3', 'status' => $request->input('studio_3_status', 'Siap Produksi (Siap)'), 'badge' => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20', 'keterangan' => $request->input('studio_3_ket', 'Standby untuk Program Budaya')],
                ['nama' => 'Studio 4', 'status' => $request->input('studio_4_status', 'Maintenance (Dalam Perbaikan)'), 'badge' => 'bg-amber-500/10 text-amber-700 border border-amber-500/20', 'keterangan' => $request->input('studio_4_ket', 'Inspeksi & penanganan audio tidak terdeteksi')],
            ];
            unset($data['studio_1_status'], $data['studio_1_ket'], $data['studio_2_status'], $data['studio_2_ket'], $data['studio_3_status'], $data['studio_3_ket'], $data['studio_4_status'], $data['studio_4_ket']);
        }

        // Update Inventaris Peralatan Utama
        if ($request->has('inv_kamera_total')) {
            $kameraTot = (int) $request->input('inv_kamera_total', 24);
            $kameraBaik = (int) $request->input('inv_kamera_baik', 21);
            $editTot = (int) $request->input('inv_editing_total', 12);
            $editBaik = (int) $request->input('inv_editing_baik', 11);
            $srvTot = (int) $request->input('inv_server_total', 8);
            $srvBaik = (int) $request->input('inv_server_baik', 7);
            $txTot = (int) $request->input('inv_tx_total', 16);
            $txBaik = (int) $request->input('inv_tx_baik', 15);
            $genTot = (int) $request->input('inv_genset_total', 6);
            $genBaik = (int) $request->input('inv_genset_baik', 5);
            $softTot = (int) $request->input('inv_software_total', 12);
            $softBaik = (int) $request->input('inv_software_baik', 12);

            $data['inventaris_json'] = [
                ['nama' => 'Kamera', 'kategori' => 'Produksi', 'total' => $kameraTot, 'baik' => $kameraBaik, 'status_label' => 'Baik: ' . $kameraBaik, 'persen' => $kameraTot > 0 ? round($kameraBaik / $kameraTot * 100) : 0],
                ['nama' => 'Perangkat Editing', 'kategori' => 'Pasca Produksi', 'total' => $editTot, 'baik' => $editBaik, 'status_label' => 'Baik: ' . $editBaik, 'persen' => $editTot > 0 ? round($editBaik / $editTot * 100) : 0],
                ['nama' => 'Server & Storage', 'kategori' => 'IT Data Center', 'total' => $srvTot, 'baik' => $srvBaik, 'status_label' => 'Baik: ' . $srvBaik, 'persen' => $srvTot > 0 ? round($srvBaik / $srvTot * 100) : 0],
                ['nama' => 'Perangkat Transmisi', 'kategori' => 'Transmisi', 'total' => $txTot, 'baik' => $txBaik, 'status_label' => 'Baik: ' . $txBaik, 'persen' => $txTot > 0 ? round($txBaik / $txTot * 100) : 0],
                ['nama' => 'Genset', 'kategori' => 'Power & Utility', 'total' => $genTot, 'baik' => $genBaik, 'status_label' => 'Baik: ' . $genBaik, 'persen' => $genTot > 0 ? round($genBaik / $genTot * 100) : 0],
                ['nama' => 'Software Lisensi', 'kategori' => 'IT & Broadcast', 'total' => $softTot, 'baik' => $softBaik, 'status_label' => 'Aktif: ' . $softBaik, 'persen' => $softTot > 0 ? round($softBaik / $softTot * 100) : 0],
            ];

            unset(
                $data['inv_kamera_total'], $data['inv_kamera_baik'],
                $data['inv_editing_total'], $data['inv_editing_baik'],
                $data['inv_server_total'], $data['inv_server_baik'],
                $data['inv_tx_total'], $data['inv_tx_baik'],
                $data['inv_genset_total'], $data['inv_genset_baik'],
                $data['inv_software_total'], $data['inv_software_baik']
            );
        }

        $ringkasan->update($data);

        return redirect()->route('tvri.teknik.manage', ['tab' => 'ringkasan'])->with('success', 'Seluruh parameter KPI, Sistem, Indeks Kesiapan & Inventaris Teknik berhasil diperbarui.');
    }

    public function reseedDefaults()
    {
        Artisan::call('db:seed', ['--class' => 'TvriTeknikDataSeeder']);
        return redirect()->route('tvri.teknik.manage')->with('success', 'Data Bidang Teknik Studio berhasil dipulihkan sesuai spesifikasi resmi.');
    }

    // ================= CRUD JADWAL PRODUKSI =================
    public function storeJadwal(Request $request)
    {
        $request->validate([
            'waktu' => 'required|string|max:50',
            'program' => 'required|string|max:255',
            'lokasi' => 'required|string|max:100',
            'kebutuhan' => 'required|string|max:255',
            'status' => 'required|string|max:50',
        ]);

        $badge = 'bg-amber-50 text-amber-700 border border-amber-200';
        if ($request->status === 'Selesai') $badge = 'bg-slate-100 text-slate-700';
        elseif ($request->status === 'Sedang Berjalan') $badge = 'bg-emerald-50 text-emerald-700 border border-emerald-200 animate-pulse';
        elseif ($request->status === 'Siap') $badge = 'bg-blue-50 text-blue-700 border border-blue-200';

        $data = $request->all();
        $data['badge'] = $badge;
        $data['urutan'] = TvriTeknikJadwal::count() + 1;

        TvriTeknikJadwal::create($data);

        return redirect()->route('tvri.teknik.manage', ['tab' => 'jadwal'])->with('success', 'Jadwal produksi & teknik berhasil ditambahkan.');
    }

    public function updateJadwal(Request $request, $id)
    {
        $request->validate([
            'waktu' => 'required|string|max:50',
            'program' => 'required|string|max:255',
            'lokasi' => 'required|string|max:100',
            'kebutuhan' => 'required|string|max:255',
            'status' => 'required|string|max:50',
        ]);

        $badge = 'bg-amber-50 text-amber-700 border border-amber-200';
        if ($request->status === 'Selesai') $badge = 'bg-slate-100 text-slate-700';
        elseif ($request->status === 'Sedang Berjalan') $badge = 'bg-emerald-50 text-emerald-700 border border-emerald-200 animate-pulse';
        elseif ($request->status === 'Siap') $badge = 'bg-blue-50 text-blue-700 border border-blue-200';

        $jadwal = TvriTeknikJadwal::findOrFail($id);
        $data = $request->all();
        $data['badge'] = $badge;
        $jadwal->update($data);

        return redirect()->route('tvri.teknik.manage', ['tab' => 'jadwal'])->with('success', 'Jadwal produksi berhasil diperbarui.');
    }

    public function destroyJadwal($id)
    {
        TvriTeknikJadwal::findOrFail($id)->delete();
        return redirect()->route('tvri.teknik.manage', ['tab' => 'jadwal'])->with('success', 'Jadwal berhasil dihapus.');
    }

    public function bulkDestroyJadwal(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriTeknikJadwal::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.teknik.manage', ['tab' => 'jadwal'])->with('success', count($ids) . ' jadwal berhasil dihapus.');
        }
        return redirect()->route('tvri.teknik.manage', ['tab' => 'jadwal'])->with('error', 'Tidak ada jadwal yang dipilih.');
    }

    // ================= CRUD MAINTENANCE =================
    public function storeMaintenance(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|string|max:50',
            'peralatan' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'pic' => 'required|string|max:100',
            'status' => 'required|string|max:50',
        ]);

        TvriTeknikMaintenance::create($request->all());

        return redirect()->route('tvri.teknik.manage', ['tab' => 'maintenance'])->with('success', 'Data maintenance peralatan berhasil ditambahkan.');
    }

    public function updateMaintenance(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|string|max:50',
            'peralatan' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'pic' => 'required|string|max:100',
            'status' => 'required|string|max:50',
        ]);

        $m = TvriTeknikMaintenance::findOrFail($id);
        $m->update($request->all());

        return redirect()->route('tvri.teknik.manage', ['tab' => 'maintenance'])->with('success', 'Data maintenance berhasil diperbarui.');
    }

    public function destroyMaintenance($id)
    {
        TvriTeknikMaintenance::findOrFail($id)->delete();
        return redirect()->route('tvri.teknik.manage', ['tab' => 'maintenance'])->with('success', 'Data maintenance berhasil dihapus.');
    }

    public function bulkDestroyMaintenance(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriTeknikMaintenance::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.teknik.manage', ['tab' => 'maintenance'])->with('success', count($ids) . ' maintenance berhasil dihapus.');
        }
        return redirect()->route('tvri.teknik.manage', ['tab' => 'maintenance'])->with('error', 'Tidak ada data maintenance yang dipilih.');
    }

    // ================= CRUD INSIDEN =================
    public function storeInsiden(Request $request)
    {
        $request->validate([
            'waktu' => 'required|string|max:50',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|string|max:50',
        ]);

        $badge = ($request->status === 'Selesai') ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-amber-100 text-amber-800 border-amber-200';
        $data = $request->all();
        $data['badge'] = $badge;

        TvriTeknikInsiden::create($data);

        return redirect()->route('tvri.teknik.manage', ['tab' => 'insiden'])->with('success', 'Data insiden/gangguan berhasil dicatat.');
    }

    public function updateInsiden(Request $request, $id)
    {
        $request->validate([
            'waktu' => 'required|string|max:50',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|string|max:50',
        ]);

        $badge = ($request->status === 'Selesai') ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-amber-100 text-amber-800 border-amber-200';
        $ins = TvriTeknikInsiden::findOrFail($id);
        $data = $request->all();
        $data['badge'] = $badge;
        $ins->update($data);

        return redirect()->route('tvri.teknik.manage', ['tab' => 'insiden'])->with('success', 'Data insiden berhasil diperbarui.');
    }

    public function destroyInsiden($id)
    {
        TvriTeknikInsiden::findOrFail($id)->delete();
        return redirect()->route('tvri.teknik.manage', ['tab' => 'insiden'])->with('success', 'Data insiden berhasil dihapus.');
    }

    public function bulkDestroyInsiden(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriTeknikInsiden::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.teknik.manage', ['tab' => 'insiden'])->with('success', count($ids) . ' insiden berhasil dihapus.');
        }
        return redirect()->route('tvri.teknik.manage', ['tab' => 'insiden'])->with('error', 'Tidak ada data insiden yang dipilih.');
    }

    // ================= CRUD SOP TEKNIK =================
    public function storeSop(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'versi' => 'required|string|max:20',
            'tanggal_efektif' => 'nullable|date',
            'status' => 'required|string|max:50',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->except(["_token", "file"]); if ($request->hasFile("file")) { $clean = time()."_".preg_replace("/[^a-zA-Z0-9_\.-]/","_", $request->file("file")->getClientOriginalName()); $path = $request->file("file")->storeAs("sops", $clean, "public"); $data["file_url"] = "storage/".$path; $data["file_path"] = "storage/".$path; } TvriTeknikSop::create($data);

        return redirect()->route('tvri.teknik.manage', ['tab' => 'sop'])->with('success', 'Dokumen SOP Teknik berhasil ditambahkan.');
    }

    public function updateSop(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:50',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'versi' => 'required|string|max:20',
            'tanggal_efektif' => 'nullable|date',
            'status' => 'required|string|max:50',
            'keterangan' => 'nullable|string',
        ]);

        $sop = TvriTeknikSop::findOrFail($id);
        $data = $request->except(["_token", "_method", "file"]); if ($request->hasFile("file")) { $clean = time()."_".preg_replace("/[^a-zA-Z0-9_\.-]/","_", $request->file("file")->getClientOriginalName()); $path = $request->file("file")->storeAs("sops", $clean, "public"); $data["file_url"] = "storage/".$path; $data["file_path"] = "storage/".$path; } $sop->update($data);

        return redirect()->route('tvri.teknik.manage', ['tab' => 'sop'])->with('success', 'Dokumen SOP Teknik berhasil diperbarui.');
    }

    public function destroySop($id)
    {
        TvriTeknikSop::findOrFail($id)->delete();
        return redirect()->route('tvri.teknik.manage', ['tab' => 'sop'])->with('success', 'Dokumen SOP Teknik berhasil dihapus.');
    }

    public function bulkDestroySop(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriTeknikSop::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.teknik.manage', ['tab' => 'sop'])->with('success', count($ids) . ' dokumen SOP berhasil dihapus.');
        }
        return redirect()->route('tvri.teknik.manage', ['tab' => 'sop'])->with('error', 'Tidak ada dokumen SOP yang dipilih.');
    }

    // ================= CRUD TRANSMITTER DIGITAL =================
    public function storeTransmitter(Request $request)
    {
        $request->validate([
            'lokasi' => 'required|string|max:100',
            'frekuensi' => 'required|string|max:50',
            'daya' => 'required|string|max:50',
            'status' => 'required|string|max:50',
            'sinyal_warna' => 'required|string|max:50',
        ]);

        TvriTeknikTransmitter::create($request->all());

        return redirect()->route('tvri.teknik.manage', ['tab' => 'transmitter'])->with('success', 'Data stasiun pemancar transmitter berhasil ditambahkan.');
    }

    public function updateTransmitter(Request $request, $id)
    {
        $request->validate([
            'lokasi' => 'required|string|max:100',
            'frekuensi' => 'required|string|max:50',
            'daya' => 'required|string|max:50',
            'status' => 'required|string|max:50',
            'sinyal_warna' => 'required|string|max:50',
        ]);

        $tx = TvriTeknikTransmitter::findOrFail($id);
        $tx->update($request->all());

        return redirect()->route('tvri.teknik.manage', ['tab' => 'transmitter'])->with('success', 'Data stasiun pemancar transmitter berhasil diperbarui.');
    }

    public function destroyTransmitter($id)
    {
        TvriTeknikTransmitter::findOrFail($id)->delete();
        return redirect()->route('tvri.teknik.manage', ['tab' => 'transmitter'])->with('success', 'Data transmitter berhasil dihapus.');
    }

    public function bulkDestroyTransmitter(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriTeknikTransmitter::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.teknik.manage', ['tab' => 'transmitter'])->with('success', count($ids) . ' data transmitter berhasil dihapus.');
        }
        return redirect()->route('tvri.teknik.manage', ['tab' => 'transmitter'])->with('error', 'Tidak ada data transmitter yang dipilih.');
    }
}
