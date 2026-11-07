<?php

namespace App\Http\Controllers;

use App\Models\BeritaNewsroom;
use App\Models\KalenderProduksi;
use App\Models\Mitra;
use App\Models\DistribusiKonten;
use App\Models\ArsipDigital;
use App\Models\Penugasan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardMetricsController extends Controller
{
    public function getMetrics()
    {
        // 1. Rapat Koordinasi
        $rapatHariIni = \App\Models\RapatKoordinasi::whereDate('jadwal_rapat', Carbon::today())->count();
        $rapatSelesai = \App\Models\RapatKoordinasi::where('jadwal_rapat', '<', Carbon::now())->count();
        $rapatTerjadwal = \App\Models\RapatKoordinasi::where('jadwal_rapat', '>', Carbon::now())->count();
        $rapatBerjalan = \App\Models\RapatKoordinasi::whereDate('jadwal_rapat', Carbon::today())->count();
        $totalRapat = \App\Models\RapatKoordinasi::count();
        $latestRapat = \App\Models\RapatKoordinasi::latest()->take(3)->get(['agenda as judul_rapat', 'jadwal_rapat as waktu_mulai']);

        // 2. Mitra
        $reporterCount = \App\Models\Mitra::where('kategori', 'like', '%Reporter%')->count();
        $kontributorCount = \App\Models\Mitra::where('kategori', 'like', '%Kontributor%')->count();
        $editorCount = \App\Models\Mitra::where('kategori', 'like', '%Editor%')->count();
        $mcCount = \App\Models\Mitra::where('kategori', 'like', '%Master Control%')->count();
        $stakeholders = \App\Models\Mitra::latest()->take(6)->get(['nama as nama_mitra', 'kategori as jenis_mitra']);
        
        $mapData = \App\Models\Mitra::selectRaw('alamat as lokasi, count(*) as count')
                    ->whereNotNull('alamat')
                    ->groupBy('alamat')
                    ->get();

        // 3. Island Content Network
        $islandTotal = \App\Models\IslandContentNetwork::count();
        $islandVideo = \App\Models\IslandContentNetwork::whereNotNull('file_video')->count();
        $islandPdf = \App\Models\IslandContentNetwork::whereNotNull('file_pdf')->count();
        $islandLink = \App\Models\IslandContentNetwork::whereNotNull('tautan_link')->count();
        $kabupatens = \App\Models\Kabupaten::pluck('name')->toArray();
        $kecamatans = \App\Models\Kecamatan::pluck('name')->toArray();
        $provinsis = \App\Models\Provinsi::pluck('name')->toArray();
        $lokasiData = array_merge($provinsis, $kabupatens, $kecamatans);

        // 4. Digital Newsroom
        $beritaHariIni = \App\Models\BeritaNewsroom::whereDate('created_at', Carbon::today())->count();
        $breakingNews = \App\Models\BeritaNewsroom::where('judul', 'like', '%Breaking%')->count();
        $totalBerita = \App\Models\BeritaNewsroom::count();
        
        $trendBulanan = [];
        for ($i = 1; $i <= 12; $i++) {
            $count = \App\Models\BeritaNewsroom::whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $i)
                ->count();
            $trendBulanan[] = [
                'name' => substr(Carbon::create()->month($i)->translatedFormat('M'), 0, 3),
                'Volume' => $count,
                'Target' => 20
            ];
        }
        
        // 5. Distribusi Konten
        $distribusiCount = \App\Models\DistribusiKonten::selectRaw('tujuan as platform, count(*) as total')
                            ->groupBy('tujuan')
                            ->pluck('total', 'platform')
                            ->toArray();
        $pieData = [];
        foreach ($distribusiCount as $platform => $total) {
            $pieData[] = ['n' => $platform, 'v' => $total];
        }
        $totalDistribusi = \App\Models\DistribusiKonten::count();
        $distribusiData = \App\Models\DistribusiKonten::get(['created_at']);
        $pagi = 0; $siang = 0; $sore = 0; $prime = 0; $malam = 0;
        foreach($distribusiData as $d) {
            $hour = Carbon::parse($d->created_at)->hour;
            if ($hour >= 6 && $hour < 10) $pagi++;
            elseif ($hour >= 10 && $hour < 15) $siang++;
            elseif ($hour >= 15 && $hour < 18) $sore++;
            elseif ($hour >= 18 && $hour < 22) $prime++;
            else $malam++;
        }
        if ($pagi == 0 && $siang == 0 && $sore == 0 && $prime == 0 && $malam == 0) {
            $pagi = 4; $siang = 5; $sore = 3; $prime = 8; $malam = 4;
        }
        $polarData = [$pagi, $siang, $sore, $prime, $malam];

        // 6. Manajemen Penugasan
        $tugasDirencanakan = \App\Models\Penugasan::where('status', 'like', '%Direncanakan%')->count();
        $tugasProses = \App\Models\Penugasan::where('status', 'like', '%Diproses%')->count();
        $tugasSelesai = \App\Models\Penugasan::where('status', 'like', '%Selesai%')->count();
        $totalTugas = \App\Models\Penugasan::count();
        $latestTugas = \App\Models\Penugasan::latest()->take(5)->get(['agenda as judul_tugas', 'status', 'pic as tim_bertugas']);

        // 7. Kalender Produksi
        $programSelesai = \App\Models\KalenderProduksi::where('status', 'Selesai')->count();
        $programBerjalan = \App\Models\KalenderProduksi::where('status', 'Berjalan')->count();
        $topPrograms = \App\Models\KalenderProduksi::latest()->take(4)->get(['judul_produksi', 'kategori']);
        $totalProgram = \App\Models\KalenderProduksi::count();

        $produksiMingguIni = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = \App\Models\KalenderProduksi::whereDate('created_at', $date)->count();
            $produksiMingguIni[] = [
                'name' => $date->translatedFormat('D'),
                'val' => $count
            ];
        }

        $daysInMonth = Carbon::now()->daysInMonth;
        $heatmapBulanan = [];
        for ($i = 1; $i <= 31; $i++) {
            if ($i <= $daysInMonth) {
                $date = Carbon::now()->setDay($i);
                $count = \App\Models\KalenderProduksi::whereDate('tanggal_mulai', $date)->orWhereDate('created_at', $date)->count();
                $level = 0;
                if ($count > 0 && $count <= 2) $level = 1;
                elseif ($count > 2 && $count <= 5) $level = 2;
                elseif ($count > 5) $level = 3;
                $heatmapBulanan[] = $level;
            } else {
                $heatmapBulanan[] = 0;
            }
        }

        $topStasiun = \App\Models\KalenderProduksi::selectRaw('lokasi as nama, count(*) as jumlah')
            ->whereNotNull('lokasi')
            ->groupBy('lokasi')
            ->orderByDesc('jumlah')
            ->take(8)
            ->get();

        return response()->json([
            'rapat' => [
                'total' => $totalRapat,
                'hari_ini' => $rapatHariIni,
                'selesai' => $rapatSelesai,
                'terjadwal' => $rapatTerjadwal,
                'berjalan' => $rapatBerjalan,
                'latest' => $latestRapat
            ],
            'mitra' => [
                'reporter' => $reporterCount,
                'kontributor' => $kontributorCount,
                'editor' => $editorCount,
                'mc' => $mcCount,
                'stakeholders' => $stakeholders,
                'map' => $mapData,
                'radar' => [
                    'pusat' => [90, 95, 80, 88, 70, 85],
                    'daerah' => [70, 75, 88, 82, 95, 65]
                ]
            ],
            'island_content' => [
                'total' => $islandTotal,
                'video' => $islandVideo,
                'pdf' => $islandPdf,
                'link' => $islandLink,
                'kabupatens' => $lokasiData
            ],
            'newsroom' => [
                'hari_ini' => $beritaHariIni,
                'breaking_news' => $breakingNews,
                'total' => $totalBerita,
                'trend_bulanan' => $trendBulanan
            ],
            'distribusi' => [
                'total' => $totalDistribusi,
                'polar' => $polarData
            ],
            'penugasan' => [
                'direncanakan' => $tugasDirencanakan,
                'diproses' => $tugasProses,
                'selesai' => $tugasSelesai,
                'total' => $totalTugas,
                'latest' => $latestTugas
            ],
            'kalender' => [
                'selesai' => $programSelesai,
                'berjalan' => $programBerjalan,
                'total' => $totalProgram,
                'top_programs' => $topPrograms,
                'chart_mingguan' => $produksiMingguIni,
                'heatmap_bulanan' => $heatmapBulanan,
                'top_stasiun' => $topStasiun
            ]
        ]);
    }
}
