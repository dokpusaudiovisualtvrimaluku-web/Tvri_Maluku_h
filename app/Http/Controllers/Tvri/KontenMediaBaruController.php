<?php

namespace App\Http\Controllers\Tvri;

use App\Http\Controllers\Controller;
use App\Models\TvriKmbRingkasan;
use App\Models\TvriKmbSop;
use App\Models\TvriKonten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class KontenMediaBaruController extends Controller
{
    private function getRingkasan(): TvriKmbRingkasan
    {
        $ringkasan = TvriKmbRingkasan::first();
        if (!$ringkasan || TvriKonten::count() === 0 || TvriKmbSop::count() === 0) {
            $seeder = new \Database\Seeders\TvriKmbDataSeeder();
            $seeder->run();
            $ringkasan = TvriKmbRingkasan::first();
        }
        return $ringkasan;
    }

    public function index()
    {
        $ringkasan = $this->getRingkasan();

        $kontenList = TvriKonten::orderByDesc('views')->get();
        $sops = TvriKmbSop::orderBy('urutan')->get();

        $kontenTotal = $ringkasan->total_konten ?: $kontenList->count();
        $kontenViews = $ringkasan->total_views ?: (int) $kontenList->sum('views');
        $kontenReach = $ringkasan->total_reach ?: (int) $kontenList->sum('reach');
        $kontenEngagement = $ringkasan->engagement ?: (int) $kontenList->sum('engagement');
        $kontenPublish = $kontenList->where('status', 'Published')->count() ?: $kontenTotal;

        $platformList = DB::table('tvri_kontens')
            ->select('platform', DB::raw('count(*) as total'), DB::raw('sum(views) as total_views'), DB::raw('sum(engagement) as total_eng'))
            ->groupBy('platform')
            ->orderBy('total_views', 'desc')
            ->get();

        $topKonten = TvriKonten::orderByDesc('views')->limit(10)->get();

        return view('tvri.kmb.dashboard', compact(
            'ringkasan',
            'kontenTotal', 'kontenViews', 'kontenReach', 'kontenEngagement',
            'kontenPublish', 'platformList', 'topKonten', 'kontenList', 'sops'
        ));
    }

    public function manage(Request $request)
    {
        $ringkasan = $this->getRingkasan();
        $kontens = TvriKonten::orderByDesc('id')->get();
        $sops = TvriKmbSop::orderBy('urutan')->get();

        $tab = $request->query('tab', 'konten');

        return view('tvri.kmb.manage', compact(
            'ringkasan',
            'kontens',
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

        $ringkasan->update($data);

        return redirect()->route('tvri.mbn.manage', ['tab' => 'ringkasan'])->with('success', 'Parameter Ringkasan Konten Media Baru berhasil disimpan.');
    }

    public function reseedDefaults()
    {
        Artisan::call('db:seed', ['--class' => 'TvriKmbDataSeeder']);
        return redirect()->route('tvri.mbn.manage')->with('success', 'Data Konten Media Baru berhasil dipulihkan sesuai spesifikasi resmi.');
    }

    // ================= CRUD KONTEN =================
    public function storeKonten(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'platform' => 'required|string|max:100',
            'kategori' => 'required|string|max:100',
            'views' => 'required|numeric|min:0',
            'reach' => 'nullable|numeric|min:0',
            'engagement' => 'nullable|numeric|min:0',
            'watch_time' => 'nullable|numeric|min:0',
            'tanggal' => 'nullable|date',
            'status' => 'required|string|max:50',
        ]);

        TvriKonten::create($request->all());

        return redirect()->route('tvri.mbn.manage', ['tab' => 'konten'])->with('success', 'Konten digital baru berhasil ditambahkan.');
    }

    public function updateKonten(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'platform' => 'required|string|max:100',
            'kategori' => 'required|string|max:100',
            'views' => 'required|numeric|min:0',
            'reach' => 'nullable|numeric|min:0',
            'engagement' => 'nullable|numeric|min:0',
            'watch_time' => 'nullable|numeric|min:0',
            'tanggal' => 'nullable|date',
            'status' => 'required|string|max:50',
        ]);

        $konten = TvriKonten::findOrFail($id);
        $konten->update($request->all());

        return redirect()->route('tvri.mbn.manage', ['tab' => 'konten'])->with('success', 'Data konten digital berhasil diperbarui.');
    }

    public function destroyKonten($id)
    {
        TvriKonten::findOrFail($id)->delete();
        return redirect()->route('tvri.mbn.manage', ['tab' => 'konten'])->with('success', 'Konten berhasil dihapus.');
    }

    public function bulkDestroyKonten(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriKonten::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.mbn.manage', ['tab' => 'konten'])->with('success', count($ids) . ' konten berhasil dihapus.');
        }
        return redirect()->route('tvri.mbn.manage', ['tab' => 'konten'])->with('error', 'Tidak ada konten yang dipilih.');
    }

    // ================= CRUD SOP & REGULASI =================
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

        $data = $request->except(["_token", "file"]); if ($request->hasFile("file")) { $clean = time()."_".preg_replace("/[^a-zA-Z0-9_\.-]/","_", $request->file("file")->getClientOriginalName()); $path = $request->file("file")->storeAs("sops", $clean, "public"); $data["file_url"] = "storage/".$path; $data["file_path"] = "storage/".$path; } TvriKmbSop::create($data);

        return redirect()->route('tvri.mbn.manage', ['tab' => 'sop'])->with('success', 'Dokumen SOP KMB berhasil ditambahkan.');
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

        $sop = TvriKmbSop::findOrFail($id);
        $data = $request->except(["_token", "_method", "file"]); if ($request->hasFile("file")) { $clean = time()."_".preg_replace("/[^a-zA-Z0-9_\.-]/","_", $request->file("file")->getClientOriginalName()); $path = $request->file("file")->storeAs("sops", $clean, "public"); $data["file_url"] = "storage/".$path; $data["file_path"] = "storage/".$path; } $sop->update($data);

        return redirect()->route('tvri.mbn.manage', ['tab' => 'sop'])->with('success', 'Dokumen SOP KMB berhasil diperbarui.');
    }

    public function destroySop($id)
    {
        TvriKmbSop::findOrFail($id)->delete();
        return redirect()->route('tvri.mbn.manage', ['tab' => 'sop'])->with('success', 'Dokumen SOP berhasil dihapus.');
    }

    public function bulkDestroySop(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriKmbSop::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.mbn.manage', ['tab' => 'sop'])->with('success', count($ids) . ' dokumen SOP berhasil dihapus.');
        }
        return redirect()->route('tvri.mbn.manage', ['tab' => 'sop'])->with('error', 'Tidak ada dokumen SOP yang dipilih.');
    }
}
