<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\SimandatSchool;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $query = SimandatSchool::query()->withCount(['teachers', 'students', 'sarpras']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('nama_sekolah', 'like', "%{$s}%")
                  ->orWhere('npsn', 'like', "%{$s}%")
                  ->orWhere('kepala_sekolah', 'like', "%{$s}%");
            });
        }

        if ($request->filled('jenjang') && $request->jenjang !== 'all') {
            $query->where('jenjang', $request->jenjang);
        }

        if ($request->filled('kabupaten_kota') && $request->kabupaten_kota !== 'all') {
            $query->where(function ($q) use ($request) {
                $q->where('kabupaten_kota', $request->kabupaten_kota)
                  ->orWhere('cabang', 'like', "%{$request->kabupaten_kota}%");
            });
        }

        if ($request->filled('status_sekolah') && $request->status_sekolah !== 'all') {
            $query->where('status_sekolah', $request->status_sekolah);
        }

        if ($request->filled('akreditasi') && $request->akreditasi !== 'all') {
            $query->where('akreditasi', $request->akreditasi);
        }

        if ($request->filled('status_validasi') && $request->status_validasi !== 'all') {
            $query->where('status_validasi', $request->status_validasi);
        }

        if ($request->get('export') === 'csv') {
            return $this->exportCsv($query->get());
        }

        $schools = $query->latest()->paginate(15)->withQueryString();

        $totalSekolah = SimandatSchool::count();
        $totalNegeri = SimandatSchool::where('status_sekolah', 'Negeri')->count();
        $totalSwasta = SimandatSchool::where('status_sekolah', 'Swasta')->count();
        $totalSiswa = SimandatSchool::sum('jumlah_siswa') ?: SimandatSchool::sum('siswa') ?: \App\Models\SimandatStudent::count();
        $totalGuru = SimandatSchool::sum('jumlah_guru') ?: \App\Models\SimandatTeacher::count();
        $totalValidated = SimandatSchool::where('status_validasi', 'VALIDATED')->count();

        $jenjangList = ['PAUD', 'TK', 'SD', 'SMP', 'SMA', 'SMK', 'SLB'];
        $jenjangCounts = [];
        foreach ($jenjangList as $j) {
            $jenjangCounts[$j] = SimandatSchool::where('jenjang', $j)->count();
        }

        $kabupatenOptions = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        return view('pendidikan.schools.index', compact(
            'schools', 'kabupatenOptions', 'totalSekolah', 'totalNegeri', 'totalSwasta',
            'totalSiswa', 'totalGuru', 'totalValidated', 'jenjangCounts'
        ));
    }

    public function create()
    {
        $kabupatenOptions = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        return view('pendidikan.schools.create', compact('kabupatenOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'npsn' => 'required|string|unique:simandat_schools,npsn|max:20',
            'nama_sekolah' => 'required|string|max:255',
            'jenjang' => 'required|in:PAUD,TK,SD,SMP,SMA,SMK,SLB',
            'status_sekolah' => 'required|in:Negeri,Swasta',
            'alamat' => 'nullable|string',
            'desa_kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'kepala_sekolah' => 'nullable|string|max:255',
            'jumlah_guru' => 'nullable|integer|min:0',
            'jumlah_siswa' => 'nullable|integer|min:0',
            'akreditasi' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'tahun_data' => 'required|string|max:10',
            'status_validasi' => 'required|in:DRAFT,SUBMITTED,VERIFIED,VALIDATED,REJECTED',
        ]);

        $validated['nama'] = $validated['nama_sekolah'];
        $validated['cabang'] = $validated['kabupaten_kota'];
        $validated['siswa'] = $validated['jumlah_siswa'] ?? 0;
        $school = SimandatSchool::create($validated);

        ActivityLogger::log('CREATE_SCHOOL', "Menambahkan data satuan pendidikan [{$school->nama_sekolah} - NPSN: {$school->npsn}]");

        return redirect()->route('pendidikan.schools.show', $school->id)->with('success', 'Data Satuan Pendidikan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $school = SimandatSchool::with(['teachers', 'students', 'sarpras', 'archives'])->findOrFail($id);
        return view('pendidikan.schools.show', compact('school'));
    }

    public function edit($id)
    {
        $school = SimandatSchool::findOrFail($id);
        $kabupatenOptions = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        return view('pendidikan.schools.edit', compact('school', 'kabupatenOptions'));
    }

    public function update(Request $request, $id)
    {
        $school = SimandatSchool::findOrFail($id);

        $validated = $request->validate([
            'npsn' => 'required|string|max:20|unique:simandat_schools,npsn,' . $school->id,
            'nama_sekolah' => 'required|string|max:255',
            'jenjang' => 'required|in:PAUD,TK,SD,SMP,SMA,SMK,SLB',
            'status_sekolah' => 'required|in:Negeri,Swasta',
            'alamat' => 'nullable|string',
            'desa_kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'kepala_sekolah' => 'nullable|string|max:255',
            'jumlah_guru' => 'nullable|integer|min:0',
            'jumlah_siswa' => 'nullable|integer|min:0',
            'akreditasi' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'tahun_data' => 'required|string|max:10',
            'status_validasi' => 'required|in:DRAFT,SUBMITTED,VERIFIED,VALIDATED,REJECTED',
        ]);

        $validated['nama'] = $validated['nama_sekolah'];
        $validated['cabang'] = $validated['kabupaten_kota'];
        $validated['siswa'] = $validated['jumlah_siswa'] ?? 0;
        $school->update($validated);

        ActivityLogger::log('UPDATE_SCHOOL', "Memperbarui data satuan pendidikan [{$school->nama_sekolah}]");

        return redirect()->route('pendidikan.schools.show', $school->id)->with('success', 'Data Satuan Pendidikan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $school = SimandatSchool::findOrFail($id);
        $name = $school->nama_sekolah ?? $school->nama;
        $school->delete();

        ActivityLogger::log('DELETE_SCHOOL', "Menghapus data satuan pendidikan [{$name}]");

        return redirect()->route('pendidikan.schools.index')->with('success', "Satuan Pendidikan {$name} berhasil dihapus.");
    }

    public function updateValidationStatus(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:DRAFT,SUBMITTED,VERIFIED,VALIDATED,REJECTED'
        ]);

        $school = SimandatSchool::findOrFail($id);
        $school->update(['status_validasi' => $request->status_validasi]);

        $name = $school->nama_sekolah ?? $school->nama;
        ActivityLogger::log('VALIDATE_SCHOOL', "Mengubah status validasi [{$name}] menjadi {$request->status_validasi}");

        return back()->with('success', "Status validasi {$name} berhasil diubah ke {$request->status_validasi}.");
    }

    private function exportCsv($data)
    {
        $filename = 'data_satuan_pendidikan_maluku_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['NPSN', 'Nama Sekolah', 'Jenjang', 'Status', 'Kabupaten/Kota', 'Kecamatan', 'Kepala Sekolah', 'Guru', 'Siswa', 'Akreditasi', 'Tahun Data', 'Status Validasi']);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->npsn,
                    $row->nama_sekolah ?? $row->nama,
                    $row->jenjang,
                    $row->status_sekolah,
                    $row->kabupaten_kota ?? $row->cabang,
                    $row->kecamatan,
                    $row->kepala_sekolah,
                    $row->jumlah_guru,
                    $row->jumlah_siswa ?? $row->siswa,
                    $row->akreditasi,
                    $row->tahun_data,
                    $row->status_validasi,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
