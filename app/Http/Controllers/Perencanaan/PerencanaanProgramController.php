<?php

namespace App\Http\Controllers\Perencanaan;

use App\Http\Controllers\Controller;
use App\Models\SimandatPerencanaanProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class PerencanaanProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = SimandatPerencanaanProgram::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('nama_program_kegiatan', 'like', "%{$search}%")
                  ->orWhere('kode_rekening', 'like', "%{$search}%")
                  ->orWhere('pejabat_penanggung_jawab', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_dokumen')) {
            $query->where('jenis_dokumen', $request->jenis_dokumen);
        }

        if ($request->filled('bidang_penanggung_jawab')) {
            $query->where('bidang_penanggung_jawab', $request->bidang_penanggung_jawab);
        }

        if ($request->filled('tahun_anggaran')) {
            $query->where('tahun_anggaran', $request->tahun_anggaran);
        }

        if ($request->filled('status_pelaksanaan')) {
            $query->where('status_pelaksanaan', $request->status_pelaksanaan);
        }

        $programList = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Statistics dynamic from DB
        $totalPagu = (float) SimandatPerencanaanProgram::sum('pagu_anggaran');
        $totalRealisasi = (float) SimandatPerencanaanProgram::sum('realisasi_anggaran');
        $serapanPercent = $totalPagu > 0 ? round(($totalRealisasi / $totalPagu) * 100, 2) : 0;

        $stats = [
            'total_dokumen' => SimandatPerencanaanProgram::count(),
            'total_pagu' => $totalPagu,
            'total_realisasi' => $totalRealisasi,
            'serapan_persen' => $serapanPercent,
            'count_program' => SimandatPerencanaanProgram::where('jenis_dokumen', 'Program')->count(),
            'count_kegiatan' => SimandatPerencanaanProgram::where('jenis_dokumen', 'Kegiatan')->count(),
            'count_renstra' => SimandatPerencanaanProgram::where('jenis_dokumen', 'Renstra')->count(),
            'count_renja' => SimandatPerencanaanProgram::where('jenis_dokumen', 'Renja')->count(),
            'count_rka' => SimandatPerencanaanProgram::where('jenis_dokumen', 'RKA')->count(),
            'count_dpa' => SimandatPerencanaanProgram::where('jenis_dokumen', 'DPA')->count(),
            'selesai' => SimandatPerencanaanProgram::where('status_pelaksanaan', 'SELESAI')->count(),
            'berjalan' => SimandatPerencanaanProgram::where('status_pelaksanaan', 'SEDANG_BERJALAN')->count(),
        ];

        $jenisDokumenOptions = SimandatPerencanaanProgram::JENIS_DOKUMEN_LIST;
        $bidangOptions = SimandatPerencanaanProgram::BIDANG_LIST;
        $statusOptions = SimandatPerencanaanProgram::STATUS_LIST;

        return view('perencanaan.program.index', compact(
            'programList',
            'stats',
            'jenisDokumenOptions',
            'bidangOptions',
            'statusOptions'
        ));
    }

    public function create(Request $request)
    {
        $selectedJenis = $request->query('jenis', 'Program');
        $jenisDokumenOptions = SimandatPerencanaanProgram::JENIS_DOKUMEN_LIST;
        $bidangOptions = SimandatPerencanaanProgram::BIDANG_LIST;
        $statusOptions = SimandatPerencanaanProgram::STATUS_LIST;

        return view('perencanaan.program.create', compact(
            'selectedJenis',
            'jenisDokumenOptions',
            'bidangOptions',
            'statusOptions'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_dokumen' => 'required|in:' . implode(',', array_keys(SimandatPerencanaanProgram::JENIS_DOKUMEN_LIST)),
            'kode_rekening' => 'nullable|string|max:50',
            'nama_program_kegiatan' => 'required|string|max:255',
            'bidang_penanggung_jawab' => 'required|string|max:150',
            'tahun_anggaran' => 'required|integer|min:2020|max:2035',
            'periode_pelaksanaan' => 'nullable|string|max:100',
            'pagu_anggaran' => 'required|numeric|min:0',
            'realisasi_anggaran' => 'nullable|numeric|min:0',
            'target_kinerja' => 'nullable|string|max:255',
            'realisasi_kinerja' => 'nullable|string|max:255',
            'persentase_capaian' => 'nullable|numeric|min:0|max:100',
            'status_pelaksanaan' => 'required|in:' . implode(',', array_keys(SimandatPerencanaanProgram::STATUS_LIST)),
            'uraian_kegiatan' => 'required|string',
            'pejabat_penanggung_jawab' => 'nullable|string|max:255',
            'catatan_evaluasi' => 'nullable|string',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:15360',
        ]);

        $validated['nomor_registrasi'] = SimandatPerencanaanProgram::generateNomorRegistrasi($validated['jenis_dokumen']);
        $validated['user_id'] = auth()->id() ?? 1;

        if (empty($validated['realisasi_anggaran'])) {
            $validated['realisasi_anggaran'] = 0;
        }

        // Auto calculate percentage if not provided
        if ($validated['pagu_anggaran'] > 0 && empty($validated['persentase_capaian'])) {
            $validated['persentase_capaian'] = round(($validated['realisasi_anggaran'] / $validated['pagu_anggaran']) * 100, 2);
        }

        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $path = $file->store('perencanaan_dokumen', 'public');
            $validated['file_dokumen'] = $path;
            $validated['file_nama_dokumen'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
        }

        $item = SimandatPerencanaanProgram::create($validated);

        return redirect()->route('perencanaan.program.show', $item->id)
            ->with('success', "Dokumen Perencanaan [{$item->jenis_dokumen}] {$item->nomor_registrasi} berhasil disimpan.");
    }

    public function show($id)
    {
        $program = $id instanceof SimandatPerencanaanProgram ? $id->load('user') : SimandatPerencanaanProgram::with('user')->findOrFail($id);
        return view('perencanaan.program.show', compact('program'));
    }

    public function edit($id)
    {
        $program = $id instanceof SimandatPerencanaanProgram ? $id : SimandatPerencanaanProgram::findOrFail($id);
        $jenisDokumenOptions = SimandatPerencanaanProgram::JENIS_DOKUMEN_LIST;
        $bidangOptions = SimandatPerencanaanProgram::BIDANG_LIST;
        $statusOptions = SimandatPerencanaanProgram::STATUS_LIST;

        return view('perencanaan.program.edit', compact(
            'program',
            'jenisDokumenOptions',
            'bidangOptions',
            'statusOptions'
        ));
    }

    public function update(Request $request, $id)
    {
        $program = $id instanceof SimandatPerencanaanProgram ? $id : SimandatPerencanaanProgram::findOrFail($id);

        $validated = $request->validate([
            'jenis_dokumen' => 'required|in:' . implode(',', array_keys(SimandatPerencanaanProgram::JENIS_DOKUMEN_LIST)),
            'kode_rekening' => 'nullable|string|max:50',
            'nama_program_kegiatan' => 'required|string|max:255',
            'bidang_penanggung_jawab' => 'required|string|max:150',
            'tahun_anggaran' => 'required|integer|min:2020|max:2035',
            'periode_pelaksanaan' => 'nullable|string|max:100',
            'pagu_anggaran' => 'required|numeric|min:0',
            'realisasi_anggaran' => 'nullable|numeric|min:0',
            'target_kinerja' => 'nullable|string|max:255',
            'realisasi_kinerja' => 'nullable|string|max:255',
            'persentase_capaian' => 'nullable|numeric|min:0|max:100',
            'status_pelaksanaan' => 'required|in:' . implode(',', array_keys(SimandatPerencanaanProgram::STATUS_LIST)),
            'uraian_kegiatan' => 'required|string',
            'pejabat_penanggung_jawab' => 'nullable|string|max:255',
            'catatan_evaluasi' => 'nullable|string',
        ]);

        if (isset($validated['pagu_anggaran']) && (float)$validated['pagu_anggaran'] > 0) {
            $real = (float)($validated['realisasi_anggaran'] ?? 0);
            $validated['persentase_capaian'] = min(100, round(($real / (float)$validated['pagu_anggaran']) * 100, 2));
        }

        if ($request->hasFile('file_dokumen')) {
            if ($program->file_dokumen && Storage::disk('public')->exists($program->file_dokumen)) {
                Storage::disk('public')->delete($program->file_dokumen);
            }
            $file = $request->file('file_dokumen');
            $path = $file->store('perencanaan_dokumen', 'public');
            $validated['file_dokumen'] = $path;
            $validated['file_nama_dokumen'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
        }

        $program->update($validated);

        return redirect()->route('perencanaan.program.show', $program->id)
            ->with('success', "Dokumen Perencanaan [{$program->jenis_dokumen}] {$program->nomor_registrasi} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $program = $id instanceof SimandatPerencanaanProgram ? $id : SimandatPerencanaanProgram::findOrFail($id);

        if ($program->file_dokumen && Storage::disk('public')->exists($program->file_dokumen)) {
            Storage::disk('public')->delete($program->file_dokumen);
        }

        $nomor = $program->nomor_registrasi;
        $jenis = $program->jenis_dokumen;
        $program->delete();

        return redirect()->route('perencanaan.program.index')
            ->with('success', "Dokumen Perencanaan [{$jenis}] {$nomor} berhasil dihapus.");
    }

    public function downloadFile($id)
    {
        $program = $id instanceof SimandatPerencanaanProgram ? $id : SimandatPerencanaanProgram::findOrFail($id);

        if (!$program->file_dokumen || !Storage::disk('public')->exists($program->file_dokumen)) {
            return back()->with('error', 'File dokumen tidak ditemukan atau belum diunggah.');
        }

        $customName = $program->file_nama_dokumen ?? "Dokumen_Perencanaan_{$program->nomor_registrasi}.pdf";
        return Storage::disk('public')->download($program->file_dokumen, $customName);
    }

    public function exportCsv(Request $request)
    {
        $query = SimandatPerencanaanProgram::query();

        if ($request->filled('jenis_dokumen')) {
            $query->where('jenis_dokumen', $request->jenis_dokumen);
        }

        if ($request->filled('bidang_penanggung_jawab')) {
            $query->where('bidang_penanggung_jawab', $request->bidang_penanggung_jawab);
        }

        if ($request->filled('tahun_anggaran')) {
            $query->where('tahun_anggaran', $request->tahun_anggaran);
        }

        if ($request->filled('status_pelaksanaan')) {
            $query->where('status_pelaksanaan', $request->status_pelaksanaan);
        }

        $records = $query->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data-program-perencanaan-' . date('Ymd-His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($records) {
            $output = fopen('php://output', 'w');
            fputs($output, "\xEF\xBB\xBF"); // UTF-8 BOM

            fputcsv($output, [
                'No',
                'No Registrasi',
                'Jenis Dokumen',
                'Kode Rekening',
                'Nama Program / Kegiatan',
                'Bidang Penanggung Jawab',
                'Tahun Anggaran',
                'Pagu Anggaran (Rp)',
                'Realisasi Anggaran (Rp)',
                'Serapan (%)',
                'Target Kinerja',
                'Realisasi Kinerja',
                'Status Pelaksanaan',
                'Pejabat Penanggung Jawab',
            ]);

            foreach ($records as $index => $row) {
                fputcsv($output, [
                    $index + 1,
                    $row->nomor_registrasi,
                    $row->jenis_dokumen,
                    $row->kode_rekening ?? '-',
                    $row->nama_program_kegiatan,
                    $row->bidang_penanggung_jawab,
                    $row->tahun_anggaran,
                    number_format($row->pagu_anggaran, 0, ',', '.'),
                    number_format($row->realisasi_anggaran, 0, ',', '.'),
                    $row->persentase_capaian . '%',
                    $row->target_kinerja ?? '-',
                    $row->realisasi_kinerja ?? '-',
                    $row->status_pelaksanaan,
                    $row->pejabat_penanggung_jawab ?? '-',
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}
