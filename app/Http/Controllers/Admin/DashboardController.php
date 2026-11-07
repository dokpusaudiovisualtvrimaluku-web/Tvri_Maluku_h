<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardMetricsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check()) {
            if (auth()->user()->username === 'berita') {
                return redirect()->route('tvri.berita.dashboard');
            }

            if (auth()->user()->username === 'keuangan' || auth()->user()->username === 'kasubag.keuangan') {
                return redirect()->route('tvri.keuangan.dashboard');
            }

            if (auth()->user()->username === 'kabid.kebudayaan') {
                return redirect()->route('kebudayaan.dashboard');
            }

            if (auth()->user()->username === 'kabid.ketenagaan') {
                return view('ketenagaan.dashboard');
            }

            if (auth()->user()->username === 'kabid.diksus') {
                $year = $request->query('year', date('Y'));
                
                $slbData = \App\Models\SimandatSlbLayanan::whereYear('created_at', $year)->get();
                
                $cards = [
                    'kelembagaan' => $slbData->where('jenis_layanan', 'Kelembagaan & Perizinan')->count(),
                    'kesiswaan' => $slbData->where('jenis_layanan', 'Kesiswaan & Mutasi')->count(),
                    'pendanaan' => $slbData->where('jenis_layanan', 'Pemerataan & Pendanaan')->count(),
                    'legalitas' => $slbData->where('jenis_layanan', 'Dokumen & Legalitas')->count(),
                ];
                
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
                $trend = [];
                foreach ($months as $idx => $m) {
                    $monthNum = $idx + 1;
                    $trend[] = $slbData->filter(function($item) use ($monthNum) {
                        return $item->created_at->format('n') == $monthNum;
                    })->count();
                }
                
                $kabupatens = [
                    'Kota Ambon', 'Maluku Tengah', 'Seram Bagian Barat', 'Seram Bagian Timur',
                    'Buru', 'Buru Selatan', 'Kepulauan Aru', 'Maluku Tenggara', 'Kota Tual',
                    'Kepulauan Tanimbar', 'Maluku Barat Daya'
                ];
                
                $tableData = [];
                foreach ($kabupatens as $kab) {
                    $kabData = $slbData->where('kabupaten_kota', $kab);
                    $tableData[] = [
                        'kabupaten' => $kab,
                        'total' => $kabData->count(),
                        'kelembagaan' => $kabData->where('jenis_layanan', 'Kelembagaan & Perizinan')->count(),
                        'kesiswaan' => $kabData->where('jenis_layanan', 'Kesiswaan & Mutasi')->count(),
                        'pendanaan' => $kabData->where('jenis_layanan', 'Pemerataan & Pendanaan')->count(),
                        'legalitas' => $kabData->where('jenis_layanan', 'Dokumen & Legalitas')->count(),
                    ];
                }
                
                return view('pendidikan.slb.dashboard', compact('cards', 'trend', 'tableData', 'year'));
            }

            if (auth()->user()->username === 'kabid.sma') {
                return view('pendidikan.sma.dashboard');
            }

            if (auth()->user()->username === 'kabid.smk') {
                $year = $request->query('year', date('Y'));
                
                $smkData = \App\Models\SimandatSmkLayanan::whereYear('created_at', $year)->get();
                
                $cards = [
                    'kesiswaan' => $smkData->where('jenis_layanan', 'Kesiswaan & Mutasi')->count(),
                    'pendanaan' => $smkData->where('jenis_layanan', 'Pemerataan & Pendanaan')->count(),
                    'legalitas' => $smkData->where('jenis_layanan', 'Dokumen & Legalitas')->count(),
                    'kurikulum' => $smkData->where('jenis_layanan', 'Kurikulum & Vokasi')->count(),
                    'kelembagaan' => $smkData->where('jenis_layanan', 'Kelembagaan & Perizinan')->count(),
                ];
                
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
                $trend = [];
                foreach ($months as $idx => $m) {
                    $monthNum = $idx + 1;
                    $trend[] = $smkData->filter(function($item) use ($monthNum) {
                        return $item->created_at->format('n') == $monthNum;
                    })->count();
                }
                
                $kabupatens = [
                    'Kota Ambon', 'Maluku Tengah', 'Seram Bagian Barat', 'Seram Bagian Timur',
                    'Buru', 'Buru Selatan', 'Kepulauan Aru', 'Maluku Tenggara', 'Kota Tual',
                    'Kepulauan Tanimbar', 'Maluku Barat Daya'
                ];
                
                $tableData = [];
                foreach ($kabupatens as $kab) {
                    $kabData = $smkData->where('kabupaten_kota', $kab);
                    $tableData[] = [
                        'kabupaten' => $kab,
                        'total' => $kabData->count(),
                        'kesiswaan' => $kabData->where('jenis_layanan', 'Kesiswaan & Mutasi')->count(),
                        'pendanaan' => $kabData->where('jenis_layanan', 'Pemerataan & Pendanaan')->count(),
                        'legalitas' => $kabData->where('jenis_layanan', 'Dokumen & Legalitas')->count(),
                        'kurikulum' => $kabData->where('jenis_layanan', 'Kurikulum & Vokasi')->count(),
                        'kelembagaan' => $kabData->where('jenis_layanan', 'Kelembagaan & Perizinan')->count(),
                    ];
                }
                
                return view('pendidikan.smk.dashboard', compact('cards', 'trend', 'tableData', 'year'));
            }
        }

        $dashboardData = DashboardMetricsService::getMetrics($request);

        return view('admin.dashboard', $dashboardData);
    }
}
