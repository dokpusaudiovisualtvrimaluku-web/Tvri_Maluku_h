<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\SimandatArchive;
use App\Models\SimandatCulture;
use App\Models\SimandatDocument;
use App\Models\SimandatGtkLayanan;
use App\Models\SimandatKepegawaianLayanan;
use App\Models\SimandatPerencanaanProgram;
use App\Models\SimandatSarpras;
use App\Models\SimandatSchool;
use App\Models\SimandatSekretariat;
use App\Models\SimandatSlbLayanan;
use App\Models\SimandatSmaLayanan;
use App\Models\SimandatSmkLayanan;
use App\Models\SimandatStudent;
use App\Models\SimandatTeacher;
use App\Models\SimandatUptdBtkp;
use App\Models\SimandatUptdMuseum;
use App\Models\SimandatUptdTamanBudaya;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Display the Consolidated Integrated Report (Laporan Terpadu Disdikbud Maluku)
     */
    public function terpadu(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $sektor = $request->input('sektor', 'semua');

        // 1. Sector Summary Counters
        $statPendidikan = [
            'total_sekolah' => SimandatSchool::count(),
            'total_sma'     => SimandatSchool::where('jenjang', 'SMA')->count(),
            'total_smk'     => SimandatSchool::where('jenjang', 'SMK')->count(),
            'total_slb'     => SimandatSchool::where('jenjang', 'SLB')->count(),
            'total_siswa'   => (int) SimandatSchool::sum('siswa'),
            'total_guru'    => SimandatTeacher::count(),
            'total_sarpras' => SimandatSarpras::count(),
        ];

        // 2. Layanan Bidang Counters
        $statLayanan = [
            'sma'         => SimandatSmaLayanan::count(),
            'smk'         => SimandatSmkLayanan::count(),
            'gtk'         => SimandatGtkLayanan::count(),
            'slb'         => SimandatSlbLayanan::count(),
            'kepegawaian' => SimandatKepegawaianLayanan::count(),
            'sekretariat' => SimandatSekretariat::count(),
        ];
        $totalLayanan = array_sum($statLayanan);

        // 3. Kebudayaan & UPTD
        $statKebudayaan = [
            'total_cagar_budaya' => SimandatCulture::count(),
            'btkp'               => SimandatUptdBtkp::count(),
            'museum'             => SimandatUptdMuseum::count(),
            'taman_budaya'       => SimandatUptdTamanBudaya::count(),
        ];

        // 4. Perencanaan & Anggaran
        $perencanaanQuery = SimandatPerencanaanProgram::query();
        if ($tahun && $tahun !== 'semua') {
            $perencanaanQuery->where('tahun_anggaran', $tahun);
        }
        $totalPagu = (float) $perencanaanQuery->sum('pagu_anggaran');
        $totalRealisasi = (float) $perencanaanQuery->sum('realisasi_anggaran');
        $sisaAnggaran = max(0, $totalPagu - $totalRealisasi);
        $persenRealisasi = $totalPagu > 0 ? round(($totalRealisasi / $totalPagu) * 100, 2) : 0;

        $dokumenPerencanaanByType = [
            'Program'  => SimandatPerencanaanProgram::where('jenis_dokumen', 'Program')->count(),
            'Kegiatan' => SimandatPerencanaanProgram::where('jenis_dokumen', 'Kegiatan')->count(),
            'Renstra'  => SimandatPerencanaanProgram::where('jenis_dokumen', 'Renstra')->count(),
            'Renja'    => SimandatPerencanaanProgram::where('jenis_dokumen', 'Renja')->count(),
            'RKA'      => SimandatPerencanaanProgram::where('jenis_dokumen', 'RKA')->count(),
            'DPA'      => SimandatPerencanaanProgram::where('jenis_dokumen', 'DPA')->count(),
        ];

        // 5. Dokumen & Kearsipan Digital
        $statDokumen = [
            'dms_documents' => SimandatDocument::count(),
            'dms_folders'   => \App\Models\SimandatDocumentFolder::count(),
            'arsip_digital' => SimandatArchive::count(),
        ];

        // 6. Recent Program / Document List for Printable Table
        $programList = (clone $perencanaanQuery)->latest()->take(15)->get();

        return view('laporan.terpadu', compact(
            'tahun',
            'sektor',
            'statPendidikan',
            'statLayanan',
            'totalLayanan',
            'statKebudayaan',
            'totalPagu',
            'totalRealisasi',
            'sisaAnggaran',
            'persenRealisasi',
            'dokumenPerencanaanByType',
            'statDokumen',
            'programList'
        ));
    }

    /**
     * Display Statistical & Analytical Charts across 11 Kabupaten/Kota
     */
    public function statistik(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // 11 Kabupaten/Kota di Maluku
        $kabupatens = [
            'Kota Ambon',
            'Maluku Tengah',
            'Maluku Tenggara',
            'Kepulauan Tanimbar',
            'Buru',
            'Seram Bagian Timur',
            'Seram Bagian Barat',
            'Kepulauan Aru',
            'Maluku Barat Daya',
            'Buru Selatan',
            'Kota Tual',
        ];

        // Distribution of Schools and Students per Kab/Kota
        $sekolahPerKab = [];
        $siswaPerKab = [];
        foreach ($kabupatens as $kab) {
            $sekolahCount = SimandatSchool::where('cabang', 'like', "%{$kab}%")->count();
            $siswaSum = (int) SimandatSchool::where('cabang', 'like', "%{$kab}%")->sum('siswa');
            $sekolahPerKab[] = $sekolahCount;
            $siswaPerKab[] = $siswaSum;
        }

        // Service Distribution across 6 Units
        $layananLabels = ['SMA', 'SMK', 'GTK', 'SLB', 'Kepegawaian & Umum', 'Sekretariat Dinas'];
        $layananCounts = [
            SimandatSmaLayanan::count(),
            SimandatSmkLayanan::count(),
            SimandatGtkLayanan::count(),
            SimandatSlbLayanan::count(),
            SimandatKepegawaianLayanan::count(),
            SimandatSekretariat::count(),
        ];

        // Planning Budget by Bidang
        $bidangList = [
            'Sekretariat',
            'Pembinaan SMA',
            'Pembinaan SMK',
            'Pendidikan Khusus (SLB)',
            'GTK',
            'Kebudayaan',
            'UPTD BTKP',
            'UPTD Taman Budaya',
            'UPTD Museum',
        ];

        $paguPerBidang = [];
        $realisasiPerBidang = [];
        foreach ($bidangList as $bid) {
            $pagu = (float) SimandatPerencanaanProgram::where('bidang_penanggung_jawab', $bid)->sum('pagu_anggaran');
            $realisasi = (float) SimandatPerencanaanProgram::where('bidang_penanggung_jawab', $bid)->sum('realisasi_anggaran');
            $paguPerBidang[] = round($pagu / 1000000, 2); // In Millions (Juta Rupiah)
            $realisasiPerBidang[] = round($realisasi / 1000000, 2);
        }

        // Summary Aggregates
        $totalSekolah = SimandatSchool::count();
        $totalSiswa = (int) SimandatSchool::sum('siswa');
        $totalGuru = SimandatTeacher::count();
        $totalLayanan = array_sum($layananCounts);
        $totalPagu = (float) SimandatPerencanaanProgram::sum('pagu_anggaran');
        $totalRealisasi = (float) SimandatPerencanaanProgram::sum('realisasi_anggaran');
        $persenRealisasi = $totalPagu > 0 ? round(($totalRealisasi / $totalPagu) * 100, 1) : 0;

        return view('laporan.statistik', compact(
            'tahun',
            'kabupatens',
            'sekolahPerKab',
            'siswaPerKab',
            'layananLabels',
            'layananCounts',
            'bidangList',
            'paguPerBidang',
            'realisasiPerBidang',
            'totalSekolah',
            'totalSiswa',
            'totalGuru',
            'totalLayanan',
            'totalPagu',
            'totalRealisasi',
            'persenRealisasi'
        ));
    }

    /**
     * Export Consolidated Summary to CSV
     */
    public function exportTerpaduCsv(Request $request): StreamedResponse
    {
        $filename = 'laporan_terpadu_disdikbud_maluku_' . date('Ymd_His') . '.csv';

        $programs = SimandatPerencanaanProgram::orderBy('tahun_anggaran', 'desc')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($programs) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'NO',
                'NOMOR REGISTRASI',
                'JENIS DOKUMEN',
                'BIDANG PENANGGUNG JAWAB',
                'TAHUN ANGGARAN',
                'KODE REKENING',
                'NAMA PROGRAM / KEGIATAN',
                'PAGU ANGGARAN (RP)',
                'REALISASI ANGGARAN (RP)',
                'SISA ANGGARAN (RP)',
                'PERSENTASE REALISASI (%)',
                'STATUS',
                'PEJABAT PENANGGUNG JAWAB',
                'WAKTU INPUT',
            ]);

            foreach ($programs as $idx => $p) {
                $sisa = max(0, (float)$p->pagu_anggaran - (float)$p->realisasi_anggaran);
                fputcsv($handle, [
                    $idx + 1,
                    $p->nomor_registrasi,
                    $p->jenis_dokumen,
                    $p->bidang_penanggung_jawab,
                    $p->tahun_anggaran,
                    $p->kode_rekening ?: '-',
                    $p->nama_program_kegiatan,
                    $p->pagu_anggaran,
                    $p->realisasi_anggaran,
                    $sisa,
                    $p->persentase_realisasi,
                    $p->status_label,
                    $p->pejabat_penanggung_jawab ?: '-',
                    $p->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
