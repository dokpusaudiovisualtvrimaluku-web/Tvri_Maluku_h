<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\SimandatStudent;
use App\Models\SimandatSchool;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = SimandatStudent::query()->with('school');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('nisn', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%");
            });
        }

        if ($request->filled('kabupaten_kota') && $request->kabupaten_kota !== 'all') {
            $query->where('kabupaten_kota', $request->kabupaten_kota);
        }

        if ($request->filled('jenjang') && $request->jenjang !== 'all') {
            $query->where('jenjang', $request->jenjang);
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

        $students = $query->latest()->paginate(15)->withQueryString();
        $schools = SimandatSchool::orderBy('nama')->get();

        return view('pendidikan.students.index', compact('students', 'schools'));
    }

    public function create()
    {
        $schools = SimandatSchool::orderBy('nama')->get();
        return view('pendidikan.students.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|unique:simandat_students,nisn|max:20',
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'school_id' => 'required|exists:simandat_schools,id',
            'jenjang' => 'required|in:PAUD,TK,SD,SMP,SMA,SMK,SLB',
            'tingkat_kelas' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'nama_ibu_kandung' => 'nullable|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'tahun_ajaran' => 'required|string|max:20',
            'status' => 'required|string',
            'status_validasi' => 'required|in:DRAFT,SUBMITTED,VERIFIED,VALIDATED,REJECTED',
        ]);

        $student = SimandatStudent::create($validated);

        ActivityLogger::log('CREATE_STUDENT', "Menambahkan data siswa [{$student->nama} - NISN: {$student->nisn}]");

        return redirect()->route('pendidikan.students.index')->with('success', 'Data Peserta Didik berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $student = SimandatStudent::findOrFail($id);
        $schools = SimandatSchool::orderBy('nama')->get();
        return view('pendidikan.students.edit', compact('student', 'schools'));
    }

    public function update(Request $request, $id)
    {
        $student = SimandatStudent::findOrFail($id);

        $validated = $request->validate([
            'nisn' => 'required|string|max:20|unique:simandat_students,nisn,' . $student->id,
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'school_id' => 'required|exists:simandat_schools,id',
            'jenjang' => 'required|in:PAUD,TK,SD,SMP,SMA,SMK,SLB',
            'tingkat_kelas' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'nama_ibu_kandung' => 'nullable|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'tahun_ajaran' => 'required|string|max:20',
            'status' => 'required|string',
            'status_validasi' => 'required|in:DRAFT,SUBMITTED,VERIFIED,VALIDATED,REJECTED',
        ]);

        $student->update($validated);

        ActivityLogger::log('UPDATE_STUDENT', "Memperbarui data siswa [{$student->nama}]");

        return redirect()->route('pendidikan.students.index')->with('success', 'Data Peserta Didik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $student = SimandatStudent::findOrFail($id);
        $name = $student->nama;
        $student->delete();

        ActivityLogger::log('DELETE_STUDENT', "Menghapus data siswa [{$name}]");

        return redirect()->route('pendidikan.students.index')->with('success', "Peserta Didik {$name} berhasil dihapus.");
    }

    private function exportCsv($data)
    {
        $filename = 'data_peserta_didik_maluku_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['NISN', 'Nama Siswa', 'L/P', 'Sekolah', 'Jenjang', 'Kelas', 'Kabupaten/Kota', 'Tahun Ajaran', 'Status Siswa', 'Status Validasi']);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->nisn,
                    $row->nama,
                    $row->jenis_kelamin,
                    $row->school->nama_sekolah ?? $row->school->nama ?? '-',
                    $row->jenjang,
                    $row->tingkat_kelas,
                    $row->kabupaten_kota,
                    $row->tahun_ajaran,
                    $row->status,
                    $row->status_validasi,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
