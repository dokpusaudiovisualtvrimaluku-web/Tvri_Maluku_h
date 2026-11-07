<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\SimandatSarpras;
use App\Models\SimandatSchool;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class SarprasController extends Controller
{
    public function index(Request $request)
    {
        $query = SimandatSarpras::query()->with('school');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_sarpras', 'like', "%{$s}%")
                  ->orWhere('jenis_sarpras', 'like', "%{$s}%")
                  ->orWhereHas('school', function ($sq) use ($s) {
                      $sq->where('nama', 'like', "%{$s}%")
                         ->orWhere('nama_sekolah', 'like', "%{$s}%");
                  });
            });
        }

        if ($request->filled('kabupaten_kota') && $request->kabupaten_kota !== 'all') {
            $query->where('kabupaten_kota', $request->kabupaten_kota);
        }

        if ($request->filled('jenis_sarpras') && $request->jenis_sarpras !== 'all') {
            $query->where('jenis_sarpras', $request->jenis_sarpras);
        }

        if ($request->filled('school_id') && $request->school_id !== 'all') {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('status_validasi') && $request->status_validasi !== 'all') {
            $query->where('status_validasi', $request->status_validasi);
        }

        if ($request->get('export') === 'csv') {
            return $this->exportCsv($query->get());
        }

        $sarpras = $query->latest()->paginate(15)->withQueryString();
        $schools = SimandatSchool::orderBy('nama')->get();

        return view('pendidikan.sarpras.index', compact('sarpras', 'schools'));
    }

    public function create()
    {
        $schools = SimandatSchool::orderBy('nama')->get();
        return view('pendidikan.sarpras.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sarpras' => 'required|string|max:255',
            'jenis_sarpras' => 'required|string|max:100',
            'school_id' => 'required|exists:simandat_schools,id',
            'jumlah' => 'required|integer|min:0',
            'kondisi_baik' => 'required|integer|min:0',
            'rusak_ringan' => 'required|integer|min:0',
            'rusak_berat' => 'required|integer|min:0',
            'kabupaten_kota' => 'required|string|max:100',
            'tahun_data' => 'required|integer',
            'keterangan' => 'nullable|string',
            'status_validasi' => 'required|in:DRAFT,SUBMITTED,VERIFIED,VALIDATED,REJECTED',
        ]);

        $sarpras = SimandatSarpras::create($validated);

        ActivityLogger::log('CREATE_SARPRAS', "Menambahkan sarana prasarana [{$sarpras->nama_sarpras}]");

        return redirect()->route('pendidikan.sarpras.index')->with('success', 'Data Sarana Prasarana berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sarpra = SimandatSarpras::findOrFail($id);
        $schools = SimandatSchool::orderBy('nama')->get();
        return view('pendidikan.sarpras.edit', compact('sarpra', 'schools'));
    }

    public function update(Request $request, $id)
    {
        $sarpra = SimandatSarpras::findOrFail($id);

        $validated = $request->validate([
            'nama_sarpras' => 'required|string|max:255',
            'jenis_sarpras' => 'required|string|max:100',
            'school_id' => 'required|exists:simandat_schools,id',
            'jumlah' => 'required|integer|min:0',
            'kondisi_baik' => 'required|integer|min:0',
            'rusak_ringan' => 'required|integer|min:0',
            'rusak_berat' => 'required|integer|min:0',
            'kabupaten_kota' => 'required|string|max:100',
            'tahun_data' => 'required|integer',
            'keterangan' => 'nullable|string',
            'status_validasi' => 'required|in:DRAFT,SUBMITTED,VERIFIED,VALIDATED,REJECTED',
        ]);

        $sarpra->update($validated);

        ActivityLogger::log('UPDATE_SARPRAS', "Memperbarui sarana prasarana [{$sarpra->nama_sarpras}]");

        return redirect()->route('pendidikan.sarpras.index')->with('success', 'Data Sarana Prasarana berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sarpra = SimandatSarpras::findOrFail($id);
        $name = $sarpra->nama_sarpras;
        $sarpra->delete();

        ActivityLogger::log('DELETE_SARPRAS', "Menghapus sarana prasarana [{$name}]");

        return redirect()->route('pendidikan.sarpras.index')->with('success', "Sarpras {$name} berhasil dihapus.");
    }

    private function exportCsv($data)
    {
        $filename = 'data_sarpras_sekolah_maluku_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama Sarana', 'Jenis Sarana', 'Sekolah', 'Kabupaten/Kota', 'Jumlah Unit', 'Kondisi Baik', 'Rusak Ringan', 'Rusak Berat', 'Tahun', 'Status Validasi']);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->nama_sarpras,
                    $row->jenis_sarpras,
                    $row->school->nama_sekolah ?? $row->school->nama ?? '-',
                    $row->kabupaten_kota,
                    $row->jumlah,
                    $row->kondisi_baik,
                    $row->rusak_ringan,
                    $row->rusak_berat,
                    $row->tahun_data,
                    $row->status_validasi,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
