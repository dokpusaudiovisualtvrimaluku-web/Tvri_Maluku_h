<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\SimandatTeacher;
use App\Models\SimandatSchool;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = SimandatTeacher::query()->with('school');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('nip', 'like', "%{$s}%")
                  ->orWhere('nuptk', 'like', "%{$s}%");
            });
        }

        if ($request->filled('kabupaten_kota') && $request->kabupaten_kota !== 'all') {
            $query->where('kabupaten_kota', $request->kabupaten_kota);
        }

        if ($request->filled('status_kepegawaian') && $request->status_kepegawaian !== 'all') {
            $query->where('status_kepegawaian', $request->status_kepegawaian);
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

        $teachers = $query->latest()->paginate(15)->withQueryString();
        $schools = SimandatSchool::orderBy('nama')->get();

        return view('pendidikan.teachers.index', compact('teachers', 'schools'));
    }

    public function create()
    {
        $schools = SimandatSchool::orderBy('nama')->get();
        return view('pendidikan.teachers.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:30',
            'nuptk' => 'nullable|string|max:30',
            'jenis_kelamin' => 'required|in:L,P',
            'jabatan' => 'required|string|max:100',
            'mata_pelajaran' => 'nullable|string|max:100',
            'pangkat_golongan' => 'nullable|string|max:50',
            'status_kepegawaian' => 'required|string',
            'school_id' => 'required|exists:simandat_schools,id',
            'pendidikan_terakhir' => 'nullable|string|max:50',
            'sertifikasi' => 'required|boolean',
            'kabupaten_kota' => 'required|string|max:100',
            'tahun_data' => 'required|integer',
            'status_validasi' => 'required|in:DRAFT,SUBMITTED,VERIFIED,VALIDATED,REJECTED',
        ]);

        $teacher = SimandatTeacher::create($validated);

        ActivityLogger::log('CREATE_TEACHER', "Menambahkan data PTK [{$teacher->nama}]");

        return redirect()->route('pendidikan.teachers.index')->with('success', 'Data Guru & Tenaga Kependidikan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $teacher = SimandatTeacher::findOrFail($id);
        $schools = SimandatSchool::orderBy('nama')->get();
        return view('pendidikan.teachers.edit', compact('teacher', 'schools'));
    }

    public function update(Request $request, $id)
    {
        $teacher = SimandatTeacher::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:30',
            'nuptk' => 'nullable|string|max:30',
            'jenis_kelamin' => 'required|in:L,P',
            'jabatan' => 'required|string|max:100',
            'mata_pelajaran' => 'nullable|string|max:100',
            'pangkat_golongan' => 'nullable|string|max:50',
            'status_kepegawaian' => 'required|string',
            'school_id' => 'required|exists:simandat_schools,id',
            'pendidikan_terakhir' => 'nullable|string|max:50',
            'sertifikasi' => 'required|boolean',
            'kabupaten_kota' => 'required|string|max:100',
            'tahun_data' => 'required|integer',
            'status_validasi' => 'required|in:DRAFT,SUBMITTED,VERIFIED,VALIDATED,REJECTED',
        ]);

        $teacher->update($validated);

        ActivityLogger::log('UPDATE_TEACHER', "Memperbarui data PTK [{$teacher->nama}]");

        return redirect()->route('pendidikan.teachers.index')->with('success', 'Data Guru & Tenaga Kependidikan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $teacher = SimandatTeacher::findOrFail($id);
        $name = $teacher->nama;
        $teacher->delete();

        ActivityLogger::log('DELETE_TEACHER', "Menghapus data PTK [{$name}]");

        return redirect()->route('pendidikan.teachers.index')->with('success', "Guru/Tendik {$name} berhasil dihapus.");
    }

    private function exportCsv($data)
    {
        $filename = 'data_guru_tendik_maluku_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['NIP', 'NUPTK', 'Nama', 'L/P', 'Jabatan', 'Mapel', 'Status Kepegawaian', 'Golongan', 'Sekolah', 'Kabupaten/Kota', 'Sertifikasi', 'Tahun', 'Status Validasi']);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->nip,
                    $row->nuptk,
                    $row->nama,
                    $row->jenis_kelamin,
                    $row->jabatan,
                    $row->mata_pelajaran,
                    $row->status_kepegawaian,
                    $row->pangkat_golongan,
                    $row->school->nama_sekolah ?? $row->school->nama ?? '-',
                    $row->kabupaten_kota,
                    $row->sertifikasi ? 'Sudah' : 'Belum',
                    $row->tahun_data,
                    $row->status_validasi,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
