<?php

namespace App\Http\Controllers\Tvri;

use App\Http\Controllers\Controller;
use App\Models\TvriKegiatanUsaha;
use App\Models\TvriMitra;
use App\Models\TvriPnbp;
use App\Models\TvriUsahaRingkasan;
use App\Models\TvriUsahaSop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PengembanganUsahaController extends Controller
{
    private function getRingkasan(): TvriUsahaRingkasan
    {
        $ringkasan = TvriUsahaRingkasan::first();
        if (!$ringkasan) {
            Artisan::call('db:seed', ['--class' => 'TvriUsahaDataSeeder']);
            $ringkasan = TvriUsahaRingkasan::first();
        }
        return $ringkasan;
    }

    public function index()
    {
        $ringkasan = $this->getRingkasan();

        $mitraList = TvriMitra::orderByDesc('nilai_kontrak')->get();
        $kegiatanList = TvriKegiatanUsaha::orderBy('tanggal')->get();
        $pnbpList = TvriPnbp::orderByDesc('realisasi')->get();
        $sops = TvriUsahaSop::orderBy('urutan')->get();

        // Indikator Kinerja Utama
        $pnbpTarget = (float) ($ringkasan->target_pendapatan ?: 3400000000);
        $pnbpRealisasi = (float) ($ringkasan->total_pendapatan ?: 2850000000);
        $pctPnbp = $pnbpTarget > 0 ? round($pnbpRealisasi / $pnbpTarget * 100, 1) : 0;

        $mitraTotal = $ringkasan->jumlah_mitra ?: $mitraList->count();
        $mitraAktif = $mitraList->where('status', 'Aktif')->count() ?: $mitraTotal;
        $totalKontrak = (float) $mitraList->sum('nilai_kontrak') ?: $pnbpRealisasi;

        $kegiatanTotal = $ringkasan->kegiatan_usaha ?: $kegiatanList->count();
        $kegiatanNilai = (float) $kegiatanList->sum('estimasi_nilai');

        return view('tvri.usaha.dashboard', compact(
            'ringkasan',
            'pnbpTarget', 'pnbpRealisasi', 'pctPnbp',
            'mitraTotal', 'mitraAktif', 'totalKontrak',
            'kegiatanTotal', 'kegiatanNilai',
            'pnbpList', 'mitraList', 'kegiatanList',
            'sops'
        ));
    }

    public function manage(Request $request)
    {
        $ringkasan = $this->getRingkasan();
        $mitras = TvriMitra::orderByDesc('nilai_kontrak')->get();
        $kegiatans = TvriKegiatanUsaha::orderBy('tanggal')->get();
        $pnbps = TvriPnbp::orderByDesc('realisasi')->get();
        $sops = TvriUsahaSop::orderBy('urutan')->get();

        $tab = $request->query('tab', 'mitra');

        return view('tvri.usaha.manage', compact(
            'ringkasan',
            'mitras',
            'kegiatans',
            'pnbps',
            'sops',
            'tab'
        ));
    }

    public function updateRingkasan(Request $request)
    {
        $ringkasan = $this->getRingkasan();
        $data = $request->except(['_token']);

        // Handle array json fields if submitted as newline/comma separated text
        if ($request->has('sudah_berjalan_text')) {
            $data['sudah_berjalan_json'] = array_filter(array_map('trim', explode("\n", $request->input('sudah_berjalan_text'))));
            unset($data['sudah_berjalan_text']);
        }
        if ($request->has('dalam_proses_text')) {
            $data['dalam_proses_json'] = array_filter(array_map('trim', explode("\n", $request->input('dalam_proses_text'))));
            unset($data['dalam_proses_text']);
        }
        if ($request->has('rencana_selanjutnya_text')) {
            $data['rencana_selanjutnya_json'] = array_filter(array_map('trim', explode("\n", $request->input('rencana_selanjutnya_text'))));
            unset($data['rencana_selanjutnya_text']);
        }

        $ringkasan->update($data);

        return redirect()->route('tvri.usaha.manage', ['tab' => 'ringkasan'])->with('success', 'Parameter Ringkasan Pengembangan Usaha berhasil disimpan.');
    }

    public function reseedDefaults()
    {
        Artisan::call('db:seed', ['--class' => 'TvriUsahaDataSeeder']);
        return redirect()->route('tvri.usaha.manage')->with('success', 'Data Pengembangan Usaha berhasil disinkronkan ke default file spesifikasi.');
    }

    // ================= CRUD MITRA / KLIEN =================
    public function storeMitra(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kerja_sama' => 'required|string|max:255',
            'nilai_kontrak' => 'required|numeric|min:0',
            'status' => 'required|string|max:50',
            'lokasi' => 'nullable|string|max:100',
        ]);

        TvriMitra::create($request->all());

        return redirect()->route('tvri.usaha.manage', ['tab' => 'mitra'])->with('success', 'Mitra / Klien baru berhasil ditambahkan.');
    }

    public function updateMitra(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kerja_sama' => 'required|string|max:255',
            'nilai_kontrak' => 'required|numeric|min:0',
            'status' => 'required|string|max:50',
            'lokasi' => 'nullable|string|max:100',
        ]);

        $mitra = TvriMitra::findOrFail($id);
        $mitra->update($request->all());

        return redirect()->route('tvri.usaha.manage', ['tab' => 'mitra'])->with('success', 'Data Mitra / Klien berhasil diperbarui.');
    }

    public function destroyMitra($id)
    {
        TvriMitra::findOrFail($id)->delete();
        return redirect()->route('tvri.usaha.manage', ['tab' => 'mitra'])->with('success', 'Data Mitra berhasil dihapus.');
    }

    public function bulkDestroyMitra(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriMitra::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.usaha.manage', ['tab' => 'mitra'])->with('success', count($ids) . ' data Mitra berhasil dihapus.');
        }
        return redirect()->route('tvri.usaha.manage', ['tab' => 'mitra'])->with('error', 'Tidak ada data yang dipilih.');
    }

    // ================= CRUD KEGIATAN USAHA =================
    public function storeKegiatan(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'estimasi_nilai' => 'required|numeric|min:0',
            'status' => 'required|string|max:50',
        ]);

        TvriKegiatanUsaha::create($request->all());

        return redirect()->route('tvri.usaha.manage', ['tab' => 'kegiatan'])->with('success', 'Kegiatan usaha baru berhasil ditambahkan.');
    }

    public function updateKegiatan(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'estimasi_nilai' => 'required|numeric|min:0',
            'status' => 'required|string|max:50',
        ]);

        $kegiatan = TvriKegiatanUsaha::findOrFail($id);
        $kegiatan->update($request->all());

        return redirect()->route('tvri.usaha.manage', ['tab' => 'kegiatan'])->with('success', 'Kegiatan usaha berhasil diperbarui.');
    }

    public function destroyKegiatan($id)
    {
        TvriKegiatanUsaha::findOrFail($id)->delete();
        return redirect()->route('tvri.usaha.manage', ['tab' => 'kegiatan'])->with('success', 'Kegiatan usaha berhasil dihapus.');
    }

    public function bulkDestroyKegiatan(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriKegiatanUsaha::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.usaha.manage', ['tab' => 'kegiatan'])->with('success', count($ids) . ' kegiatan usaha berhasil dihapus.');
        }
        return redirect()->route('tvri.usaha.manage', ['tab' => 'kegiatan'])->with('error', 'Tidak ada kegiatan yang dipilih.');
    }

    // ================= CRUD PNBP / SUMBER PENDAPATAN =================
    public function storePnbp(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
            'target' => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
            'tahun' => 'nullable|integer',
        ]);

        TvriPnbp::create($request->all());

        return redirect()->route('tvri.usaha.manage', ['tab' => 'pnbp'])->with('success', 'Sumber pendapatan / PNBP berhasil ditambahkan.');
    }

    public function updatePnbp(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
            'target' => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
            'tahun' => 'nullable|integer',
        ]);

        $pnbp = TvriPnbp::findOrFail($id);
        $pnbp->update($request->all());

        return redirect()->route('tvri.usaha.manage', ['tab' => 'pnbp'])->with('success', 'Data PNBP berhasil diperbarui.');
    }

    public function destroyPnbp($id)
    {
        TvriPnbp::findOrFail($id)->delete();
        return redirect()->route('tvri.usaha.manage', ['tab' => 'pnbp'])->with('success', 'Data PNBP berhasil dihapus.');
    }

    public function bulkDestroyPnbp(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriPnbp::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.usaha.manage', ['tab' => 'pnbp'])->with('success', count($ids) . ' data PNBP berhasil dihapus.');
        }
        return redirect()->route('tvri.usaha.manage', ['tab' => 'pnbp'])->with('error', 'Tidak ada data yang dipilih.');
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

        $data = $request->except(["_token", "file"]); if ($request->hasFile("file")) { $clean = time()."_".preg_replace("/[^a-zA-Z0-9_\.-]/","_", $request->file("file")->getClientOriginalName()); $path = $request->file("file")->storeAs("sops", $clean, "public"); $data["file_url"] = "storage/".$path; $data["file_path"] = "storage/".$path; } TvriUsahaSop::create($data);

        return redirect()->route('tvri.usaha.manage', ['tab' => 'sop'])->with('success', 'Dokumen SOP baru berhasil ditambahkan.');
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

        $sop = TvriUsahaSop::findOrFail($id);
        $data = $request->except(["_token", "_method", "file"]); if ($request->hasFile("file")) { $clean = time()."_".preg_replace("/[^a-zA-Z0-9_\.-]/","_", $request->file("file")->getClientOriginalName()); $path = $request->file("file")->storeAs("sops", $clean, "public"); $data["file_url"] = "storage/".$path; $data["file_path"] = "storage/".$path; } $sop->update($data);

        return redirect()->route('tvri.usaha.manage', ['tab' => 'sop'])->with('success', 'Dokumen SOP berhasil diperbarui.');
    }

    public function destroySop($id)
    {
        TvriUsahaSop::findOrFail($id)->delete();
        return redirect()->route('tvri.usaha.manage', ['tab' => 'sop'])->with('success', 'Dokumen SOP berhasil dihapus.');
    }

    public function bulkDestroySop(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriUsahaSop::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.usaha.manage', ['tab' => 'sop'])->with('success', count($ids) . ' dokumen SOP berhasil dihapus.');
        }
        return redirect()->route('tvri.usaha.manage', ['tab' => 'sop'])->with('error', 'Tidak ada dokumen SOP yang dipilih.');
    }
}
