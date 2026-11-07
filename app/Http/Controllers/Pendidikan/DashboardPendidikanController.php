<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\SimandatSchool;
use App\Models\SimandatTeacher;
use App\Models\SimandatStudent;
use App\Models\SimandatSarpras;
use Illuminate\Http\Request;

class DashboardPendidikanController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->get('tahun', '2026');
        $selectedKab = $request->get('kabupaten', 'all');
        $selectedJenjang = $request->get('jenjang', 'all');

        $regencies = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        // Base Queries
        $schoolQ = SimandatSchool::query();
        $teacherQ = SimandatTeacher::query();
        $studentQ = SimandatStudent::query();
        $sarprasQ = SimandatSarpras::query();

        if ($selectedKab !== 'all') {
            $schoolQ->where(function ($q) use ($selectedKab) {
                $q->where('kabupaten_kota', $selectedKab)->orWhere('cabang', 'like', "%{$selectedKab}%");
            });
            $teacherQ->where('kabupaten_kota', $selectedKab);
            $studentQ->where('kabupaten_kota', $selectedKab);
            $sarprasQ->where('kabupaten_kota', $selectedKab);
        }

        if ($selectedJenjang !== 'all') {
            $schoolQ->where('jenjang', $selectedJenjang);
            $studentQ->where('jenjang', $selectedJenjang);
        }

        // Stats Summary
        $stats = [
            'total_sekolah' => (clone $schoolQ)->count(),
            'total_guru' => (clone $teacherQ)->count(),
            'total_siswa' => (clone $studentQ)->count(),
            'total_sarpras' => (clone $sarprasQ)->sum('jumlah') ?: 0,
            'sekolah_tervalidasi' => (clone $schoolQ)->where('status_validasi', 'VALIDATED')->count(),
            'guru_sertifikasi' => (clone $teacherQ)->where('sertifikasi', 1)->count(),
        ];

        // Chart 1: Sekolah, Guru, Siswa per Kabupaten/Kota
        $schoolsPerKab = [];
        $teachersPerKab = [];
        $studentsPerKab = [];
        foreach ($regencies as $reg) {
            $schoolsPerKab[] = SimandatSchool::where(function($q) use ($reg) {
                $q->where('kabupaten_kota', $reg)->orWhere('cabang', 'like', "%{$reg}%");
            })->count();
            $teachersPerKab[] = SimandatTeacher::where('kabupaten_kota', $reg)->count();
            $studentsPerKab[] = SimandatStudent::where('kabupaten_kota', $reg)->count();
        }

        // Chart 2: Sekolah per Jenjang
        $jenjangLabels = ['PAUD', 'TK', 'SD', 'SMP', 'SMA', 'SMK', 'SLB'];
        $jenjangCounts = [];
        foreach ($jenjangLabels as $j) {
            $jenjangCounts[] = (clone $schoolQ)->where('jenjang', $j)->count();
        }

        // Chart 3: Akreditasi Breakdown
        $akreditasiLabels = ['A', 'B', 'C', 'Belum Terakreditasi'];
        $akreditasiCounts = [];
        foreach ($akreditasiLabels as $ak) {
            $akreditasiCounts[] = (clone $schoolQ)->where('akreditasi', $ak)->count();
        }

        // Chart 4: Sarpras Condition Summary
        $sarprasKondisi = [
            'baik' => (clone $sarprasQ)->sum('kondisi_baik') ?: 0,
            'rusak_ringan' => (clone $sarprasQ)->sum('rusak_ringan') ?: 0,
            'rusak_berat' => (clone $sarprasQ)->sum('rusak_berat') ?: 0,
        ];

        $charts = [
            'kabupaten_labels' => $regencies,
            'schools_per_kab' => $schoolsPerKab,
            'teachers_per_kab' => $teachersPerKab,
            'students_per_kab' => $studentsPerKab,
            'jenjang_labels' => $jenjangLabels,
            'jenjang_counts' => $jenjangCounts,
            'akreditasi_labels' => $akreditasiLabels,
            'akreditasi_counts' => $akreditasiCounts,
        ];

        // Recent Updates
        $recentSchools = SimandatSchool::latest()->take(5)->get();

        return view('pendidikan.dashboard', [
            'stats' => $stats,
            'charts' => $charts,
            'kabupatenList' => $regencies,
            'tahun' => $selectedYear,
            'kabupaten' => $selectedKab,
            'jenjang' => $selectedJenjang,
            'sarprasKondisi' => $sarprasKondisi,
            'recentSchools' => $recentSchools,
        ]);
    }
}
