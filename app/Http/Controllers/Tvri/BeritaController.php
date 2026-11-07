<?php

namespace App\Http\Controllers\Tvri;

use App\Http\Controllers\Controller;
use App\Models\TvriBerita;
use App\Models\TvriBeritaAgendaLiputan;
use App\Models\TvriBeritaCatatan;
use App\Models\TvriBeritaDokumentasi;
use App\Models\TvriBeritaMonitoring;
use App\Models\TvriBeritaProduksi;
use App\Models\TvriBeritaRealtimeFeed;
use App\Models\TvriBeritaRingkasan;
use App\Models\TvriBeritaSop;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public const STATUS = ['Selesai', 'Dalam Proses', 'Terkendala', 'Batal', 'Rencana'];

    public const KATEGORI = [
        'Pemerintahan', 'Sosial Masyarakat', 'Ekonomi & UMKM', 'Pendidikan',
        'Kesehatan', 'Pariwisata', 'Budaya', 'Olahraga', 'Hukum & Kriminal', 'Lainnya'
    ];

    public const JENIS = ['Berita Reguler', 'Current Affairs', 'Breaking News', 'Live Report', 'Feature'];

    public const LOKASI = [
        'Ambon', 'Maluku Tengah', 'SBB', 'SBT', 'Buru', 'Buru Selatan',
        'Maluku Tenggara', 'Kota Tual', 'Kep. Aru', 'Kepulauan Tanimbar', 'Maluku Barat Daya', 'Lainnya'
    ];

    /**
     * Tampilan Dashboard Utama Berita (Newsroom Infografis)
     */
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'September 2025');

        // 1. Ringkasan & Metrik Utama
        $ringkasan = TvriBeritaRingkasan::firstOrCreate(
            ['periode' => $periode],
            [
                'rencana_liputan' => 120,
                'liputan_dilakukan' => 102,
                'berita_diproduksi' => 95,
                'berita_tayang' => 92,
                'views_digital' => 1200000,
                'performance_index' => 92,
                'kecepatan_publikasi_menit' => 45,
                'dist_tv' => 92,
                'dist_website' => 95,
                'dist_youtube' => 82,
                'dist_instagram' => 60,
                'dist_tiktok' => 48,
                'kualitas_fakta' => 98,
                'kualitas_etik' => 96,
                'kualitas_akurasi' => 97,
                'kualitas_visual' => 94,
                'kualitas_sop' => 95,
            ]
        );

        // 2. Kategori Berita (Donut Chart)
        $kategoriColors = [
            'Pemerintahan' => '#3b82f6',
            'Sosial Masyarakat' => '#10b981',
            'Ekonomi & UMKM' => '#06b6d4',
            'Pendidikan' => '#f59e0b',
            'Kesehatan' => '#ec4899',
            'Pariwisata' => '#8b5cf6',
            'Budaya' => '#f97316',
            'Olahraga' => '#14b8a6',
            'Hukum & Kriminal' => '#6366f1',
            'Lainnya' => '#64748b',
        ];

        $kategoriDb = TvriBerita::selectRaw('kategori, COUNT(*) as jumlah')
            ->whereNotNull('kategori')
            ->groupBy('kategori')
            ->pluck('jumlah', 'kategori')
            ->toArray();

        $defaultKategori = [
            'Pemerintahan' => ['jumlah' => 20, 'persen' => 15],
            'Sosial Masyarakat' => ['jumlah' => 20, 'persen' => 15],
            'Ekonomi & UMKM' => ['jumlah' => 20, 'persen' => 15],
            'Pendidikan' => ['jumlah' => 20, 'persen' => 15],
            'Kesehatan' => ['jumlah' => 20, 'persen' => 15],
            'Pariwisata' => ['jumlah' => 20, 'persen' => 15],
            'Budaya' => ['jumlah' => 6, 'persen' => 4],
            'Olahraga' => ['jumlah' => 5, 'persen' => 4],
            'Hukum & Kriminal' => ['jumlah' => 4, 'persen' => 3],
            'Lainnya' => ['jumlah' => 2, 'persen' => 1],
        ];

        $totalKategoriDb = array_sum($kategoriDb);
        $kategoriList = [];
        if ($totalKategoriDb > 0) {
            foreach ($kategoriColors as $kat => $color) {
                $jml = $kategoriDb[$kat] ?? 0;
                $persen = round(($jml / $totalKategoriDb) * 100);
                $kategoriList[$kat] = [
                    'nama' => $kat,
                    'jumlah' => $jml,
                    'persen' => $persen,
                    'color' => $color,
                ];
            }
        } else {
            foreach ($defaultKategori as $kat => $val) {
                $kategoriList[$kat] = [
                    'nama' => $kat,
                    'jumlah' => $val['jumlah'],
                    'persen' => $val['persen'],
                    'color' => $kategoriColors[$kat] ?? '#3b82f6',
                ];
            }
        }

        // 3. Status Liputan
        $statusColors = [
            'Selesai' => '#10b981',
            'Dalam Proses' => '#3b82f6',
            'Terkendala' => '#f59e0b',
            'Batal' => '#ef4444',
        ];

        $statusCounts = TvriBerita::selectRaw('status, COUNT(*) as jumlah')
            ->whereNotNull('status')
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();

        $totalStatusDb = array_sum($statusCounts);
        if ($totalStatusDb > 0) {
            $statusList = [];
            foreach (['Selesai', 'Dalam Proses', 'Terkendala', 'Batal'] as $st) {
                $jml = $statusCounts[$st] ?? 0;
                $persen = round(($jml / $totalStatusDb) * 100);
                $statusList[$st] = [
                    'jumlah' => $jml,
                    'persen' => $persen,
                    'color' => $statusColors[$st],
                ];
            }
        } else {
            $statusList = [
                'Selesai' => ['jumlah' => 60, 'persen' => 85, 'color' => '#10b981'],
                'Dalam Proses' => ['jumlah' => 20, 'persen' => 8, 'color' => '#3b82f6'],
                'Terkendala' => ['jumlah' => 20, 'persen' => 4, 'color' => '#f59e0b'],
                'Batal' => ['jumlah' => 20, 'persen' => 3, 'color' => '#ef4444'],
            ];
        }

        // 4. Sebaran Lokasi Liputan
        $lokasiDb = TvriBerita::selectRaw('lokasi, COUNT(*) as jumlah')
            ->whereNotNull('lokasi')
            ->groupBy('lokasi')
            ->pluck('jumlah', 'lokasi')
            ->toArray();

        if (array_sum($lokasiDb) > 0) {
            $sebaranLokasi = [];
            foreach (self::LOKASI as $lok) {
                $sebaranLokasi[$lok] = $lokasiDb[$lok] ?? 0;
            }
        } else {
            $sebaranLokasi = [
                'Ambon' => 24,
                'Maluku Tengah' => 24,
                'SBB' => 24,
                'SBT' => 24,
                'Buru' => 24,
                'Maluku Tenggara' => 7,
                'Kep. Aru' => 6,
                'Maluku Barat Daya' => 5,
                'Lainnya' => 11,
            ];
        }

        // 5. Jenis Penyiaran Berita
        $jenisDb = TvriBerita::selectRaw('jenis, COUNT(*) as jumlah')
            ->whereNotNull('jenis')
            ->groupBy('jenis')
            ->pluck('jumlah', 'jenis')
            ->toArray();

        $totalJenisDb = array_sum($jenisDb);
        $jenisColors = [
            'Berita Reguler' => '#3b82f6',
            'Current Affairs' => '#10b981',
            'Breaking News' => '#ef4444',
            'Live Report' => '#06b6d4',
            'Feature' => '#8b5cf6',
        ];

        if ($totalJenisDb > 0) {
            $jenisPenyiaran = [];
            foreach ($jenisColors as $jName => $color) {
                $jml = $jenisDb[$jName] ?? 0;
                $persen = round(($jml / $totalJenisDb) * 100);
                $jenisPenyiaran[$jName] = [
                    'jumlah' => $jml,
                    'persen' => $persen,
                    'color' => $color,
                ];
            }
        } else {
            $jenisPenyiaran = [
                'Berita Reguler' => ['jumlah' => 40, 'persen' => 63, 'color' => '#3b82f6'],
                'Current Affairs' => ['jumlah' => 40, 'persen' => 19, 'color' => '#10b981'],
                'Breaking News' => ['jumlah' => 40, 'persen' => 9, 'color' => '#ef4444'],
                'Live Report' => ['jumlah' => 6, 'persen' => 7, 'color' => '#06b6d4'],
                'Feature' => ['jumlah' => 5, 'persen' => 5, 'color' => '#8b5cf6'],
            ];
        }

        // 6. Real Time Newsroom Feeds
        $realtimeFeeds = TvriBeritaRealtimeFeed::orderBy('urutan')->get();

        // 7. Top 5 Berita Digital (Views tertinggi)
        $topBeritaDb = TvriBerita::where('views', '>', 0)
            ->orderByDesc('views')
            ->take(5)
            ->get();
        if ($topBeritaDb->isNotEmpty()) {
            $topBerita = $topBeritaDb->map(function ($b) {
                $tgl = $b->tanggal_tayang 
                    ? (is_string($b->tanggal_tayang) ? date('j M Y', strtotime($b->tanggal_tayang)) : $b->tanggal_tayang->format('j M Y')) 
                    : ($b->tanggal_rencana ? (is_string($b->tanggal_rencana) ? date('j M Y', strtotime($b->tanggal_rencana)) : $b->tanggal_rencana->format('j M Y')) : date('j M Y'));

                return (object) [
                    'id' => $b->id,
                    'judul' => $b->judul,
                    'views' => (int) $b->views,
                    'tanggal' => $tgl,
                    'kategori' => $b->kategori ?? 'Pemerintahan',
                ];
            })->all();
        } else {
            $topBerita = [
                (object) ['id' => 120, 'judul' => 'Liputan Undangan Rapat #120', 'views' => 17000, 'tanggal' => '2 Sep 2025', 'kategori' => 'Pemerintahan'],
                (object) ['id' => 114, 'judul' => 'Liputan Pedoman Sarana Siaran #114', 'views' => 16400, 'tanggal' => '2 Sep 2025', 'kategori' => 'Pemberitaan'],
                (object) ['id' => 108, 'judul' => 'Liputan Permohonan Narasumber #108', 'views' => 15800, 'tanggal' => '2 Sep 2025', 'kategori' => 'Sosial Masyarakat'],
                (object) ['id' => 102, 'judul' => 'Liputan Laporan Kegiatan #102', 'views' => 15200, 'tanggal' => '2 Sep 2025', 'kategori' => 'Ekonomi & UMKM'],
                (object) ['id' => 96, 'judul' => 'Liputan Undangan Rapat #96', 'views' => 14600, 'tanggal' => '2 Sep 2025', 'kategori' => 'Pemerintahan'],
            ];
        }

        // 8. Berita Terbaru (Video Cards & Siap Siar - Sinkron Penuh dengan Kelola Berita)
        $latestDb = self::getOrSeedBeritas();

        $broadcastSamples = [
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4',
        ];

        $beritaTerbaru = $latestDb->map(function ($b, $index) use ($broadcastSamples) {
            $tgl = $b->tanggal_tayang 
                ? (is_string($b->tanggal_tayang) ? date('j M Y', strtotime($b->tanggal_tayang)) : $b->tanggal_tayang->format('j M Y')) 
                : ($b->tanggal_rencana ? (is_string($b->tanggal_rencana) ? date('j M Y', strtotime($b->tanggal_rencana)) : $b->tanggal_rencana->format('j M Y')) : date('j M Y'));

            return [
                'id' => $b->id,
                'judul' => $b->judul,
                'durasi' => $b->durasi ?: '02:00',
                'tanggal' => $tgl,
                'lokasi' => $b->lokasi ?? 'Ambon',
                'kategori' => $b->kategori ?? 'Pemerintahan',
                'video_url' => $b->video_url,
                'playable_url' => $b->video_url ?: $broadcastSamples[$index % count($broadcastSamples)],
                'video_file' => $b->video_file,
                'status' => $b->status,
            ];
        })->all();

        // 9. Agenda Liputan Hari Ini (Sinkron dengan Jadwal Peliputan Berita)
        $agendaLiputan = TvriBeritaAgendaLiputan::orderBy('tanggal')->orderBy('mulai')->orderBy('urutan')->get();

        // Metrik Ringkasan Peliputan
        $totalLiputan = $agendaLiputan->count();
        $siaranLangsung = $agendaLiputan->filter(fn($a) => $a->jenis === 'Live' || stripos($a->agenda, 'live') !== false || $a->status === 'On Air')->count();
        $prioritasPenting = $agendaLiputan->whereIn('prioritas', ['Penting', 'Mendesak'])->count();
        $crews = collect();
        foreach ($agendaLiputan as $ag) {
            if (!empty($ag->reporter)) $crews->push($ag->reporter);
            if (!empty($ag->kamerawan)) $crews->push($ag->kamerawan);
            if (!empty($ag->driver) && $ag->driver !== '-') $crews->push($ag->driver);
        }
        $crewBertugas = $crews->unique()->count();

        // 10. Catatan & Peringatan Redaksi
        $catatanPeringatan = TvriBeritaCatatan::orderBy('urutan')->get();

        // 11. Dokumen & SOP Newsroom
        $sops = self::getOrSeedSops();

        // 12. Produksi Paket Berita, Monitoring Harian & Dokumentasi Berita
        $produksis = self::getOrSeedProduksis();
        $monitorings = self::getOrSeedMonitorings();
        $dokumentasis = self::getOrSeedDokumentasis();

        return view('tvri.berita.dashboard', compact(
            'ringkasan', 'kategoriList', 'statusList', 'sebaranLokasi', 'jenisPenyiaran',
            'realtimeFeeds', 'topBerita', 'beritaTerbaru', 'agendaLiputan', 'catatanPeringatan', 'periode', 'sops',
            'totalLiputan', 'siaranLangsung', 'prioritasPenting', 'crewBertugas',
            'produksis', 'monitorings', 'dokumentasis'
        ));
    }

    /**
     * Halaman Manajemen / CRUD Data Bidang Berita (Newsroom Manager)
     * Dapat diakses oleh semua akun untuk mengelola data dan menghapus file
     */
    public function manage(Request $request)
    {
        $periode = $request->get('periode', 'September 2025');
        $tab = $request->get('tab', 'berita');

        $ringkasan = TvriBeritaRingkasan::firstOrCreate(['periode' => $periode]);
        self::getOrSeedBeritas();
        $beritas = TvriBerita::orderByDesc('id')->paginate(15);
        $realtimeFeeds = TvriBeritaRealtimeFeed::orderBy('urutan')->get();
        $agendaLiputan = TvriBeritaAgendaLiputan::orderBy('tanggal')->orderBy('mulai')->orderBy('urutan')->get();
        $catatans = TvriBeritaCatatan::orderBy('urutan')->get();
        $sops = self::getOrSeedSops();
        $produksis = self::getOrSeedProduksis();
        $monitorings = self::getOrSeedMonitorings();
        $dokumentasis = self::getOrSeedDokumentasis();

        // Metrik Ringkasan Peliputan
        $totalLiputan = $agendaLiputan->count();
        $siaranLangsung = $agendaLiputan->filter(fn($a) => $a->jenis === 'Live' || stripos($a->agenda, 'live') !== false || $a->status === 'On Air')->count();
        $prioritasPenting = $agendaLiputan->whereIn('prioritas', ['Penting', 'Mendesak'])->count();
        $crews = collect();
        foreach ($agendaLiputan as $ag) {
            if (!empty($ag->reporter)) $crews->push($ag->reporter);
            if (!empty($ag->kamerawan)) $crews->push($ag->kamerawan);
            if (!empty($ag->driver) && $ag->driver !== '-') $crews->push($ag->driver);
        }
        $crewBertugas = $crews->unique()->count();

        return view('tvri.berita.manage', compact(
            'ringkasan', 'beritas', 'realtimeFeeds', 'agendaLiputan', 'catatans', 'periode', 'tab', 'sops',
            'totalLiputan', 'siaranLangsung', 'prioritasPenting', 'crewBertugas',
            'produksis', 'monitorings', 'dokumentasis'
        ));
    }

    /**
     * 1. Update Ringkasan & Metrik Newsroom
     */
    public function updateRingkasan(Request $request)
    {
        $validated = $request->validate([
            'periode' => 'required|string',
            'rencana_liputan' => 'nullable|integer|min:0',
            'liputan_dilakukan' => 'nullable|integer|min:0',
            'berita_diproduksi' => 'nullable|integer|min:0',
            'berita_tayang' => 'nullable|integer|min:0',
            'views_digital' => 'nullable|integer|min:0',
            'performance_index' => 'nullable|integer|min:0|max:100',
            'kecepatan_publikasi_menit' => 'nullable|integer|min:0',
            'dist_tv' => 'nullable|integer|min:0',
            'dist_website' => 'nullable|integer|min:0',
            'dist_youtube' => 'nullable|integer|min:0',
            'dist_instagram' => 'nullable|integer|min:0',
            'dist_tiktok' => 'nullable|integer|min:0',
            'kualitas_fakta' => 'nullable|integer|min:0|max:100',
            'kualitas_etik' => 'nullable|integer|min:0|max:100',
            'kualitas_akurasi' => 'nullable|integer|min:0|max:100',
            'kualitas_visual' => 'nullable|integer|min:0|max:100',
            'kualitas_sop' => 'nullable|integer|min:0|max:100',
        ]);

        $numericFields = [
            'rencana_liputan',
            'liputan_dilakukan',
            'berita_diproduksi',
            'berita_tayang',
            'views_digital',
            'performance_index',
            'kecepatan_publikasi_menit',
            'dist_tv',
            'dist_website',
            'dist_youtube',
            'dist_instagram',
            'dist_tiktok',
            'kualitas_fakta',
            'kualitas_etik',
            'kualitas_akurasi',
            'kualitas_visual',
            'kualitas_sop',
        ];

        foreach ($numericFields as $field) {
            if (array_key_exists($field, $validated)) {
                $validated[$field] = $validated[$field] !== null ? (int) $validated[$field] : 0;
            }
        }

        $ringkasan = TvriBeritaRingkasan::firstOrCreate(['periode' => $request->periode]);
        $ringkasan->update($validated);

        return redirect()->route('tvri.berita.manage', ['periode' => $request->periode, 'tab' => 'ringkasan'])
            ->with('success', 'Metrik & Ringkasan Newsroom berhasil diperbarui.');
    }

    /**
     * 2. CRUD Data Berita & Berita Terbaru (Dengan Upload Video Max 500 MB)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'jenis' => 'required|string',
            'lokasi' => 'nullable|string',
            'status' => 'required|string',
            'reporter' => 'nullable|string|max:100',
            'durasi' => 'nullable|string|max:20',
            'tanggal_rencana' => 'required|date',
            'tanggal_tayang' => 'nullable|date',
            'views' => 'nullable|integer|min:0',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg,mov,mkv,avi|max:512000', // Resolusi/ukuran file maksimal 500 MB
        ]);

        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $path = $file->store('berita_videos', 'public');
            $validated['video_file'] = $path;
        }

        if (empty($validated['tanggal_tayang']) && ($validated['status'] ?? '') === 'Selesai') {
            $validated['tanggal_tayang'] = $validated['tanggal_rencana'] ?? now()->toDateString();
        }

        $berita = TvriBerita::create($validated);

        ActivityLogger::log('BERITA_CREATED', "Menambahkan Berita Baru: {$berita->judul}" . ($berita->video_file ? " dengan upload video (" . round($request->file('video_file')->getSize() / 1024 / 1024, 2) . " MB)" : ""));

        return redirect()->route('tvri.berita.manage', ['tab' => 'berita'])
            ->with('success', 'Berita baru' . ($berita->video_file ? ' dan file video (maks 500 MB)' : '') . ' berhasil disimpan.');
    }

    public function update(Request $request, TvriBerita $berita)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'jenis' => 'required|string',
            'lokasi' => 'nullable|string',
            'status' => 'required|string',
            'reporter' => 'nullable|string|max:100',
            'durasi' => 'nullable|string|max:20',
            'tanggal_rencana' => 'required|date',
            'tanggal_tayang' => 'nullable|date',
            'views' => 'nullable|integer|min:0',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg,mov,mkv,avi|max:512000', // Resolusi/ukuran file maksimal 500 MB
        ]);

        if ($request->hasFile('video_file')) {
            // Hapus file video lama jika ada
            if ($berita->video_file && Storage::disk('public')->exists($berita->video_file)) {
                Storage::disk('public')->delete($berita->video_file);
            }
            $file = $request->file('video_file');
            $path = $file->store('berita_videos', 'public');
            $validated['video_file'] = $path;
        } else {
            // Jangan timpa file video yang sudah ada jika tidak ada file baru yang diunggah
            unset($validated['video_file']);
        }

        if (!isset($validated['views']) || $validated['views'] === null) {
            unset($validated['views']);
        }

        if (empty($validated['tanggal_tayang'])) {
            if ($berita->tanggal_tayang) {
                $validated['tanggal_tayang'] = $berita->tanggal_tayang;
            } elseif (($validated['status'] ?? '') === 'Selesai') {
                $validated['tanggal_tayang'] = $validated['tanggal_rencana'] ?? now()->toDateString();
            }
        }

        $berita->update($validated);

        ActivityLogger::log('BERITA_UPDATED', "Memperbarui Berita: {$berita->judul}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'berita'])
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(TvriBerita $berita)
    {
        $judul = $berita->judul;
        // Hapus file video terkait di storage disk
        if ($berita->video_file && Storage::disk('public')->exists($berita->video_file)) {
            Storage::disk('public')->delete($berita->video_file);
        }
        $berita->delete();

        ActivityLogger::log('BERITA_DELETED', "Menghapus Berita: {$judul}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'berita'])
            ->with('success', 'Berita dan file terkait berhasil dihapus.');
    }

    /**
     * Hapus Massal Berita & File Video Terkait via Kotak Central (Semua Akun)
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $beritas = TvriBerita::whereIn('id', $ids)->get();
            $fileCount = 0;
            foreach ($beritas as $b) {
                if ($b->video_file && Storage::disk('public')->exists($b->video_file)) {
                    Storage::disk('public')->delete($b->video_file);
                    $fileCount++;
                }
                $b->delete();
            }
            ActivityLogger::log('BERITA_BULK_DELETED', "Menghapus " . count($ids) . " data berita dan {$fileCount} file video melalui kotak central.");
        }

        return redirect()->route('tvri.berita.manage', ['tab' => $request->get('tab', 'berita')])
            ->with('success', count($ids) . " data berita dan file video berhasil dihapus melalui kotak central.");
    }

    /**
     * Hapus Hanya File Video pada Berita Tertentu
     */
    public function deleteVideo($id)
    {
        $berita = TvriBerita::findOrFail($id);
        if ($berita->video_file && Storage::disk('public')->exists($berita->video_file)) {
            Storage::disk('public')->delete($berita->video_file);
        }
        $berita->update(['video_file' => null]);

        ActivityLogger::log('BERITA_VIDEO_DELETED', "Menghapus file video pada berita: {$berita->judul}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'berita'])
            ->with('success', "File video pada berita '{$berita->judul}' berhasil dihapus.");
    }

    /**
     * 3. CRUD Real Time Newsroom Live Feeds
     */
    public function storeRealtime(Request $request)
    {
        $request->validate([
            'waktu' => 'required|string|max:10',
            'kegiatan' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
        ]);

        TvriBeritaRealtimeFeed::create([
            'waktu' => $request->waktu,
            'kegiatan' => $request->kegiatan,
            'urutan' => $request->urutan ?? 1,
            'status' => 'LIVE',
        ]);

        return redirect()->route('tvri.berita.manage', ['tab' => 'realtime'])
            ->with('success', 'Feed Real Time Newsroom berhasil ditambahkan.');
    }

    public function updateRealtime(Request $request, $id)
    {
        $request->validate([
            'waktu' => 'required|string|max:10',
            'kegiatan' => 'required|string|max:255',
        ]);

        $feed = TvriBeritaRealtimeFeed::findOrFail($id);
        $feed->update($request->only(['waktu', 'kegiatan']));

        return redirect()->route('tvri.berita.manage', ['tab' => 'realtime'])
            ->with('success', 'Feed Real Time Newsroom berhasil diperbarui.');
    }

    public function destroyRealtime($id)
    {
        $feed = TvriBeritaRealtimeFeed::findOrFail($id);
        $feed->delete();

        return redirect()->route('tvri.berita.manage', ['tab' => 'realtime'])
            ->with('success', 'Feed Real Time Newsroom berhasil dihapus.');
    }

    public function bulkDestroyRealtime(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = TvriBeritaRealtimeFeed::whereIn('id', $ids)->count();
            TvriBeritaRealtimeFeed::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.berita.manage', ['tab' => 'realtime'])
                ->with('success', "Berhasil menghapus {$count} feed live newsroom terpilih.");
        }
        return redirect()->route('tvri.berita.manage', ['tab' => 'realtime'])
            ->with('error', 'Tidak ada feed live yang dipilih untuk dihapus.');
    }

    /**
     * 4. CRUD Agenda Liputan (Sinkron dengan Jadwal Peliputan Berita)
     */
    public function storeAgenda(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
            'waktu' => 'nullable|string|max:50',
            'mulai' => 'nullable|string|max:10',
            'selesai' => 'nullable|string|max:10',
            'durasi_label' => 'nullable|string|max:50',
            'agenda' => 'required|string|max:255',
            'jenis' => 'nullable|string|max:50',
            'kategori' => 'nullable|string|max:100',
            'lokasi' => 'required|string|max:255',
            'pic' => 'nullable|string|max:255',
            'kendaraan' => 'nullable|string|max:100',
            'reporter' => 'nullable|string|max:100',
            'kamerawan' => 'nullable|string|max:100',
            'driver' => 'nullable|string|max:100',
            'koordinator' => 'nullable|string|max:100',
            'status' => 'required|string|max:50',
            'prioritas' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
            'link' => 'nullable|string|max:500',
            'urutan' => 'nullable|integer',
        ]);

        if (empty($validated['waktu']) && !empty($validated['mulai']) && !empty($validated['selesai'])) {
            $validated['waktu'] = $validated['mulai'] . ' - ' . $validated['selesai'];
        } elseif (empty($validated['waktu'])) {
            $validated['waktu'] = '08:00 - 10:00';
        }

        if (empty($validated['durasi_label']) && !empty($validated['mulai']) && !empty($validated['selesai'])) {
            $mParts = explode(':', $validated['mulai']);
            $sParts = explode(':', $validated['selesai']);
            $diffMinutes = ((int)($sParts[0] ?? 0) * 60 + (int)($sParts[1] ?? 0)) - ((int)($mParts[0] ?? 0) * 60 + (int)($mParts[1] ?? 0));
            $hours = floor($diffMinutes / 60);
            $mins = $diffMinutes % 60;
            $lbl = $hours > 0 ? "{$hours} jam" : "";
            if ($mins > 0) $lbl .= ($lbl ? " " : "") . "{$mins} mnt";
            $validated['durasi_label'] = $lbl ?: '2 jam';
        }

        if (empty($validated['pic'])) {
            $pic = trim(($validated['reporter'] ?? '') . ' (Rep) & ' . ($validated['kamerawan'] ?? '') . ' (Cam)');
            if (!empty($validated['driver']) && $validated['driver'] !== '-') {
                $pic .= ' & ' . $validated['driver'] . ' (Drv)';
            }
            $validated['pic'] = $pic ?: 'Tim Redaksi';
        }

        if (empty($validated['kode'])) {
            $tglCode = !empty($validated['tanggal']) ? str_replace('-', '', $validated['tanggal']) : date('Ymd');
            $countSameDay = TvriBeritaAgendaLiputan::where('tanggal', $validated['tanggal'] ?? date('Y-m-d'))->count() + 1;
            $validated['kode'] = 'LP-' . $tglCode . '-' . str_pad($countSameDay, 2, '0', STR_PAD_LEFT);
        }

        if (empty($validated['link']) && !empty($validated['lokasi'])) {
            $validated['link'] = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($validated['lokasi']);
        }

        $agenda = TvriBeritaAgendaLiputan::create($validated);

        // Sinkronisasi otomatis ke TvriBerita agar terbaca langsung di metrik & daftar berita newsroom
        TvriBerita::updateOrCreate(
            ['judul' => $agenda->agenda],
            [
                'kategori' => $agenda->kategori ?: 'Pemerintahan',
                'jenis' => $agenda->jenis === 'Live' ? 'Live Report' : ($agenda->jenis ?: 'Berita Reguler'),
                'lokasi' => $agenda->lokasi,
                'status' => $agenda->status,
                'reporter' => $agenda->reporter ?: $agenda->pic,
                'tanggal_rencana' => $agenda->tanggal ?: now()->toDateString(),
                'tanggal_tayang' => in_array($agenda->status, ['Selesai', 'On Air']) ? ($agenda->tanggal ?: now()->toDateString()) : null,
                'views' => in_array($agenda->status, ['Selesai', 'On Air']) ? rand(10000, 25000) : 0,
                'durasi' => $agenda->jenis === 'Live' ? '45:00' : '02:30',
            ]
        );

        ActivityLogger::log('AGENDA_CREATED', "Menambahkan Agenda Liputan: {$agenda->agenda} ({$agenda->kode})");

        return redirect()->route('tvri.berita.manage', ['tab' => 'agenda'])
            ->with('success', "Agenda Liputan [{$agenda->kode}] berhasil ditambahkan dan disinkronkan ke Newsroom.");
    }

    public function updateAgenda(Request $request, $id)
    {
        $validated = $request->validate([
            'kode' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
            'waktu' => 'nullable|string|max:50',
            'mulai' => 'nullable|string|max:10',
            'selesai' => 'nullable|string|max:10',
            'durasi_label' => 'nullable|string|max:50',
            'agenda' => 'required|string|max:255',
            'jenis' => 'nullable|string|max:50',
            'kategori' => 'nullable|string|max:100',
            'lokasi' => 'required|string|max:255',
            'pic' => 'nullable|string|max:255',
            'kendaraan' => 'nullable|string|max:100',
            'reporter' => 'nullable|string|max:100',
            'kamerawan' => 'nullable|string|max:100',
            'driver' => 'nullable|string|max:100',
            'koordinator' => 'nullable|string|max:100',
            'status' => 'required|string|max:50',
            'prioritas' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
            'link' => 'nullable|string|max:500',
        ]);

        if (empty($validated['waktu']) && !empty($validated['mulai']) && !empty($validated['selesai'])) {
            $validated['waktu'] = $validated['mulai'] . ' - ' . $validated['selesai'];
        }

        if (empty($validated['durasi_label']) && !empty($validated['mulai']) && !empty($validated['selesai'])) {
            $mParts = explode(':', $validated['mulai']);
            $sParts = explode(':', $validated['selesai']);
            $diffMinutes = ((int)($sParts[0] ?? 0) * 60 + (int)($sParts[1] ?? 0)) - ((int)($mParts[0] ?? 0) * 60 + (int)($mParts[1] ?? 0));
            $hours = floor($diffMinutes / 60);
            $mins = $diffMinutes % 60;
            $lbl = $hours > 0 ? "{$hours} jam" : "";
            if ($mins > 0) $lbl .= ($lbl ? " " : "") . "{$mins} mnt";
            $validated['durasi_label'] = $lbl ?: '2 jam';
        }

        if (empty($validated['pic']) && (!empty($validated['reporter']) || !empty($validated['kamerawan']))) {
            $pic = trim(($validated['reporter'] ?? '') . ' (Rep) & ' . ($validated['kamerawan'] ?? '') . ' (Cam)');
            if (!empty($validated['driver']) && $validated['driver'] !== '-') {
                $pic .= ' & ' . $validated['driver'] . ' (Drv)';
            }
            $validated['pic'] = $pic ?: 'Tim Redaksi';
        }

        $ag = TvriBeritaAgendaLiputan::findOrFail($id);
        $oldJudul = $ag->agenda;
        $ag->update($validated);

        // Update corresponding berita
        TvriBerita::where('judul', $oldJudul)->update([
            'judul' => $ag->agenda,
            'kategori' => $ag->kategori ?: 'Pemerintahan',
            'jenis' => $ag->jenis === 'Live' ? 'Live Report' : ($ag->jenis ?: 'Berita Reguler'),
            'lokasi' => $ag->lokasi,
            'status' => $ag->status,
            'reporter' => $ag->reporter ?: $ag->pic,
            'tanggal_rencana' => $ag->tanggal ?: now()->toDateString(),
        ]);

        ActivityLogger::log('AGENDA_UPDATED', "Memperbarui Agenda Liputan: {$ag->agenda} ({$ag->kode})");

        return redirect()->route('tvri.berita.manage', ['tab' => 'agenda'])
            ->with('success', "Agenda Liputan [{$ag->kode}] berhasil diperbarui.");
    }

    public function destroyAgenda($id)
    {
        $ag = TvriBeritaAgendaLiputan::findOrFail($id);
        $judul = $ag->agenda;
        $kode = $ag->kode;
        $ag->delete();

        ActivityLogger::log('AGENDA_DELETED', "Menghapus Agenda Liputan: {$judul} ({$kode})");

        return redirect()->route('tvri.berita.manage', ['tab' => 'agenda'])
            ->with('success', "Agenda Liputan [{$kode}] berhasil dihapus.");
    }

    public function bulkDestroyAgenda(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = TvriBeritaAgendaLiputan::whereIn('id', $ids)->count();
            TvriBeritaAgendaLiputan::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.berita.manage', ['tab' => 'agenda'])
                ->with('success', "Berhasil menghapus {$count} agenda liputan terpilih.");
        }
        return redirect()->route('tvri.berita.manage', ['tab' => 'agenda'])
            ->with('error', 'Tidak ada agenda liputan yang dipilih untuk dihapus.');
    }

    /**
     * Tampilan Live Portal Jadwal Peliputan TVRI Maluku
     * Tersinkronisasi real-time dengan pangkalan data TvriBeritaAgendaLiputan
     */
    public function jadwalPortal(Request $request)
    {
        $agendas = TvriBeritaAgendaLiputan::orderBy('tanggal')->orderBy('mulai')->orderBy('urutan')->get();

        $jadwalData = $agendas->map(function ($ag) {
            $tgl = $ag->tanggal ? \Carbon\Carbon::parse($ag->tanggal)->format('Y-m-d') : date('Y-m-d');
            $mulai = $ag->mulai ? substr($ag->mulai, 0, 5) : '08:00';
            $selesai = $ag->selesai ? substr($ag->selesai, 0, 5) : '10:00';
            $kode = !empty($ag->kode) ? $ag->kode : ('LP-' . str_replace('-', '', $tgl) . '-' . sprintf('%02d', $ag->id));
            $lokasi = $ag->lokasi ?? 'Kantor TVRI Maluku';
            $link = (!empty($ag->link) && $ag->link !== '-') ? $ag->link : ('https://www.google.com/maps/search/?api=1&query=' . urlencode($lokasi));

            return [
                'id' => $kode,
                'tanggal' => $tgl,
                'mulai' => $mulai,
                'selesai' => $selesai,
                'agenda' => $ag->agenda ?? 'Liputan',
                'jenis' => $ag->jenis ?? 'Berita Harian',
                'lokasi' => $lokasi,
                'link' => $link,
                'reporter' => (!empty($ag->reporter) && $ag->reporter !== '-') ? $ag->reporter : '',
                'kamerawan' => (!empty($ag->kamerawan) && $ag->kamerawan !== '-') ? $ag->kamerawan : '',
                'driver' => (!empty($ag->driver) && $ag->driver !== '-') ? $ag->driver : '',
                'kendaraan' => (!empty($ag->kendaraan) && $ag->kendaraan !== '-') ? $ag->kendaraan : '',
                'koordinator' => $ag->koordinator ?? 'Nahrum',
                'prioritas' => $ag->prioritas ?? 'Biasa',
                'status' => $ag->status ?? 'Terjadwal',
                'catatan' => $ag->catatan ?? '',
            ];
        })->values()->all();

        $defaultCrews = [
            ['nama' => 'Sarles Bolaman', 'jabatan' => 'Reporter'],
            ['nama' => 'Yaser Tuarita', 'jabatan' => 'Reporter'],
            ['nama' => 'Christine Sipahelut', 'jabatan' => 'Reporter'],
            ['nama' => 'Novita Bonara', 'jabatan' => 'Reporter'],
            ['nama' => 'Monica Sipahelut', 'jabatan' => 'Reporter'],
            ['nama' => 'Devy Bin Umar', 'jabatan' => 'Reporter'],
            ['nama' => 'Yohanis Limba', 'jabatan' => 'Reporter'],
            ['nama' => 'Shinta', 'jabatan' => 'Reporter'],
            ['nama' => 'Peggy Nahumury', 'jabatan' => 'Reporter'],
            ['nama' => 'Minggus Noya', 'jabatan' => 'Kamerawan'],
            ['nama' => 'Junaedi Asril', 'jabatan' => 'Kamerawan'],
            ['nama' => 'Fernanda Rasyid', 'jabatan' => 'Kamerawan'],
            ['nama' => 'Andre Uktolseja', 'jabatan' => 'Kamerawan'],
            ['nama' => 'Kurniawan Suwarno', 'jabatan' => 'Kamerawan'],
            ['nama' => 'Idrus', 'jabatan' => 'Driver'],
            ['nama' => 'Max Appono', 'jabatan' => 'Driver'],
            ['nama' => 'Della', 'jabatan' => 'Driver'],
        ];

        $existingCrewNames = collect($defaultCrews)->pluck('nama')->all();
        foreach ($agendas as $ag) {
            if (!empty($ag->reporter) && $ag->reporter !== '-' && !in_array($ag->reporter, $existingCrewNames)) {
                $defaultCrews[] = ['nama' => $ag->reporter, 'jabatan' => 'Reporter'];
                $existingCrewNames[] = $ag->reporter;
            }
            if (!empty($ag->kamerawan) && $ag->kamerawan !== '-' && !in_array($ag->kamerawan, $existingCrewNames)) {
                $defaultCrews[] = ['nama' => $ag->kamerawan, 'jabatan' => 'Kamerawan'];
                $existingCrewNames[] = $ag->kamerawan;
            }
            if (!empty($ag->driver) && $ag->driver !== '-' && !in_array($ag->driver, $existingCrewNames)) {
                $defaultCrews[] = ['nama' => $ag->driver, 'jabatan' => 'Driver'];
                $existingCrewNames[] = $ag->driver;
            }
        }

        $defaultKendaraan = [
            ['id' => 'KN001', 'nopol' => 'DE 1201 AB', 'jenis' => 'Mobil operasional (Innova)', 'kondisi' => 'Baik'],
            ['id' => 'KN002', 'nopol' => 'DE 1305 AB', 'jenis' => 'Mobil SNG / Live', 'kondisi' => 'Baik'],
            ['id' => 'KN003', 'nopol' => 'DE 1422 AB', 'jenis' => 'Mobil operasional (Avanza)', 'kondisi' => 'Baik'],
            ['id' => 'KN004', 'nopol' => 'DE 5310 AC', 'jenis' => 'Sepeda motor operasional', 'kondisi' => 'Perlu Perbaikan'],
        ];

        $existingNopol = collect($defaultKendaraan)->pluck('nopol')->all();
        foreach ($agendas as $ag) {
            if (!empty($ag->kendaraan) && $ag->kendaraan !== '-' && !in_array($ag->kendaraan, $existingNopol)) {
                $defaultKendaraan[] = [
                    'id' => 'KN' . sprintf('%03d', count($defaultKendaraan) + 1),
                    'nopol' => $ag->kendaraan,
                    'jenis' => 'Kendaraan Operasional Lapangan',
                    'kondisi' => 'Baik',
                ];
                $existingNopol[] = $ag->kendaraan;
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $jadwalData,
                'crew' => $defaultCrews,
                'kendaraan' => $defaultKendaraan,
            ]);
        }

        return view('tvri.berita.jadwal_portal', [
            'jadwalData' => $jadwalData,
            'crewData' => $defaultCrews,
            'kendaraanData' => $defaultKendaraan,
        ]);
    }

    /**
     * 5. CRUD Catatan & Peringatan Redaksi
     */
    public function storeCatatan(Request $request)
    {
        $request->validate([
            'tipe' => 'required|string|in:warning,info,success',
            'pesan' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
        ]);

        TvriBeritaCatatan::create($request->all());

        return redirect()->route('tvri.berita.manage', ['tab' => 'catatan'])
            ->with('success', 'Catatan / Peringatan Redaksi berhasil ditambahkan.');
    }

    public function updateCatatan(Request $request, $id)
    {
        $request->validate([
            'tipe' => 'required|string|in:warning,info,success',
            'pesan' => 'required|string|max:255',
        ]);

        $c = TvriBeritaCatatan::findOrFail($id);
        $c->update($request->all());

        return redirect()->route('tvri.berita.manage', ['tab' => 'catatan'])
            ->with('success', 'Catatan / Peringatan Redaksi berhasil diperbarui.');
    }

    public function destroyCatatan($id)
    {
        $c = TvriBeritaCatatan::findOrFail($id);
        $c->delete();

        return redirect()->route('tvri.berita.manage', ['tab' => 'catatan'])
            ->with('success', 'Catatan / Peringatan Redaksi berhasil dihapus.');
    }

    public function bulkDestroyCatatan(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = TvriBeritaCatatan::whereIn('id', $ids)->count();
            TvriBeritaCatatan::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.berita.manage', ['tab' => 'catatan'])
                ->with('success', "Berhasil menghapus {$count} catatan / peringatan redaksi terpilih.");
        }
        return redirect()->route('tvri.berita.manage', ['tab' => 'catatan'])
            ->with('error', 'Tidak ada catatan yang dipilih untuk dihapus.');
    }

    /**
     * Helper Seeder Default SOP Berita
     */
    public static function getOrSeedSops()
    {
        $sops = TvriBeritaSop::orderBy('urutan')->get();
        if ($sops->isEmpty()) {
            $defaultSops = [
                [
                    'kode' => 'SOP-BRT-01',
                    'judul' => 'SOP Peliputan Berita Lapangan & Verifikasi Fakta',
                    'kategori' => 'Peliputan',
                    'versi' => 'v1.2',
                    'status' => 'Aktif',
                    'urutan' => 1,
                    'keterangan' => 'Pedoman operasional peliputan jurnalis di lapangan, pengecekan keabsahan narasumber dan verifikasi silang fakta dua arah.'
                ],
                [
                    'kode' => 'SOP-BRT-02',
                    'judul' => 'SOP Produksi & Editing Paket Berita Televisi',
                    'kategori' => 'Produksi',
                    'versi' => 'v1.1',
                    'status' => 'Aktif',
                    'urutan' => 2,
                    'keterangan' => 'Standar editing video, dubbing/VO audio, bumper siaran, dan penyusunan naskah paket berita TVRI Maluku.'
                ],
                [
                    'kode' => 'SOP-BRT-03',
                    'judul' => 'SOP Penyiaran Berita Siap Siar (On-Air) & Rundown Studio',
                    'kategori' => 'Penyiaran',
                    'versi' => 'v2.0',
                    'status' => 'Aktif',
                    'urutan' => 3,
                    'keterangan' => 'Prosedur pemindahan file berita ke video server siaran, sinkronisasi rundown siaran langsung, dan koordinasi dengan master control (MCR).'
                ],
                [
                    'kode' => 'SOP-BRT-04',
                    'judul' => 'SOP Penanganan Breaking News & Live Report Satelit/IPTV',
                    'kategori' => 'Penyiaran Khusus',
                    'versi' => 'v1.0',
                    'status' => 'Aktif',
                    'urutan' => 4,
                    'keterangan' => 'Prosedur percepatan siaran khusus breaking news bencana, peristiwa mendesak, dan interupsi program dengan persetujuan Produser Berita.'
                ],
                [
                    'kode' => 'SOP-BRT-05',
                    'judul' => 'SOP Pengarsipan Audio Visual & Distribusi Multiplatform Digital',
                    'kategori' => 'Distribusi Digital',
                    'versi' => 'v1.3',
                    'status' => 'Aktif',
                    'urutan' => 5,
                    'keterangan' => 'Tata cara konversi format, pembuatan thumbnail/metadata digital, upload ke media sosial resmi (YouTube, IG, TikTok, Portal Web TVRI Maluku) dan arsip DMS.'
                ],
                [
                    'kode' => 'SOP-BRT-06',
                    'judul' => 'SOP Kepatuhan Kode Etik Jurnalistik & Pedoman P3SPS KPI',
                    'kategori' => 'Kode Etik & Regulasi',
                    'versi' => 'v2.1',
                    'status' => 'Aktif',
                    'urutan' => 6,
                    'keterangan' => 'Kepatuhan terhadap UU Pers No. 40 Tahun 1999, Kode Etik Wartawan Indonesia, dan Pedoman Perilaku Penyiaran serta Standar Program Siaran (P3SPS).'
                ],
            ];
            foreach ($defaultSops as $sop) {
                TvriBeritaSop::create($sop);
            }
            $sops = TvriBeritaSop::orderBy('urutan')->get();
        }
        return $sops;
    }

    /**
     * Helper Seeder Master Berita Terbaru (Sinkron Penuh dengan Kelola Berita & Dashboard)
     */
    public static function getOrSeedBeritas()
    {
        $count = TvriBerita::count();
        if ($count < 5) {
            $defaults = [
                [
                    'judul' => 'Festival Budaya Pela Gandong di Ambon',
                    'kategori' => 'Budaya',
                    'jenis' => 'Feature',
                    'lokasi' => 'Ambon',
                    'status' => 'Selesai',
                    'reporter' => 'Sarles Bolaman',
                    'durasi' => '02:35',
                    'views' => 12500,
                    'tanggal_rencana' => '2025-09-02',
                    'tanggal_tayang' => '2025-09-02',
                ],
                [
                    'judul' => 'Bantuan UMKM untuk Nelayan di Seram',
                    'kategori' => 'Ekonomi & UMKM',
                    'jenis' => 'Berita Reguler',
                    'lokasi' => 'Maluku Tengah',
                    'status' => 'Selesai',
                    'reporter' => 'Christine Sipahelut',
                    'durasi' => '01:48',
                    'views' => 8700,
                    'tanggal_rencana' => '2025-09-02',
                    'tanggal_tayang' => '2025-09-02',
                ],
                [
                    'judul' => 'Perbaikan Jalan di SBB Terus Dikebut',
                    'kategori' => 'Pemerintahan',
                    'jenis' => 'Current Affairs',
                    'lokasi' => 'SBB',
                    'status' => 'Selesai',
                    'reporter' => 'Novita Bonara',
                    'durasi' => '03:12',
                    'views' => 10200,
                    'tanggal_rencana' => '2025-09-02',
                    'tanggal_tayang' => '2025-09-02',
                ],
                [
                    'judul' => 'Sekolah di Kep. Aru Dapat Bantuan Fasilitas',
                    'kategori' => 'Pendidikan',
                    'jenis' => 'Feature',
                    'lokasi' => 'Kep. Aru',
                    'status' => 'Selesai',
                    'reporter' => 'Yohanis Limba',
                    'durasi' => '03:21',
                    'views' => 6400,
                    'tanggal_rencana' => '2025-09-01',
                    'tanggal_tayang' => '2025-09-01',
                ],
                [
                    'judul' => 'Harga Pangan di Ambon Tetap Stabil',
                    'kategori' => 'Ekonomi & UMKM',
                    'jenis' => 'Berita Reguler',
                    'lokasi' => 'Ambon',
                    'status' => 'Selesai',
                    'reporter' => 'Devy Bin Umar',
                    'durasi' => '01:55',
                    'views' => 9800,
                    'tanggal_rencana' => '2025-09-01',
                    'tanggal_tayang' => '2025-09-01',
                ],
            ];

            foreach ($defaults as $def) {
                if (!TvriBerita::where('judul', $def['judul'])->exists()) {
                    TvriBerita::create($def);
                }
            }
        }

        return TvriBerita::orderByDesc('id')->take(5)->get();
    }

    /**
     * Helper Seeder Default Produksi Paket Berita
     */
    public static function getOrSeedProduksis()
    {
        $produksis = TvriBeritaProduksi::orderByDesc('tanggal_produksi')->orderByDesc('id')->get();
        if ($produksis->isEmpty()) {
            $defaults = [
                [
                    'kode' => 'PKT-202611-01',
                    'judul' => 'Paket Khusus Upacara & Tabur Bunga Hari Pahlawan di Teluk Ambon',
                    'program' => 'Maluku Hari Ini Sore',
                    'kategori' => 'Pemerintahan',
                    'reporter' => 'Monica Sipahelut',
                    'kamerawan' => 'Kurniawan Suwarno',
                    'editor' => 'Hendrik Latuputty',
                    'durasi' => '03:45',
                    'tanggal_produksi' => '2026-11-10',
                    'target_tayang' => '2026-11-10',
                    'status' => 'Siap Tayang',
                    'prioritas' => 'Mendesak',
                    'catatan' => 'Gabungkan visual drone Teluk Ambon dan wawancara Forkopimda.',
                    'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                    'naskah_file' => null,
                ],
                [
                    'kode' => 'PKT-202611-02',
                    'judul' => 'Investigasi Abrasi Pantai Pesisir Hitu dan Ketahanan Nelayan Tradisional',
                    'program' => 'Panorama Maluku',
                    'kategori' => 'Investigasi',
                    'reporter' => 'Sarles Bolaman',
                    'kamerawan' => 'Kurniawan Suwarno',
                    'editor' => 'Rony Nanlohy',
                    'durasi' => '05:20',
                    'tanggal_produksi' => '2026-11-11',
                    'target_tayang' => '2026-11-12',
                    'status' => 'Review EIC',
                    'prioritas' => 'Penting',
                    'catatan' => 'Sertakan data grafis BMKG dan tanggapan BPBD Maluku Tengah.',
                    'video_url' => null,
                    'naskah_file' => null,
                ],
                [
                    'kode' => 'PKT-202611-03',
                    'judul' => 'Jelajah Kuliner Pesisir: Tradisi Papeda Panas & Ikan Kuah Kuning Natsepa',
                    'program' => 'Pesona Budaya Maluku',
                    'kategori' => 'Budaya',
                    'reporter' => 'Christine Sipahelut',
                    'kamerawan' => 'Fernanda Rasyid',
                    'editor' => 'Grace Tentua',
                    'durasi' => '04:15',
                    'tanggal_produksi' => '2026-11-09',
                    'target_tayang' => '2026-11-14',
                    'status' => 'Selesai',
                    'prioritas' => 'Biasa',
                    'catatan' => 'Untuk segmen akhir pekan; perbanyak stok audio alami laut.',
                    'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
                    'naskah_file' => null,
                ],
                [
                    'kode' => 'PKT-202611-04',
                    'judul' => 'Dinamika KUA-PPAS APBD 2027: Sorotan Alokasi Pendidikan & Kesehatan Daerah',
                    'program' => 'Maluku Hari Ini Pagi',
                    'kategori' => 'Pemerintahan',
                    'reporter' => 'Sarles Bolaman',
                    'kamerawan' => 'Minggus Noya',
                    'editor' => 'Hendrik Latuputty',
                    'durasi' => '03:10',
                    'tanggal_produksi' => '2026-11-09',
                    'target_tayang' => '2026-11-10',
                    'status' => 'On Air',
                    'prioritas' => 'Penting',
                    'catatan' => 'Soundbite Ketua DPRD dan pandangan umum fraksi lengkap.',
                    'video_url' => null,
                    'naskah_file' => null,
                ],
                [
                    'kode' => 'PKT-202611-05',
                    'judul' => 'Kesiapan Angkutan Laut Pelni & ASDP Jelang Natal 2026 dan Tahun Baru 2027',
                    'program' => 'Berita Daerah',
                    'kategori' => 'Ekonomi & UMKM',
                    'reporter' => 'Peggy Nahumury',
                    'kamerawan' => 'Andre Uktolseja',
                    'editor' => 'Grace Tentua',
                    'durasi' => '02:50',
                    'tanggal_produksi' => '2026-11-11',
                    'target_tayang' => '2026-11-11',
                    'status' => 'Editing',
                    'prioritas' => 'Penting',
                    'catatan' => 'Wawancara KSOP dan PT Pelni Cabang Ambon.',
                    'video_url' => null,
                    'naskah_file' => null,
                ],
                [
                    'kode' => 'PKT-202611-06',
                    'judul' => 'Revitalisasi Benteng Victoria: Merawat Cagar Budaya Peninggalan Sejarah Ambon',
                    'program' => 'Panorama Maluku',
                    'kategori' => 'Budaya',
                    'reporter' => 'Yohanis Limba',
                    'kamerawan' => 'Minggus Noya',
                    'editor' => 'Rony Nanlohy',
                    'durasi' => '04:00',
                    'tanggal_produksi' => '2026-11-12',
                    'target_tayang' => '2026-11-13',
                    'status' => 'Editing',
                    'prioritas' => 'Biasa',
                    'catatan' => 'Izin Kodam XV/Pattimura dan footage arsip kolonial.',
                    'video_url' => null,
                    'naskah_file' => null,
                ],
            ];
            foreach ($defaults as $item) {
                TvriBeritaProduksi::create($item);
            }
            $produksis = TvriBeritaProduksi::orderByDesc('tanggal_produksi')->orderByDesc('id')->get();
        }
        return $produksis;
    }

    /**
     * Helper Seeder Default Monitoring Berita Harian
     */
    public static function getOrSeedMonitorings()
    {
        $monitorings = TvriBeritaMonitoring::orderByDesc('tanggal')->orderBy('jam_tayang')->get();
        if ($monitorings->isEmpty()) {
            $defaults = [
                [
                    'kode' => 'MON-202611-01',
                    'tanggal' => '2026-11-09',
                    'program_siaran' => 'Maluku Hari Ini Pagi',
                    'jam_tayang' => '07:00 - 07:30 WIT',
                    'presenter' => 'Clara Lekatompessy',
                    'produser' => 'Nahrum',
                    'rundown_fokus' => 'Pembahasan KUA-PPAS APBD 2027 di DPRD Maluku & Pantauan Harga Pangan',
                    'jumlah_berita' => 9,
                    'status_siaran' => 'Selesai',
                    'kualitas_av' => 'Sangat Baik',
                    'evaluasi_catatan' => 'Transisi video server lancar, audio narasumber jernih.',
                ],
                [
                    'kode' => 'MON-202611-02',
                    'tanggal' => '2026-11-09',
                    'program_siaran' => 'Warta Nusantara (Relai Nasional)',
                    'jam_tayang' => '12:00 - 12:30 WIT',
                    'presenter' => 'Relai Nasional TVRI Pusat',
                    'produser' => 'Nahrum (Maluku Feed)',
                    'rundown_fokus' => 'Kontribusi 2 Berita Daerah TVRI Maluku Masuk Slot Berita Nasional',
                    'jumlah_berita' => 12,
                    'status_siaran' => 'Selesai',
                    'kualitas_av' => 'Sangat Baik',
                    'evaluasi_catatan' => 'Uplink satelit SNG ke TVRI Pusat berjalan sempurna tanpa jitter.',
                ],
                [
                    'kode' => 'MON-202611-03',
                    'tanggal' => '2026-11-10',
                    'program_siaran' => 'Breaking News & LIVE Upacara',
                    'jam_tayang' => '07:30 - 09:30 WIT',
                    'presenter' => 'Monica Sipahelut & Devy Bin Umar',
                    'produser' => 'Nahrum',
                    'rundown_fokus' => 'LIVE Upacara Peringatan Hari Pahlawan dari TMP Kapahaha Ambon',
                    'jumlah_berita' => 4,
                    'status_siaran' => 'Selesai',
                    'kualitas_av' => 'Sangat Baik',
                    'evaluasi_catatan' => 'Sinyal SNG stabil 1080i, koordinasi MCR dengan switcher lapangan prima.',
                ],
                [
                    'kode' => 'MON-202611-04',
                    'tanggal' => '2026-11-10',
                    'program_siaran' => 'Maluku Hari Ini Sore',
                    'jam_tayang' => '17:00 - 17:30 WIT',
                    'presenter' => 'Devy Bin Umar',
                    'produser' => 'Nahrum',
                    'rundown_fokus' => 'Liputan Tabur Bunga di Teluk Ambon & Profil Veteran Pejuang Maluku',
                    'jumlah_berita' => 10,
                    'status_siaran' => 'Selesai',
                    'kualitas_av' => 'Sangat Baik',
                    'evaluasi_catatan' => 'Grafis opening Hari Pahlawan tepat waktu, penonton live streaming meningkat.',
                ],
                [
                    'kode' => 'MON-202611-05',
                    'tanggal' => '2026-11-11',
                    'program_siaran' => 'Berita Daerah',
                    'jam_tayang' => '19:00 - 19:30 WIT',
                    'presenter' => 'Clara Lekatompessy',
                    'produser' => 'Nahrum',
                    'rundown_fokus' => 'Konferensi Pers Transportasi Nataru & Investigasi Abrasi Hitu',
                    'jumlah_berita' => 8,
                    'status_siaran' => 'Selesai',
                    'kualitas_av' => 'Baik',
                    'evaluasi_catatan' => 'VO paket abrasi pantai perlu sedikit penyesuaian gain audio.',
                ],
                [
                    'kode' => 'MON-202611-06',
                    'tanggal' => '2026-11-12',
                    'program_siaran' => 'Dialog LIVE "Maluku Membangun"',
                    'jam_tayang' => '13:00 - 14:00 WIT',
                    'presenter' => 'Devy Bin Umar',
                    'produser' => 'Nahrum',
                    'rundown_fokus' => 'Kesiapan Ketahanan Pangan & Inflasi Daerah Bersama BPS Maluku',
                    'jumlah_berita' => 3,
                    'status_siaran' => 'Selesai',
                    'kualitas_av' => 'Sangat Baik',
                    'evaluasi_catatan' => 'Interaksi telepon pemirsa aktif, lighting studio optimal.',
                ],
            ];
            foreach ($defaults as $item) {
                TvriBeritaMonitoring::create($item);
            }
            $monitorings = TvriBeritaMonitoring::orderByDesc('tanggal')->orderBy('jam_tayang')->get();
        }
        return $monitorings;
    }

    /**
     * Helper Seeder Default Dokumentasi Siaran Berita
     */
    public static function getOrSeedDokumentasis()
    {
        $dokumentasis = TvriBeritaDokumentasi::orderByDesc('tanggal_liputan')->orderByDesc('id')->get();
        if ($dokumentasis->isEmpty()) {
            $defaults = [
                [
                    'kode' => 'DOK-202611-01',
                    'tanggal_liputan' => '2026-11-09',
                    'judul_dokumentasi' => 'Master Footage Rapat Paripurna KUA-PPAS APBD 2027 DPRD Maluku',
                    'lokasi' => 'Gedung DPRD Provinsi Maluku, Karang Panjang',
                    'crew' => 'Sarles Bolaman (Rep) & Minggus Noya (Cam)',
                    'jenis_media' => 'Footage Video',
                    'format_file' => 'XDCAM 50Mbps 1080i',
                    'ukuran_file' => '4.8 GB',
                    'link_media' => 'https://jadwal-liputan-tvri-maluku-750b1535.vercel.app/',
                    'status_arsip' => 'Tersimpan DMS',
                    'keterangan' => 'Arsip master mentah 2 jam wawancara dan suasana sidang.',
                ],
                [
                    'kode' => 'DOK-202611-02',
                    'tanggal_liputan' => '2026-11-09',
                    'judul_dokumentasi' => 'Dokumentasi Visual Pemantauan Harga Bahan Pokok Pasar Mardika',
                    'lokasi' => 'Pasar Mardika, Ambon',
                    'crew' => 'Yaser Tuarita (Rep) & Junaedi Asril (Cam)',
                    'jenis_media' => 'Footage Video',
                    'format_file' => 'MP4 1080p',
                    'ukuran_file' => '2.1 GB',
                    'link_media' => 'https://jadwal-liputan-tvri-maluku-750b1535.vercel.app/',
                    'status_arsip' => 'Terdistribusi Digital',
                    'keterangan' => 'Klip potongan untuk medsos Instagram dan YouTube Shorts.',
                ],
                [
                    'kode' => 'DOK-202611-03',
                    'tanggal_liputan' => '2026-11-10',
                    'judul_dokumentasi' => 'Master Broadcast LIVE Peringatan Hari Pahlawan 10 November TMP Kapahaha',
                    'lokasi' => 'Taman Makam Pahlawan Kapahaha, Ambon',
                    'crew' => 'Monica Sipahelut (Rep) & Tim OB-Van TVRI Maluku',
                    'jenis_media' => 'Master Siaran',
                    'format_file' => 'ProRes 422 1080i',
                    'ukuran_file' => '18.4 GB',
                    'link_media' => 'https://jadwal-liputan-tvri-maluku-750b1535.vercel.app/',
                    'status_arsip' => 'Master Tape',
                    'keterangan' => 'Rekaman penuh 2 jam siaran langsung upacara dan tabur bunga.',
                ],
                [
                    'kode' => 'DOK-202611-04',
                    'tanggal_liputan' => '2026-11-10',
                    'judul_dokumentasi' => 'Audio VO & Naskah Profil Veteran Pejuang Kemerdekaan Asal Maluku',
                    'lokasi' => 'Kota Ambon',
                    'crew' => 'Devy Bin Umar (VO) & Hendrik Latuputty (Editor)',
                    'jenis_media' => 'Audio VO',
                    'format_file' => 'WAV 24-bit 48kHz',
                    'ukuran_file' => '420 MB',
                    'link_media' => 'https://jadwal-liputan-tvri-maluku-750b1535.vercel.app/',
                    'status_arsip' => 'Tersimpan DMS',
                    'keterangan' => 'Narasi dan sound effect khusus paket feature Hari Pahlawan.',
                ],
                [
                    'kode' => 'DOK-202611-05',
                    'tanggal_liputan' => '2026-11-11',
                    'judul_dokumentasi' => 'Foto Dokumentasi & Naskah Investigasi Kerusakan Pesisir Negeri Hitu',
                    'lokasi' => 'Negeri Hitu, Maluku Tengah',
                    'crew' => 'Sarles Bolaman & Kurniawan Suwarno',
                    'jenis_media' => 'Foto Lapangan',
                    'format_file' => 'RAW + JPEG Hi-Res',
                    'ukuran_file' => '1.6 GB',
                    'link_media' => 'https://jadwal-liputan-tvri-maluku-750b1535.vercel.app/',
                    'status_arsip' => 'Tersimpan DMS',
                    'keterangan' => '45 foto resolusi tinggi pemukiman terdampak abrasi dan wawancara warga.',
                ],
                [
                    'kode' => 'DOK-202611-06',
                    'tanggal_liputan' => '2026-11-12',
                    'judul_dokumentasi' => 'Master Video Dialog Interaktif "Maluku Membangun" Episode 46',
                    'lokasi' => 'Studio 1 TVRI Maluku',
                    'crew' => 'Tim Produksi Berita & MCR',
                    'jenis_media' => 'Master Siaran',
                    'format_file' => 'MP4 Broadcast 1080p',
                    'ukuran_file' => '6.2 GB',
                    'link_media' => 'https://jadwal-liputan-tvri-maluku-750b1535.vercel.app/',
                    'status_arsip' => 'Terdistribusi Digital',
                    'keterangan' => 'Arsip master dialog dan rekaman call-in pemirsa.',
                ],
            ];
            foreach ($defaults as $item) {
                TvriBeritaDokumentasi::create($item);
            }
            $dokumentasis = TvriBeritaDokumentasi::orderByDesc('tanggal_liputan')->orderByDesc('id')->get();
        }
        return $dokumentasis;
    }

    /**
     * CRUD: Tambah Dokumen SOP Berita
     */
        public function storeSop(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'versi' => 'nullable|string|max:20',
            'status' => 'required|string|in:Aktif,Revisi,Nonaktif',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:20480',
        ]);

        $data = [
            'kode' => $request->kode,
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'versi' => $request->versi ?? 'v1.0',
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'urutan' => $request->urutan ?? (TvriBeritaSop::max('urutan') + 1),
        ];

        if ($request->hasFile('file')) {
            $f = $request->file('file');
            $cleanName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $f->getClientOriginalName());
            $path = $f->storeAs('sops', $cleanName, 'public');
            $data['file_url'] = 'storage/' . $path;
            $data['file_path'] = 'storage/' . $path;
        }

        TvriBeritaSop::create($data);

        ActivityLogger::log('BERITA_SOP_CREATED', "Menambahkan Dokumen SOP Berita: {$request->kode} - {$request->judul}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'sop'])
            ->with('success', "Dokumen SOP Berita {$request->kode} berhasil ditambahkan.");
    }

    /**
     * CRUD: Update Dokumen SOP Berita
     */
    public function updateSop(Request $request, $id)
    {
        $sop = TvriBeritaSop::findOrFail($id);

        $request->validate([
            'kode' => 'required|string|max:50',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'versi' => 'nullable|string|max:20',
            'status' => 'required|string|in:Aktif,Revisi,Nonaktif',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:20480',
        ]);

        $data = [
            'kode' => $request->kode,
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'versi' => $request->versi ?? $sop->versi,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'urutan' => $request->urutan ?? $sop->urutan,
        ];

        if ($request->hasFile('file')) {
            $f = $request->file('file');
            $cleanName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $f->getClientOriginalName());
            $path = $f->storeAs('sops', $cleanName, 'public');
            $data['file_url'] = 'storage/' . $path;
            $data['file_path'] = 'storage/' . $path;
        }

        $sop->update($data);

        ActivityLogger::log('BERITA_SOP_UPDATED', "Memperbarui Dokumen SOP Berita: {$sop->kode} - {$sop->judul}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'sop'])
            ->with('success', "Dokumen SOP Berita {$sop->kode} berhasil diperbarui.");
    }

    /**
     * CRUD: Hapus Dokumen SOP Berita
     */
    public function destroySop(Request $request, $id)
    {
        $sop = TvriBeritaSop::findOrFail($id);
        $kode = $sop->kode;
        $sop->delete();

        ActivityLogger::log('BERITA_SOP_DELETED', "Menghapus Dokumen SOP Berita: {$kode}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'sop'])
            ->with('success', "Dokumen SOP Berita {$kode} berhasil dihapus.");
    }

    /**
     * CRUD: Bulk Delete Dokumen SOP Berita
     */
    public function bulkDestroySop(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriBeritaSop::whereIn('id', $ids)->delete();
            ActivityLogger::log('BERITA_SOP_BULK_DELETED', "Menghapus " . count($ids) . " Dokumen SOP Berita");
        }

        return redirect()->route('tvri.berita.manage', ['tab' => 'sop'])
            ->with('success', "Dokumen SOP Berita terpilih berhasil dihapus.");
    }

    // ========================================================
    // CRUD: PRODUKSI PAKET BERITA TELEVISI
    // ========================================================

    public function storeProduksi(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'program' => 'nullable|string|max:100',
            'kategori' => 'nullable|string|max:100',
            'reporter' => 'nullable|string|max:100',
            'kamerawan' => 'nullable|string|max:100',
            'editor' => 'nullable|string|max:100',
            'durasi' => 'nullable|string|max:20',
            'tanggal_produksi' => 'nullable|date',
            'target_tayang' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'prioritas' => 'nullable|string|max:30',
            'catatan' => 'nullable|string',
            'video_url' => 'nullable|string|max:500',
        ]);

        $count = TvriBeritaProduksi::count() + 1;
        $validated['kode'] = 'PKT-' . date('Ym') . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);
        $validated['status'] = $validated['status'] ?: 'Editing';
        $validated['prioritas'] = $validated['prioritas'] ?: 'Biasa';

        $item = TvriBeritaProduksi::create($validated);
        ActivityLogger::log('tambah_produksi_paket', "Menambahkan produksi paket berita: {$item->judul}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'produksi'])
            ->with('success', 'Paket Berita berhasil ditambahkan ke alur produksi!');
    }

    public function updateProduksi(Request $request, $id)
    {
        $item = TvriBeritaProduksi::findOrFail($id);
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'program' => 'nullable|string|max:100',
            'kategori' => 'nullable|string|max:100',
            'reporter' => 'nullable|string|max:100',
            'kamerawan' => 'nullable|string|max:100',
            'editor' => 'nullable|string|max:100',
            'durasi' => 'nullable|string|max:20',
            'tanggal_produksi' => 'nullable|date',
            'target_tayang' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'prioritas' => 'nullable|string|max:30',
            'catatan' => 'nullable|string',
            'video_url' => 'nullable|string|max:500',
        ]);

        $item->update($validated);
        ActivityLogger::log('update_produksi_paket', "Memperbarui produksi paket berita: {$item->judul}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'produksi'])
            ->with('success', 'Data Produksi Paket Berita berhasil diperbarui!');
    }

    public function destroyProduksi($id)
    {
        $item = TvriBeritaProduksi::findOrFail($id);
        $judul = $item->judul;
        $item->delete();
        ActivityLogger::log('hapus_produksi_paket', "Menghapus produksi paket berita: {$judul}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'produksi'])
            ->with('success', 'Paket berita berhasil dihapus dari alur produksi.');
    }

    public function bulkDestroyProduksi(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriBeritaProduksi::whereIn('id', $ids)->delete();
            ActivityLogger::log('bulk_hapus_produksi_paket', "Menghapus massal " . count($ids) . " produksi paket berita");
        }
        return redirect()->route('tvri.berita.manage', ['tab' => 'produksi'])
            ->with('success', count($ids) . ' paket berita terpilih berhasil dihapus.');
    }

    // ========================================================
    // CRUD: MONITORING BERITA HARIAN
    // ========================================================

    public function storeMonitoring(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'program_siaran' => 'required|string|max:100',
            'jam_tayang' => 'required|string|max:50',
            'presenter' => 'nullable|string|max:100',
            'produser' => 'nullable|string|max:100',
            'rundown_fokus' => 'required|string|max:255',
            'jumlah_berita' => 'nullable|integer',
            'status_siaran' => 'nullable|string|max:50',
            'kualitas_av' => 'nullable|string|max:50',
            'evaluasi_catatan' => 'nullable|string',
        ]);

        $count = TvriBeritaMonitoring::count() + 1;
        $validated['kode'] = 'MON-' . date('Ym') . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);
        $validated['jumlah_berita'] = $validated['jumlah_berita'] ?? 8;
        $validated['status_siaran'] = $validated['status_siaran'] ?? 'Sesuai Jadwal';
        $validated['kualitas_av'] = $validated['kualitas_av'] ?? 'Sangat Baik';

        $item = TvriBeritaMonitoring::create($validated);
        ActivityLogger::log('tambah_monitoring_berita', "Menambahkan log monitoring siaran berita: {$item->program_siaran}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'monitoring'])
            ->with('success', 'Log monitoring berita harian berhasil ditambahkan!');
    }

    public function updateMonitoring(Request $request, $id)
    {
        $item = TvriBeritaMonitoring::findOrFail($id);
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'program_siaran' => 'required|string|max:100',
            'jam_tayang' => 'required|string|max:50',
            'presenter' => 'nullable|string|max:100',
            'produser' => 'nullable|string|max:100',
            'rundown_fokus' => 'required|string|max:255',
            'jumlah_berita' => 'nullable|integer',
            'status_siaran' => 'nullable|string|max:50',
            'kualitas_av' => 'nullable|string|max:50',
            'evaluasi_catatan' => 'nullable|string',
        ]);

        $item->update($validated);
        ActivityLogger::log('update_monitoring_berita', "Memperbarui log monitoring siaran berita: {$item->program_siaran}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'monitoring'])
            ->with('success', 'Data monitoring berita harian berhasil diperbarui!');
    }

    public function destroyMonitoring($id)
    {
        $item = TvriBeritaMonitoring::findOrFail($id);
        $prog = $item->program_siaran;
        $item->delete();
        ActivityLogger::log('hapus_monitoring_berita', "Menghapus log monitoring siaran berita: {$prog}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'monitoring'])
            ->with('success', 'Log monitoring siaran berhasil dihapus.');
    }

    public function bulkDestroyMonitoring(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriBeritaMonitoring::whereIn('id', $ids)->delete();
            ActivityLogger::log('bulk_hapus_monitoring_berita', "Menghapus massal " . count($ids) . " log monitoring berita");
        }
        return redirect()->route('tvri.berita.manage', ['tab' => 'monitoring'])
            ->with('success', count($ids) . ' data monitoring berita terpilih berhasil dihapus.');
    }

    // ========================================================
    // CRUD: DOKUMENTASI BERITA HARIAN
    // ========================================================

    public function storeDokumentasi(Request $request)
    {
        $validated = $request->validate([
            'tanggal_liputan' => 'required|date',
            'judul_dokumentasi' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:100',
            'crew' => 'nullable|string|max:150',
            'jenis_media' => 'nullable|string|max:50',
            'format_file' => 'nullable|string|max:30',
            'ukuran_file' => 'nullable|string|max:30',
            'link_media' => 'nullable|string|max:500',
            'status_arsip' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
        ]);

        $count = TvriBeritaDokumentasi::count() + 1;
        $validated['kode'] = 'DOK-' . date('Ym') . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);
        $validated['jenis_media'] = $validated['jenis_media'] ?? 'Footage Video';
        $validated['status_arsip'] = $validated['status_arsip'] ?? 'Tersimpan DMS';

        $item = TvriBeritaDokumentasi::create($validated);
        ActivityLogger::log('tambah_dokumentasi_berita', "Menambahkan dokumentasi berita harian: {$item->judul_dokumentasi}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'dokumentasi'])
            ->with('success', 'Dokumentasi berita harian berhasil diarsipkan!');
    }

    public function updateDokumentasi(Request $request, $id)
    {
        $item = TvriBeritaDokumentasi::findOrFail($id);
        $validated = $request->validate([
            'tanggal_liputan' => 'required|date',
            'judul_dokumentasi' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:100',
            'crew' => 'nullable|string|max:150',
            'jenis_media' => 'nullable|string|max:50',
            'format_file' => 'nullable|string|max:30',
            'ukuran_file' => 'nullable|string|max:30',
            'link_media' => 'nullable|string|max:500',
            'status_arsip' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
        ]);

        $item->update($validated);
        ActivityLogger::log('update_dokumentasi_berita', "Memperbarui dokumentasi berita harian: {$item->judul_dokumentasi}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'dokumentasi'])
            ->with('success', 'Data dokumentasi berita harian berhasil diperbarui!');
    }

    public function destroyDokumentasi($id)
    {
        $item = TvriBeritaDokumentasi::findOrFail($id);
        $judul = $item->judul_dokumentasi;
        $item->delete();
        ActivityLogger::log('hapus_dokumentasi_berita', "Menghapus dokumentasi berita harian: {$judul}");

        return redirect()->route('tvri.berita.manage', ['tab' => 'dokumentasi'])
            ->with('success', 'Dokumentasi berita berhasil dihapus dari arsip.');
    }

    public function bulkDestroyDokumentasi(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriBeritaDokumentasi::whereIn('id', $ids)->delete();
            ActivityLogger::log('bulk_hapus_dokumentasi_berita', "Menghapus massal " . count($ids) . " arsip dokumentasi berita");
        }
        return redirect()->route('tvri.berita.manage', ['tab' => 'dokumentasi'])
            ->with('success', count($ids) . ' arsip dokumentasi terpilih berhasil dihapus.');
    }
}
