<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\Controller;
use App\Models\SimandatSekretariat;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class SekretariatController extends Controller
{
    /**
     * Display listing of Sekretariat Dinas data across 8 core functions.
     */
    public function index(Request $request)
    {
        $query = SimandatSekretariat::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_agenda', 'like', "%{$search}%")
                  ->orWhere('judul_kegiatan', 'like', "%{$search}%")
                  ->orWhere('sub_kegiatan', 'like', "%{$search}%")
                  ->orWhere('penanggung_jawab', 'like', "%{$search}%")
                  ->orWhere('bidang_unit_terkait', 'like', "%{$search}%")
                  ->orWhere('deskripsi_uraian', 'like', "%{$search}%")
                  ->orWhere('catatan_arahan_sekretaris', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_fungsi') && $request->kategori_fungsi !== 'all') {
            $query->where('kategori_fungsi', $request->kategori_fungsi);
        }

        if ($request->filled('status_pelaksanaan') && $request->status_pelaksanaan !== 'all') {
            $query->where('status_pelaksanaan', $request->status_pelaksanaan);
        }

        if ($request->filled('tingkat_prioritas') && $request->tingkat_prioritas !== 'all') {
            $query->where('tingkat_prioritas', $request->tingkat_prioritas);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_anggaran', $request->tahun);
        }

        // Export CSV if requested
        if ($request->get('export') === 'csv') {
            return $this->exportCsv($query->get());
        }

        $agendaList = $query->orderBy('tanggal_kegiatan', 'desc')->paginate(12)->withQueryString();

        // 8 Core Functions count for tabs
        $fungsiCounts = [];
        foreach (array_keys(SimandatSekretariat::FUNGSI_LIST) as $fungsiKey) {
            $fungsiCounts[$fungsiKey] = SimandatSekretariat::where('kategori_fungsi', $fungsiKey)->count();
        }

        // Summary Metric Cards
        $totalKegiatan = SimandatSekretariat::count();
        $totalBerjalan = SimandatSekretariat::whereIn('status_pelaksanaan', ['Sedang Berjalan', 'Dalam Proses', 'Menunggu Telaah'])->count();
        $totalSelesai = SimandatSekretariat::where('status_pelaksanaan', 'Selesai')->count();
        $totalAlokasi = SimandatSekretariat::sum('alokasi_anggaran');
        $totalRealisasi = SimandatSekretariat::sum('realisasi_anggaran');
        $avgCapaian = SimandatSekretariat::count() > 0 ? round(SimandatSekretariat::avg('persentase_capaian')) : 0;

        $allFungsi = SimandatSekretariat::FUNGSI_LIST;
        $statusOptions = SimandatSekretariat::STATUS_LIST;
        $prioritasOptions = SimandatSekretariat::PRIORITAS_LIST;

        $bidangOptions = [
            'Sekretariat Dinas',
            'Bidang Pembinaan SMA',
            'Bidang Pembinaan SMK',
            'Bidang Pembinaan Pendidikan Khusus',
            'Bidang Guru & Tenaga Kependidikan (GTK)',
            'Bidang Kebudayaan',
            'Sub Bagian Kepegawaian & Umum',
            'Sub Bagian Perencanaan & Program',
            'Sub Bagian Keuangan',
            'UPTD Balai Teknologi Komunikasi Pendidikan (BTKP)',
            'UPTD Museum Siwalima',
            'UPTD Taman Budaya',
            'Bappeda Prov. Maluku',
            'BPKAD Prov. Maluku',
            'Inspektorat Prov. Maluku',
            'BKD Prov. Maluku',
            'Kementerian Dikbudristek RI',
        ];

        return view('sekretariat.index', compact(
            'agendaList', 'fungsiCounts', 'totalKegiatan', 'totalBerjalan', 'totalSelesai',
            'totalAlokasi', 'totalRealisasi', 'avgCapaian', 'allFungsi', 'statusOptions',
            'prioritasOptions', 'bidangOptions'
        ));
    }

    /**
     * Show form to create new agenda / activity record.
     */
    public function create(Request $request)
    {
        $selectedFungsi = $request->get('fungsi', 'koordinasi_administrasi');
        $allFungsi = SimandatSekretariat::FUNGSI_LIST;
        $statusOptions = SimandatSekretariat::STATUS_LIST;
        $prioritasOptions = SimandatSekretariat::PRIORITAS_LIST;

        $bidangOptions = [
            'Sekretariat Dinas',
            'Bidang Pembinaan SMA',
            'Bidang Pembinaan SMK',
            'Bidang Pembinaan Pendidikan Khusus',
            'Bidang Guru & Tenaga Kependidikan (GTK)',
            'Bidang Kebudayaan',
            'Sub Bagian Kepegawaian & Umum',
            'Sub Bagian Perencanaan & Program',
            'Sub Bagian Keuangan',
            'UPTD BTKP Maluku',
            'UPTD Museum Siwalima',
            'UPTD Taman Budaya',
            'Bappeda Prov. Maluku',
            'BPKAD Prov. Maluku',
            'Inspektorat Prov. Maluku',
            'BKD Prov. Maluku',
            'Kementerian Pendidikan & Kebudayaan RI',
        ];

        return view('sekretariat.create', compact(
            'selectedFungsi', 'allFungsi', 'statusOptions', 'prioritasOptions', 'bidangOptions'
        ));
    }

    /**
     * Store new agenda / activity record in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_fungsi' => 'required|string|in:' . implode(',', array_keys(SimandatSekretariat::FUNGSI_LIST)),
            'sub_kegiatan' => 'required|string|max:255',
            'judul_kegiatan' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'bidang_unit_terkait' => 'nullable|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'tanggal_target_selesai' => 'nullable|date|after_or_equal:tanggal_kegiatan',
            'tahun_anggaran' => 'required|integer|min:2020|max:2035',
            'alokasi_anggaran' => 'nullable|numeric|min:0',
            'realisasi_anggaran' => 'nullable|numeric|min:0',
            'persentase_capaian' => 'nullable|integer|min:0|max:100',
            'status_pelaksanaan' => 'required|string|in:' . implode(',', array_keys(SimandatSekretariat::STATUS_LIST)),
            'tingkat_prioritas' => 'required|string|in:' . implode(',', array_keys(SimandatSekretariat::PRIORITAS_LIST)),
            'deskripsi_uraian' => 'nullable|string',
            'hasil_keluaran_output' => 'nullable|string',
            'catatan_arahan_sekretaris' => 'nullable|string',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip,rar|max:25600',
        ]);

        // Auto-generate agenda number
        $prefix = SimandatSekretariat::FUNGSI_LIST[$request->kategori_fungsi]['prefix'] ?? 'SEK-GEN';
        $year = $request->tahun_anggaran ?? date('Y');
        $randomPart = strtoupper(Str::random(4));
        $sequence = SimandatSekretariat::where('kategori_fungsi', $request->kategori_fungsi)
                                        ->where('tahun_anggaran', $year)
                                        ->count() + 1;
        $nomorAgenda = sprintf('%s-%d-%04d-%s', $prefix, $year, $sequence, $randomPart);

        $filePath = null;
        $fileOriginalName = null;
        $fileSize = null;

        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $fileOriginalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileName = 'sekretariat_' . time() . '_' . Str::slug(pathinfo($fileOriginalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('sekretariat_docs', $fileName, 'public');
        }

        $agenda = SimandatSekretariat::create([
            'nomor_agenda' => $nomorAgenda,
            'kategori_fungsi' => $request->kategori_fungsi,
            'sub_kegiatan' => $request->sub_kegiatan,
            'judul_kegiatan' => $request->judul_kegiatan,
            'penanggung_jawab' => $request->penanggung_jawab,
            'bidang_unit_terkait' => $request->bidang_unit_terkait,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'tanggal_target_selesai' => $request->tanggal_target_selesai,
            'tahun_anggaran' => $request->tahun_anggaran ?? date('Y'),
            'alokasi_anggaran' => $request->alokasi_anggaran ?? 0,
            'realisasi_anggaran' => $request->realisasi_anggaran ?? 0,
            'persentase_capaian' => $request->persentase_capaian ?? 0,
            'status_pelaksanaan' => $request->status_pelaksanaan,
            'tingkat_prioritas' => $request->tingkat_prioritas,
            'deskripsi_uraian' => $request->deskripsi_uraian,
            'hasil_keluaran_output' => $request->hasil_keluaran_output,
            'catatan_arahan_sekretaris' => $request->catatan_arahan_sekretaris,
            'file_dokumen' => $filePath,
            'file_nama_asli' => $fileOriginalName,
            'file_size' => $fileSize,
            'user_id' => Auth::id(),
        ]);

        ActivityLogger::log(
            'create_sekretariat_agenda',
            "Menambahkan data agenda/fungsi Sekretaris Dinas [{$agenda->nomor_agenda}] '{$agenda->judul_kegiatan}' ({$agenda->fungsi_label})"
        );

        return redirect()->route('sekretariat.data.show', $agenda->id)
                         ->with('success', "Data agenda '{$agenda->judul_kegiatan}' nomor agenda [{$agenda->nomor_agenda}] berhasil ditambahkan.");
    }

    /**
     * Display detailed view of agenda record.
     */
    public function show($id)
    {
        $agenda = SimandatSekretariat::with('user')->findOrFail($id);
        $allFungsi = SimandatSekretariat::FUNGSI_LIST;

        return view('sekretariat.show', compact('agenda', 'allFungsi'));
    }

    /**
     * Show form to edit existing record.
     */
    public function edit($id)
    {
        $agenda = SimandatSekretariat::findOrFail($id);
        $allFungsi = SimandatSekretariat::FUNGSI_LIST;
        $statusOptions = SimandatSekretariat::STATUS_LIST;
        $prioritasOptions = SimandatSekretariat::PRIORITAS_LIST;

        $bidangOptions = [
            'Sekretariat Dinas',
            'Bidang Pembinaan SMA',
            'Bidang Pembinaan SMK',
            'Bidang Pembinaan Pendidikan Khusus',
            'Bidang Guru & Tenaga Kependidikan (GTK)',
            'Bidang Kebudayaan',
            'Sub Bagian Kepegawaian & Umum',
            'Sub Bagian Perencanaan & Program',
            'Sub Bagian Keuangan',
            'UPTD BTKP Maluku',
            'UPTD Museum Siwalima',
            'UPTD Taman Budaya',
            'Bappeda Prov. Maluku',
            'BPKAD Prov. Maluku',
            'Inspektorat Prov. Maluku',
            'BKD Prov. Maluku',
            'Kementerian Pendidikan & Kebudayaan RI',
        ];

        return view('sekretariat.edit', compact(
            'agenda', 'allFungsi', 'statusOptions', 'prioritasOptions', 'bidangOptions'
        ));
    }

    /**
     * Update existing record.
     */
    public function update(Request $request, $id)
    {
        $agenda = SimandatSekretariat::findOrFail($id);

        $request->validate([
            'kategori_fungsi' => 'required|string|in:' . implode(',', array_keys(SimandatSekretariat::FUNGSI_LIST)),
            'sub_kegiatan' => 'required|string|max:255',
            'judul_kegiatan' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'bidang_unit_terkait' => 'nullable|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'tanggal_target_selesai' => 'nullable|date|after_or_equal:tanggal_kegiatan',
            'tahun_anggaran' => 'required|integer|min:2020|max:2035',
            'alokasi_anggaran' => 'nullable|numeric|min:0',
            'realisasi_anggaran' => 'nullable|numeric|min:0',
            'persentase_capaian' => 'nullable|integer|min:0|max:100',
            'status_pelaksanaan' => 'required|string|in:' . implode(',', array_keys(SimandatSekretariat::STATUS_LIST)),
            'tingkat_prioritas' => 'required|string|in:' . implode(',', array_keys(SimandatSekretariat::PRIORITAS_LIST)),
            'deskripsi_uraian' => 'nullable|string',
            'hasil_keluaran_output' => 'nullable|string',
            'catatan_arahan_sekretaris' => 'nullable|string',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip,rar|max:25600',
        ]);

        $filePath = $agenda->file_dokumen;
        $fileOriginalName = $agenda->file_nama_asli;
        $fileSize = $agenda->file_size;

        if ($request->hasFile('file_dokumen')) {
            if ($agenda->file_dokumen && Storage::disk('public')->exists($agenda->file_dokumen)) {
                Storage::disk('public')->delete($agenda->file_dokumen);
            }

            $file = $request->file('file_dokumen');
            $fileOriginalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileName = 'sekretariat_' . time() . '_' . Str::slug(pathinfo($fileOriginalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('sekretariat_docs', $fileName, 'public');
        }

        $agenda->update([
            'kategori_fungsi' => $request->kategori_fungsi,
            'sub_kegiatan' => $request->sub_kegiatan,
            'judul_kegiatan' => $request->judul_kegiatan,
            'penanggung_jawab' => $request->penanggung_jawab,
            'bidang_unit_terkait' => $request->bidang_unit_terkait,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'tanggal_target_selesai' => $request->tanggal_target_selesai,
            'tahun_anggaran' => $request->tahun_anggaran,
            'alokasi_anggaran' => $request->alokasi_anggaran ?? 0,
            'realisasi_anggaran' => $request->realisasi_anggaran ?? 0,
            'persentase_capaian' => $request->persentase_capaian ?? 0,
            'status_pelaksanaan' => $request->status_pelaksanaan,
            'tingkat_prioritas' => $request->tingkat_prioritas,
            'deskripsi_uraian' => $request->deskripsi_uraian,
            'hasil_keluaran_output' => $request->hasil_keluaran_output,
            'catatan_arahan_sekretaris' => $request->catatan_arahan_sekretaris,
            'file_dokumen' => $filePath,
            'file_nama_asli' => $fileOriginalName,
            'file_size' => $fileSize,
        ]);

        ActivityLogger::log(
            'update_sekretariat_agenda',
            "Memperbarui data agenda/fungsi Sekretariat Dinas [{$agenda->nomor_agenda}] '{$agenda->judul_kegiatan}'"
        );

        return redirect()->route('sekretariat.data.show', $agenda->id)
                         ->with('success', "Data agenda '{$agenda->judul_kegiatan}' berhasil diperbarui.");
    }

    /**
     * Delete record (soft delete).
     */
    public function destroy($id)
    {
        $agenda = SimandatSekretariat::findOrFail($id);
        $nomor = $agenda->nomor_agenda;
        $judul = $agenda->judul_kegiatan;

        $agenda->delete();

        ActivityLogger::log(
            'delete_sekretariat_agenda',
            "Menghapus data agenda Sekretariat Dinas [{$nomor}] '{$judul}'"
        );

        return redirect()->route('sekretariat.data.index')
                         ->with('success', "Data agenda '{$judul}' ({$nomor}) berhasil dihapus.");
    }

    /**
     * Download attached file.
     */
    public function downloadFile($id)
    {
        $agenda = SimandatSekretariat::findOrFail($id);

        if (!$agenda->file_dokumen || !Storage::disk('public')->exists($agenda->file_dokumen)) {
            return back()->with('error', 'Berkas lampiran fisik tidak ditemukan di server.');
        }

        ActivityLogger::log(
            'download_sekretariat_file',
            "Mengunduh berkas lampiran agenda Sekretariat [{$agenda->nomor_agenda}] '{$agenda->file_nama_asli}'"
        );

        return Storage::disk('public')->download($agenda->file_dokumen, $agenda->file_nama_asli ?? 'lampiran_sekretariat.pdf');
    }

    /**
     * Export dataset to CSV.
     */
    protected function exportCsv($items)
    {
        $filename = 'data_sekretariat_disdikbud_maluku_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($file, [
                'No Agenda', 'Kategori Fungsi', 'Sub Kegiatan', 'Judul Kegiatan',
                'Penanggung Jawab', 'Bidang/Unit Terkait', 'Tanggal Kegiatan', 'Target Selesai',
                'Tahun Anggaran', 'Alokasi Anggaran (Rp)', 'Realisasi Anggaran (Rp)',
                'Capaian (%)', 'Status Pelaksanaan', 'Tingkat Prioritas', 'Deskripsi Uraian',
                'Hasil Keluaran Output', 'Catatan/Arahan Sekretaris Dinas'
            ]);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->nomor_agenda,
                    $item->fungsi_label,
                    $item->sub_kegiatan,
                    $item->judul_kegiatan,
                    $item->penanggung_jawab,
                    $item->bidang_unit_terkait ?? '-',
                    $item->tanggal_kegiatan ? $item->tanggal_kegiatan->format('Y-m-d') : '-',
                    $item->tanggal_target_selesai ? $item->tanggal_target_selesai->format('Y-m-d') : '-',
                    $item->tahun_anggaran,
                    $item->alokasi_anggaran,
                    $item->realisasi_anggaran,
                    $item->persentase_capaian,
                    $item->status_pelaksanaan,
                    $item->tingkat_prioritas,
                    $item->deskripsi_uraian ?? '-',
                    $item->hasil_keluaran_output ?? '-',
                    $item->catatan_arahan_sekretaris ?? '-',
                ]);
            }
            fclose($file);
        };

        ActivityLogger::log('export_sekretariat_csv', 'Mengekspor data Sekretariat Dinas ke format CSV');

        return Response::stream($callback, 200, $headers);
    }
}
