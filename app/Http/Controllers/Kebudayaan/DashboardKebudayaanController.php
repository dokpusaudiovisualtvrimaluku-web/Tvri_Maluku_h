<?php

namespace App\Http\Controllers\Kebudayaan;

use App\Http\Controllers\Controller;
use App\Models\SimandatCulture;
use Illuminate\Http\Request;

class DashboardKebudayaanController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->get('tahun', 'all');
        $selectedKab = $request->get('kabupaten', 'all');
        $selectedKategori = $request->get('kategori', 'all');

        $regencies = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        $kategoriList = SimandatCulture::KATEGORI_LIST;

        $query = SimandatCulture::query();

        if ($selectedKab !== 'all') {
            $query->where('kabupaten_kota', $selectedKab);
        }

        if ($selectedYear !== 'all') {
            $query->where('tahun', $selectedYear);
        }

        if ($selectedKategori !== 'all') {
            $query->where('kategori', $selectedKategori);
        }

        // Metrics Summary
        $metrics = [
            'total_kebudayaan' => (clone $query)->count(),
            'total_cagar_budaya' => (clone $query)->whereIn('kategori', ['Cagar Budaya', 'Situs Budaya', 'Museum'])->count(),
            'total_seni' => (clone $query)->whereIn('kategori', ['Seni Tradisional', 'Tarian Tradisional', 'Musik Tradisional'])->count(),
            'total_tradisi' => (clone $query)->whereIn('kategori', ['Warisan Budaya', 'Adat Istiadat', 'Bahasa Daerah', 'Permainan Tradisional', 'Kuliner Tradisional', 'Kerajinan'])->count(),
            'total_tokoh' => (clone $query)->whereIn('kategori', ['Tokoh Budaya', 'Sanggar Seni', 'Komunitas Budaya'])->count(),
            'total_event' => (clone $query)->where('kategori', 'Festival/Event Budaya')->count(),
            'total_tervalidasi' => (clone $query)->where('status_validasi', 'VALIDATED')->count(),
        ];

        // 1. Chart: Sebaran Objek Budaya per 16 Kategori
        $kategoriCounts = [];
        foreach ($kategoriList as $kat) {
            $kategoriCounts[] = (clone $query)->where('kategori', $kat)->count();
        }

        // 2. Chart: Sebaran Objek Budaya per 11 Wilayah
        $wilayahCounts = [];
        foreach ($regencies as $reg) {
            $wilayahCounts[] = (clone $query)->where('kabupaten_kota', $reg)->count();
        }

        // 3. Chart: Perkembangan Penetapan per Tahun
        $tahunLabels = ['2020', '2021', '2022', '2023', '2024', '2025', '2026'];
        $tahunCounts = [];
        foreach ($tahunLabels as $yr) {
            $tahunCounts[] = (clone $query)->where('tahun', $yr)->count();
        }

        $charts = [
            'kategori_labels' => $kategoriList,
            'kategori_counts' => $kategoriCounts,
            'wilayah_labels' => $regencies,
            'wilayah_counts' => $wilayahCounts,
            'tahun_labels' => $tahunLabels,
            'tahun_counts' => $tahunCounts,
        ];

        $recentCultures = (clone $query)->latest()->take(6)->get();
        $permohonanList = \App\Models\SimandatGtkLayanan::latest()->get();

        return view('kebudayaan.dashboard', [
            'metrics' => $metrics,
            'charts' => $charts,
            'kategoriList' => $kategoriList,
            'kabupatenList' => $regencies,
            'tahun' => $selectedYear,
            'kabupaten' => $selectedKab,
            'kategori' => $selectedKategori,
            'recentCultures' => $recentCultures,
            'permohonanList' => $permohonanList,
        ]);
    }
}
