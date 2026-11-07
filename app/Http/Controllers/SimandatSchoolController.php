<?php

namespace App\Http\Controllers;

use App\Models\SimandatSchool;
use Illuminate\Http\Request;

class SimandatSchoolController extends Controller
{
    /**
     * Display a listing of schools.
     */
    public function index()
    {
        $schools = SimandatSchool::orderBy('id', 'asc')->get();

        if (SimandatSchool::count() === 0) {
            $this->seedInitialSchools();
            $schools = SimandatSchool::orderBy('id', 'asc')->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $schools,
            'total' => $schools->count(),
        ]);
    }

    /**
     * Store a new school.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'npsn' => 'required|string|unique:simandat_schools,npsn|max:20',
            'nama' => 'required|string|max:255',
            'jenjang' => 'required|string|in:SMA,SMK,SLB',
            'cabang' => 'required|string|max:255',
            'akreditasi' => 'required|string|max:10',
            'siswa' => 'required|integer|min:0',
        ]);

        $school = SimandatSchool::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => "Satuan Pendidikan '{$school->nama}' berhasil ditambahkan ke Database Dapodik SIMANDAT.",
            'data' => $school,
        ], 201);
    }

    /**
     * Remove the specified school (Soft delete).
     */
    public function destroy($id)
    {
        $school = SimandatSchool::findOrFail($id);
        $school->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Data Sekolah '{$school->nama}' berhasil dinonaktifkan (Soft Deleted).",
            'id' => $id,
        ]);
    }

    private function seedInitialSchools()
    {
        $initialData = [
            ['npsn' => '60100001', 'nama' => 'SMA Negeri 1 Ambon', 'jenjang' => 'SMA', 'cabang' => 'Cabdin Wilayah I (Kota Ambon)', 'akreditasi' => 'A', 'siswa' => 1245],
            ['npsn' => '60100002', 'nama' => 'SMK Negeri 1 Ambon', 'jenjang' => 'SMK', 'cabang' => 'Cabdin Wilayah I (Kota Ambon)', 'akreditasi' => 'A', 'siswa' => 1450],
            ['npsn' => '60100003', 'nama' => 'SMA Negeri 2 Kei Kecil', 'jenjang' => 'SMA', 'cabang' => 'Cabdin Wilayah VI (Malra)', 'akreditasi' => 'A', 'siswa' => 680],
            ['npsn' => '60100004', 'nama' => 'SMK Kemaritiman Tanimbar', 'jenjang' => 'SMK', 'cabang' => 'Cabdin Wilayah IX (Tanimbar)', 'akreditasi' => 'B', 'siswa' => 420],
            ['npsn' => '60100005', 'nama' => 'SLB Negeri 1 Ambon', 'jenjang' => 'SLB', 'cabang' => 'Cabdin Wilayah I (Kota Ambon)', 'akreditasi' => 'A', 'siswa' => 180],
            ['npsn' => '60100006', 'nama' => 'SMA Negeri 1 Masohi', 'jenjang' => 'SMA', 'cabang' => 'Cabdin Wilayah II (Maluku Tengah)', 'akreditasi' => 'A', 'siswa' => 910],
            ['npsn' => '60100007', 'nama' => 'SMK Negeri 1 Namlea', 'jenjang' => 'SMK', 'cabang' => 'Cabdin Wilayah III (Buru)', 'akreditasi' => 'B', 'siswa' => 530],
            ['npsn' => '60100008', 'nama' => 'SMA Negeri 1 Piru', 'jenjang' => 'SMA', 'cabang' => 'Cabdin Wilayah IV (SBB)', 'akreditasi' => 'B', 'siswa' => 490],
        ];

        foreach ($initialData as $item) {
            SimandatSchool::create($item);
        }
    }
}
