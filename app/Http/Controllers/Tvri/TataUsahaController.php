<?php

namespace App\Http\Controllers\Tvri;

use App\Http\Controllers\Controller;
use App\Models\TvriAgendaKegiatan;
use App\Models\TvriLayananAdm;
use App\Models\TvriSuratKeluar;
use App\Models\TvriSuratMasuk;
use App\Models\TvriTuRingkasan;
use App\Models\TvriTuSop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TataUsahaController extends Controller
{
    public const ENTITAS = [
        'surat-masuk' => [
            'model' => TvriSuratMasuk::class,
            'nama' => 'Surat Masuk',
            'ikon' => 'M8 12h8m-4-4l4 4-4 4',
            'cari' => ['nomor', 'pengirim', 'perihal'],
            'kolom' => [
                'tanggal' => ['label' => 'Tanggal', 'type' => 'date', 'required' => true, 'tampil' => true],
                'nomor' => ['label' => 'Nomor Surat', 'type' => 'text', 'required' => true, 'tampil' => true],
                'pengirim' => ['label' => 'Pengirim', 'type' => 'text', 'required' => true, 'tampil' => true],
                'perihal' => ['label' => 'Perihal', 'type' => 'textarea', 'required' => true, 'tampil' => true],
                'kategori' => ['label' => 'Kategori', 'type' => 'select', 'required' => true, 'tampil' => true,
                    'opsi' => ['Kementerian', 'LPP TVRI', 'Pemerintah Daerah', 'Instansi Vertikal', 'Mitra Kerja', 'Masyarakat', 'Lainnya']],
                'status' => ['label' => 'Status', 'type' => 'select', 'required' => true, 'tampil' => true,
                    'opsi' => ['Baru', 'Didisposisikan', 'Diproses', 'Selesai']],
            ],
        ],
        'surat-keluar' => [
            'model' => TvriSuratKeluar::class,
            'nama' => 'Surat Keluar',
            'ikon' => 'M16 12H8m8-4l4 4-4 4',
            'cari' => ['nomor', 'tujuan', 'perihal'],
            'kolom' => [
                'tanggal' => ['label' => 'Tanggal', 'type' => 'date', 'required' => true, 'tampil' => true],
                'nomor' => ['label' => 'Nomor Surat', 'type' => 'text', 'required' => true, 'tampil' => true],
                'tujuan' => ['label' => 'Tujuan', 'type' => 'text', 'required' => true, 'tampil' => true],
                'perihal' => ['label' => 'Perihal', 'type' => 'textarea', 'required' => true, 'tampil' => true],
                'status' => ['label' => 'Status', 'type' => 'select', 'required' => true, 'tampil' => true,
                    'opsi' => ['Baru', 'Terkirim', 'Diproses', 'Selesai']],
            ],
        ],
        'layanan-adm' => [
            'model' => TvriLayananAdm::class,
            'nama' => 'Layanan Administrasi',
            'ikon' => 'M9 12h6m-6 4h6M9 8h6m-9 12h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
            'cari' => ['jenis', 'keterangan'],
            'kolom' => [
                'jenis' => ['label' => 'Jenis Layanan', 'type' => 'select', 'required' => true, 'tampil' => true,
                    'opsi' => ['SK & Keputusan', 'Permintaan Data & Informasi', 'Surat Keterangan Internal', 'Layanan Tamu & Eksternal', 'Legalisasi Dokumen', 'Lainnya']],
                'jumlah' => ['label' => 'Jumlah', 'type' => 'number', 'required' => true, 'tampil' => true],
                'keterangan' => ['label' => 'Keterangan', 'type' => 'textarea', 'required' => false, 'tampil' => true],
            ],
        ],
        'agenda' => [
            'model' => TvriAgendaKegiatan::class,
            'nama' => 'Agenda Kegiatan',
            'ikon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            'cari' => ['kegiatan', 'lokasi', 'pic'],
            'kolom' => [
                'tanggal' => ['label' => 'Tanggal', 'type' => 'date', 'required' => true, 'tampil' => true],
                'waktu' => ['label' => 'Waktu', 'type' => 'text', 'required' => true, 'tampil' => true],
                'kegiatan' => ['label' => 'Kegiatan', 'type' => 'text', 'required' => true, 'tampil' => true],
                'lokasi' => ['label' => 'Lokasi', 'type' => 'text', 'required' => true, 'tampil' => true],
                'pic' => ['label' => 'PIC', 'type' => 'text', 'required' => false, 'tampil' => true],
                'jenis' => ['label' => 'Jenis Agenda', 'type' => 'select', 'required' => true, 'tampil' => true,
                    'opsi' => ['Hari Ini', 'Minggu Ini', 'Internal TU & Bidang', 'Eksternal', 'Lainnya']],
                'status' => ['label' => 'Status', 'type' => 'select', 'required' => true, 'tampil' => true,
                    'opsi' => ['Terjadwal', 'Mendatang', 'Selesai', 'Dibatalkan']],
            ],
        ],
    ];

    private function getRingkasan(): TvriTuRingkasan
    {
        return TvriTuRingkasan::firstOrCreate(
            ['id' => 1],
            [
                'periode' => 'September 2025',
                'header_waktu' => 'Selasa, 2 September 2025 | 10:24 WIT',
                'online_status' => 'Online | Periode: September 2025',
                'slogan_header' => 'TERTIB ADMINISTRASI • CEPAT LAYANAN • AKURAT DATA • MENDUKUNG KINERJA',
                'slogan_sub' => 'Maluku Bisa!',
                'surat_masuk' => 128,
                'diff_surat_masuk' => '+12% dari bulan lalu',
                'surat_keluar' => 96,
                'diff_surat_keluar' => '+8%',
                'disposisi' => 110,
                'diff_disposisi' => '+15%',
                'agenda_kegiatan' => 24,
                'diff_agenda' => '+20%',
                'layanan_adm' => 86,
                'diff_layanan' => '+10%',
                'service_completion_rate' => 94,
                'target_completion_rate' => 90,
                'disposisi_selesai' => 78,
                'disposisi_proses' => 18,
                'disposisi_menunggu' => 9,
                'disposisi_terlambat' => 5,
                'kat_kementerian' => 32,
                'kat_lpp' => 24,
                'kat_pemda' => 20,
                'kat_vertikal' => 16,
                'kat_mitra' => 14,
                'kat_masyarakat' => 12,
                'kat_lainnya' => 10,
                'layanan_sk' => 24,
                'layanan_data' => 18,
                'layanan_internal' => 22,
                'layanan_eksternal' => 16,
                'layanan_lainnya' => 6,
                'arsip_aktif' => 1250,
                'arsip_inaktif' => 320,
                'arsip_digital' => 850,
                'arsip_retensi' => 42,
                'kepatuhan_administrasi' => 96,
                'target_kepatuhan' => 90,
            ]
        );
    }

    public function index(Request $request)
    {
        $ringkasan = $this->getRingkasan();
        $suratMasukList = TvriSuratMasuk::orderByDesc('tanggal')->orderByDesc('id')->limit(10)->get();
        $suratKeluarList = TvriSuratKeluar::orderByDesc('tanggal')->orderByDesc('id')->limit(10)->get();
        $agendaList = TvriAgendaKegiatan::orderBy('tanggal')->orderBy('waktu')->get();
        $layananList = TvriLayananAdm::orderByDesc('jumlah')->get();
        $sops = TvriTuSop::orderBy('urutan')->get();

        // Count totals (real-time from input tables & ringkasan)
        $actualSmCount = TvriSuratMasuk::count();
        $suratMasukCount = $actualSmCount > 0 ? $actualSmCount : (int) ($ringkasan->surat_masuk ?? 0);
        $diffSuratMasuk = $ringkasan->diff_surat_masuk ?? '+12% dari bulan lalu';

        $actualSkCount = TvriSuratKeluar::count();
        $suratKeluarCount = $actualSkCount > 0 ? $actualSkCount : (int) ($ringkasan->surat_keluar ?? 0);
        $diffSuratKeluar = $ringkasan->diff_surat_keluar ?? '+8%';

        $smDisposisiCount = TvriSuratMasuk::whereIn('status', ['Didisposisikan', 'Diproses', 'Selesai'])->count();
        $disposisiCount = $smDisposisiCount > 0 ? $smDisposisiCount : (int) ($ringkasan->disposisi ?? 0);
        $smDisposisiSelesai = TvriSuratMasuk::where('status', 'Selesai')->count();
        $disposisiSelesai = $smDisposisiSelesai > 0 ? $smDisposisiSelesai : (int) ($ringkasan->disposisi_selesai ?? 0);
        $diffDisposisi = $ringkasan->diff_disposisi ?? '+15%';

        $actualAgendaCount = TvriAgendaKegiatan::count();
        $agendaCount = $actualAgendaCount > 0 ? $actualAgendaCount : (int) ($ringkasan->agenda_kegiatan ?? 0);
        $diffAgenda = $ringkasan->diff_agenda ?? '+20%';

        $actualLayananCount = (int) TvriLayananAdm::sum('jumlah');
        $layananCount = isset($ringkasan->layanan_adm) && $ringkasan->layanan_adm !== null ? (int) $ringkasan->layanan_adm : $actualLayananCount;
        $diffLayanan = $ringkasan->diff_layanan ?? '+10%';

        $targetCompletionRate = (int) ($ringkasan->target_completion_rate ?? 90);
        $serviceCompletionRate = (int) ($ringkasan->service_completion_rate ?? 94);
        if ($serviceCompletionRate >= $targetCompletionRate) {
            $serviceCompletionStatus = '▲ Sangat Memuaskan';
            $serviceCompletionStatusClass = 'text-emerald-600';
        } elseif ($serviceCompletionRate >= 70) {
            $serviceCompletionStatus = '● On Track';
            $serviceCompletionStatusClass = 'text-blue-600';
        } else {
            $serviceCompletionStatus = '▼ Perlu Perhatian';
            $serviceCompletionStatusClass = 'text-amber-600';
        }

        // Live real-time date in WIT (Ambon, Maluku)
        $daysIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $monthsIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $monthsShortIndo = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $now = \Carbon\Carbon::now('Asia/Jayapura');
        $calYear = (int) $request->query('year', $now->year);
        $calMonth = (int) $request->query('month', $now->month);

        if ($calMonth < 1 || $calMonth > 12 || $calYear < 2000 || $calYear > 2100) {
            $calYear = $now->year;
            $calMonth = $now->month;
        }

        $calDate = \Carbon\Carbon::createFromDate($calYear, $calMonth, 1, 'Asia/Jayapura');
        $calMonthName = $monthsIndo[$calMonth];
        $calMonthLabel = sprintf('%s %d', $calMonthName, $calYear);
        $totalDaysInMonth = $calDate->daysInMonth;
        $startDayOfWeek = $calDate->dayOfWeekIso; // 1 = Senin, 7 = Minggu

        // Real-time today values
        $todayDay = ($now->year === $calYear && $now->month === $calMonth) ? $now->day : 0;
        $todayDateFormatted = sprintf('%s, %02d %s %d', $daysIndo[$now->dayOfWeek], $now->day, $monthsIndo[$now->month], $now->year);
        $todayLegend = sprintf('%d %s', $now->day, $monthsShortIndo[$now->month]);

        // Scheduled days in active month from database
        $scheduledDays = TvriAgendaKegiatan::whereYear('tanggal', $calYear)
            ->whereMonth('tanggal', $calMonth)
            ->pluck('tanggal')
            ->map(function ($d) {
                return (int) \Carbon\Carbon::parse($d)->format('j');
            })
            ->unique()
            ->values()
            ->all();

        // Agenda list: Prioritize agendas from today onwards, or latest
        $agendaList = TvriAgendaKegiatan::whereDate('tanggal', '>=', $now->toDateString())
            ->orderBy('tanggal')
            ->orderBy('waktu')
            ->get();

        if ($agendaList->isEmpty()) {
            $agendaList = TvriAgendaKegiatan::orderByDesc('tanggal')->orderBy('waktu')->take(5)->get();
        }

        // If AJAX request or ?realtime=1, return real-time JSON
        if ($request->ajax() || $request->query('realtime') == 1) {
            $agendasData = $agendaList->take(5)->map(function ($ag) use ($now) {
                $agDateStr = $ag->tanggal ? \Carbon\Carbon::parse($ag->tanggal)->format('Y-m-d') : '';
                return [
                    'id' => $ag->id,
                    'waktu' => $ag->waktu ?? '',
                    'tanggal_formatted' => $ag->tanggal ? \Carbon\Carbon::parse($ag->tanggal)->format('d M') : '-',
                    'tanggal_raw' => $agDateStr,
                    'kegiatan' => $ag->kegiatan ?? '',
                    'lokasi' => $ag->lokasi ?? '',
                    'pic' => $ag->pic ?? '',
                    'jenis' => $ag->jenis ?? '',
                    'status' => $ag->status ?? '',
                    'is_today' => ($ag->jenis === 'Hari Ini' || $agDateStr === $now->format('Y-m-d')),
                ];
            });

            return response()->json([
                'success' => true,
                'stats' => [
                    'suratMasukCount' => $suratMasukCount,
                    'diffSuratMasuk' => $diffSuratMasuk,
                    'suratKeluarCount' => $suratKeluarCount,
                    'diffSuratKeluar' => $diffSuratKeluar,
                    'disposisiCount' => $disposisiCount,
                    'disposisiSelesai' => $disposisiSelesai,
                    'diffDisposisi' => $diffDisposisi,
                    'agendaCount' => $agendaCount,
                    'diffAgenda' => $diffAgenda,
                    'layananCount' => $layananCount,
                    'diffLayanan' => $diffLayanan,
                    'targetCompletionRate' => $targetCompletionRate,
                    'serviceCompletionRate' => $serviceCompletionRate,
                    'serviceCompletionStatus' => $serviceCompletionStatus,
                    'serviceCompletionStatusClass' => $serviceCompletionStatusClass,
                ],
                'agendaCount' => $agendaCount,
                'calMonthLabel' => $calMonthLabel,
                'calMonth' => $calMonth,
                'calYear' => $calYear,
                'todayDay' => $todayDay,
                'todayDateFormatted' => $todayDateFormatted,
                'todayLegend' => $todayLegend,
                'totalDaysInMonth' => $totalDaysInMonth,
                'startDayOfWeek' => $startDayOfWeek,
                'scheduledDays' => $scheduledDays,
                'agendas' => $agendasData,
            ]);
        }

        return view('tvri.tu.index', compact(
            'ringkasan',
            'suratMasukList',
            'suratKeluarList',
            'agendaList',
            'layananList',
            'sops',
            'suratMasukCount',
            'diffSuratMasuk',
            'suratKeluarCount',
            'diffSuratKeluar',
            'disposisiCount',
            'disposisiSelesai',
            'diffDisposisi',
            'agendaCount',
            'diffAgenda',
            'layananCount',
            'diffLayanan',
            'targetCompletionRate',
            'serviceCompletionRate',
            'serviceCompletionStatus',
            'serviceCompletionStatusClass',
            'calMonthLabel',
            'calMonthName',
            'calYear',
            'todayDay',
            'todayDateFormatted',
            'todayLegend',
            'totalDaysInMonth',
            'startDayOfWeek',
            'scheduledDays',
            'now'
        ));
    }

    public function manage(Request $request)
    {
        $ringkasan = $this->getRingkasan();
        $suratMasuks = TvriSuratMasuk::orderByDesc('tanggal')->orderByDesc('id')->get();
        $suratKeluars = TvriSuratKeluar::orderByDesc('tanggal')->orderByDesc('id')->get();
        $agendas = TvriAgendaKegiatan::orderBy('tanggal')->orderBy('waktu')->get();
        $layanans = TvriLayananAdm::orderBy('id')->get();
        $sops = TvriTuSop::orderBy('urutan')->get();

        $activeTab = $request->query('tab', 'surat-masuk');

        return view('tvri.tu.manage', compact(
            'ringkasan',
            'suratMasuks',
            'suratKeluars',
            'agendas',
            'layanans',
            'sops',
            'activeTab'
        ));
    }

    public function updateRingkasan(Request $request)
    {
        $ringkasan = $this->getRingkasan();
        $data = $request->except(['_token']);

        $stringDefaults = [
            'periode' => 'September 2025',
            'header_waktu' => 'Selasa, 2 September 2025 | 10:24 WIT',
            'online_status' => 'Online | Periode: September 2025',
            'slogan_header' => 'TERTIB ADMINISTRASI • CEPAT LAYANAN • AKURAT DATA • MENDUKUNG KINERJA',
            'slogan_sub' => 'Maluku Bisa!',
            'diff_surat_masuk' => '+12% dari bulan lalu',
            'diff_surat_keluar' => '+8%',
            'diff_disposisi' => '+15%',
            'diff_agenda' => '+20%',
            'diff_layanan' => '+10%',
        ];

        foreach ($stringDefaults as $field => $default) {
            if (array_key_exists($field, $data)) {
                if ($data[$field] === null || trim((string) $data[$field]) === '') {
                    $data[$field] = !empty($ringkasan->$field) ? $ringkasan->$field : $default;
                }
            }
        }

        $intFields = [
            'surat_masuk', 'surat_keluar', 'disposisi', 'agenda_kegiatan', 'layanan_adm',
            'service_completion_rate', 'target_completion_rate',
            'disposisi_selesai', 'disposisi_proses', 'disposisi_menunggu', 'disposisi_terlambat',
            'kat_kementerian', 'kat_lpp', 'kat_pemda', 'kat_vertikal', 'kat_mitra', 'kat_masyarakat', 'kat_lainnya',
            'layanan_sk', 'layanan_data', 'layanan_internal', 'layanan_eksternal', 'layanan_lainnya',
            'arsip_aktif', 'arsip_inaktif', 'arsip_digital', 'arsip_retensi',
            'kepatuhan_administrasi', 'target_kepatuhan'
        ];

        foreach ($intFields as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = ($data[$field] !== null && $data[$field] !== '') ? (int) $data[$field] : ($ringkasan->$field ?? 0);
            }
        }

        $ringkasan->update($data);

        return redirect()->route('tvri.tu.manage', ['tab' => 'ringkasan'])->with('success', 'Parameter ringkasan operasional Tata Usaha berhasil disimpan.');
    }

    public function reseedDefaults()
    {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'TvriTuDataSeeder']);
        return redirect()->route('tvri.tu.manage')->with('success', 'Data default Tata Usaha berhasil dipulihkan.');
    }

    // ================= CRUD SURAT MASUK =================
    public function storeSuratMasuk(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nomor' => 'required|string|max:100',
            'pengirim' => 'required|string|max:255',
            'perihal' => 'required|string',
            'kategori' => 'nullable|string|max:100',
            'status' => 'required|string|max:50',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:20480',
            'file_url' => 'nullable|string|max:255',
        ]);

        $data = $request->except(['_token', 'file']);
        if ($request->hasFile('file')) {
            $f = $request->file('file');
            $cleanName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $f->getClientOriginalName());
            $path = $f->storeAs('surat_masuk', $cleanName, 'public');
            $publicDir = public_path('storage/surat_masuk');
            if (!file_exists($publicDir)) {
                @mkdir($publicDir, 0777, true);
            }
            @copy(storage_path('app/public/' . $path), public_path('storage/' . $path));
            $data['file_url'] = 'storage/' . $path;
            $data['file_path'] = 'storage/' . $path;
        }

        TvriSuratMasuk::create($data);

        return redirect()->route('tvri.tu.manage', ['tab' => 'surat-masuk'])->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    public function updateSuratMasuk(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nomor' => 'required|string|max:100',
            'pengirim' => 'required|string|max:255',
            'perihal' => 'required|string',
            'kategori' => 'nullable|string|max:100',
            'status' => 'required|string|max:50',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:20480',
            'file_url' => 'nullable|string|max:255',
        ]);

        $sm = TvriSuratMasuk::findOrFail($id);
        $data = $request->except(['_token', '_method', 'file']);
        if ($request->hasFile('file')) {
            $f = $request->file('file');
            $cleanName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $f->getClientOriginalName());
            $path = $f->storeAs('surat_masuk', $cleanName, 'public');
            $publicDir = public_path('storage/surat_masuk');
            if (!file_exists($publicDir)) {
                @mkdir($publicDir, 0777, true);
            }
            @copy(storage_path('app/public/' . $path), public_path('storage/' . $path));
            $data['file_url'] = 'storage/' . $path;
            $data['file_path'] = 'storage/' . $path;
        }

        $sm->update($data);

        return redirect()->route('tvri.tu.manage', ['tab' => 'surat-masuk'])->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroySuratMasuk($id)
    {
        TvriSuratMasuk::findOrFail($id)->delete();
        return redirect()->route('tvri.tu.manage', ['tab' => 'surat-masuk'])->with('success', 'Surat masuk berhasil dihapus.');
    }

    public function bulkDestroySuratMasuk(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (!empty($ids)) {
            TvriSuratMasuk::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.tu.manage', ['tab' => 'surat-masuk'])->with('success', count($ids) . ' surat masuk berhasil dihapus.');
        }
        return redirect()->route('tvri.tu.manage', ['tab' => 'surat-masuk'])->with('error', 'Tidak ada data surat masuk yang dipilih.');
    }

    // ================= CRUD SURAT KELUAR =================
    public function storeSuratKeluar(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nomor' => 'required|string|max:100',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string',
            'status' => 'required|string|max:50',
        ]);

        TvriSuratKeluar::create($request->all());

        return redirect()->route('tvri.tu.manage', ['tab' => 'surat-keluar'])->with('success', 'Surat keluar berhasil ditambahkan.');
    }

    public function updateSuratKeluar(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nomor' => 'required|string|max:100',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string',
            'status' => 'required|string|max:50',
        ]);

        $sk = TvriSuratKeluar::findOrFail($id);
        $sk->update($request->all());

        return redirect()->route('tvri.tu.manage', ['tab' => 'surat-keluar'])->with('success', 'Surat keluar berhasil diperbarui.');
    }

    public function destroySuratKeluar($id)
    {
        TvriSuratKeluar::findOrFail($id)->delete();
        return redirect()->route('tvri.tu.manage', ['tab' => 'surat-keluar'])->with('success', 'Surat keluar berhasil dihapus.');
    }

    public function bulkDestroySuratKeluar(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (!empty($ids)) {
            TvriSuratKeluar::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.tu.manage', ['tab' => 'surat-keluar'])->with('success', count($ids) . ' surat keluar berhasil dihapus.');
        }
        return redirect()->route('tvri.tu.manage', ['tab' => 'surat-keluar'])->with('error', 'Tidak ada data surat keluar yang dipilih.');
    }

    // ================= CRUD AGENDA KEGIATAN =================
    public function storeAgenda(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required|string|max:50',
            'kegiatan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'pic' => 'nullable|string|max:255',
            'jenis' => 'required|string|max:50',
            'status' => 'required|string|max:50',
        ]);

        TvriAgendaKegiatan::create($request->all());

        return redirect()->route('tvri.tu.manage', ['tab' => 'agenda'])->with('success', 'Agenda kegiatan berhasil ditambahkan.');
    }

    public function updateAgenda(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required|string|max:50',
            'kegiatan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'pic' => 'nullable|string|max:255',
            'jenis' => 'required|string|max:50',
            'status' => 'required|string|max:50',
        ]);

        $ag = TvriAgendaKegiatan::findOrFail($id);
        $ag->update($request->all());

        return redirect()->route('tvri.tu.manage', ['tab' => 'agenda'])->with('success', 'Agenda kegiatan berhasil diperbarui.');
    }

    public function destroyAgenda($id)
    {
        TvriAgendaKegiatan::findOrFail($id)->delete();
        return redirect()->route('tvri.tu.manage', ['tab' => 'agenda'])->with('success', 'Agenda kegiatan berhasil dihapus.');
    }

    public function bulkDestroyAgenda(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (!empty($ids)) {
            TvriAgendaKegiatan::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.tu.manage', ['tab' => 'agenda'])->with('success', count($ids) . ' agenda kegiatan berhasil dihapus.');
        }
        return redirect()->route('tvri.tu.manage', ['tab' => 'agenda'])->with('error', 'Tidak ada data agenda yang dipilih.');
    }

    // ================= CRUD LAYANAN ADM =================
    public function storeLayanan(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        TvriLayananAdm::create($request->all());

        return redirect()->route('tvri.tu.manage', ['tab' => 'layanan'])->with('success', 'Data layanan administrasi berhasil ditambahkan.');
    }

    public function updateLayanan(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $ly = TvriLayananAdm::findOrFail($id);
        $ly->update($request->all());

        return redirect()->route('tvri.tu.manage', ['tab' => 'layanan'])->with('success', 'Data layanan administrasi berhasil diperbarui.');
    }

    public function destroyLayanan($id)
    {
        TvriLayananAdm::findOrFail($id)->delete();
        return redirect()->route('tvri.tu.manage', ['tab' => 'layanan'])->with('success', 'Data layanan administrasi berhasil dihapus.');
    }

    public function bulkDestroyLayanan(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (!empty($ids)) {
            TvriLayananAdm::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.tu.manage', ['tab' => 'layanan'])->with('success', count($ids) . ' data layanan berhasil dihapus.');
        }
        return redirect()->route('tvri.tu.manage', ['tab' => 'layanan'])->with('error', 'Tidak ada data layanan yang dipilih.');
    }

    // ================= CRUD FITUR SOP & REGULASI =================
    public function storeSop(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'versi' => 'required|string|max:20',
            'tanggal_efektif' => 'nullable|date',
            'status' => 'required|string|max:50',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:20480',
            'file_url' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        $data = $request->except(['_token', 'file']);
        if ($request->hasFile('file')) {
            $f = $request->file('file');
            $cleanName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $f->getClientOriginalName());
            $path = $f->storeAs('sops', $cleanName, 'public');
            $publicDir = public_path('storage/sops');
            if (!file_exists($publicDir)) {
                @mkdir($publicDir, 0777, true);
            }
            @copy(storage_path('app/public/' . $path), public_path('storage/' . $path));
            $data['file_url'] = 'storage/' . $path;
            $data['file_path'] = 'storage/' . $path;
        }

        TvriTuSop::create($data);

        return redirect()->route('tvri.tu.manage', ['tab' => 'sop'])->with('success', 'SOP & Regulasi berhasil ditambahkan.');
    }

    public function updateSop(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:50',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'versi' => 'required|string|max:20',
            'tanggal_efektif' => 'nullable|date',
            'status' => 'required|string|max:50',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:20480',
            'file_url' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        $sop = TvriTuSop::findOrFail($id);
        $data = $request->except(['_token', '_method', 'file']);
        if ($request->hasFile('file')) {
            $f = $request->file('file');
            $cleanName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $f->getClientOriginalName());
            $path = $f->storeAs('sops', $cleanName, 'public');
            $publicDir = public_path('storage/sops');
            if (!file_exists($publicDir)) {
                @mkdir($publicDir, 0777, true);
            }
            @copy(storage_path('app/public/' . $path), public_path('storage/' . $path));
            $data['file_url'] = 'storage/' . $path;
            $data['file_path'] = 'storage/' . $path;
        }

        $sop->update($data);

        return redirect()->route('tvri.tu.manage', ['tab' => 'sop'])->with('success', 'SOP & Regulasi berhasil diperbarui.');
    }

    public function destroySop($id)
    {
        TvriTuSop::findOrFail($id)->delete();
        return redirect()->route('tvri.tu.manage', ['tab' => 'sop'])->with('success', 'SOP & Regulasi berhasil dihapus.');
    }

    public function bulkDestroySop(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (!empty($ids)) {
            TvriTuSop::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.tu.manage', ['tab' => 'sop'])->with('success', count($ids) . ' SOP berhasil dihapus.');
        }
        return redirect()->route('tvri.tu.manage', ['tab' => 'sop'])->with('error', 'Tidak ada data SOP yang dipilih.');
    }

    // ================= LEGACY METHODS =================
    public function list(Request $request, string $entitas)
    {
        $def = $this->definisi($entitas);
        $model = $def['model'];
        $q = $model::query();
        if ($request->filled('cari')) {
            $cari = $request->query('cari');
            $q->where(function ($sub) use ($def, $cari) {
                foreach ($def['cari'] as $i => $kolom) {
                    $sub = $i === 0 ? $sub->where($kolom, 'like', "%{$cari}%") : $sub->orWhere($kolom, 'like', "%{$cari}%");
                }
            });
        }
        foreach ($def['kolom'] as $nama => $kolom) {
            if ($kolom['type'] === 'select' && $request->filled('f_' . $nama)) {
                $q->where($nama, $request->query('f_' . $nama));
            }
        }

        $kolomUrut = array_key_exists('tanggal', $def['kolom']) ? 'tanggal' : 'id';
        $rows = $q->orderByDesc($kolomUrut)->orderByDesc('id')->paginate(15)->withQueryString();

        return view('tvri.tu.list', ['entitas' => $entitas, 'def' => $def, 'rows' => $rows]);
    }

    public function create(string $entitas)
    {
        $def = $this->definisi($entitas);
        return view('tvri.tu.form', ['entitas' => $entitas, 'def' => $def, 'row' => new $def['model'](), 'mode' => 'create']);
    }

    public function store(Request $request, string $entitas)
    {
        $def = $this->definisi($entitas);
        ($def['model'])::create($this->validated($request, $def));

        return redirect()->route('tvri.tu.list', $entitas)->with('success', $def['nama'] . ' berhasil ditambahkan.');
    }

    public function edit(string $entitas, int $id)
    {
        $def = $this->definisi($entitas);
        $row = $def['model']::findOrFail($id);
        return view('tvri.tu.form', ['entitas' => $entitas, 'def' => $def, 'row' => $row, 'mode' => 'edit']);
    }

    public function update(Request $request, string $entitas, int $id)
    {
        $def = $this->definisi($entitas);
        $row = $def['model']::findOrFail($id);
        $row->update($this->validated($request, $def));

        return redirect()->route('tvri.tu.list', $entitas)->with('success', $def['nama'] . ' berhasil diperbarui.');
    }

    public function destroy(string $entitas, int $id)
    {
        $def = $this->definisi($entitas);
        $def['model']::findOrFail($id)->delete();

        return redirect()->route('tvri.tu.list', $entitas)->with('success', $def['nama'] . ' berhasil dihapus.');
    }

    private function definisi(string $entitas): array
    {
        abort_unless(isset(self::ENTITAS[$entitas]), 404);
        return self::ENTITAS[$entitas];
    }

    private function validated(Request $request, array $def): array
    {
        $rules = [];
        foreach ($def['kolom'] as $nama => $kolom) {
            $r = [$kolom['required'] ? 'required' : 'nullable'];
            $r = array_merge($r, explode('|', match ($kolom['type']) {
                'date' => 'date',
                'time' => 'date_format:H:i',
                'number' => 'integer|min:0',
                default => 'string|max:' . ($kolom['type'] === 'textarea' ? 1000 : 255),
            }));
            if ($kolom['type'] === 'select') {
                $r[] = 'in:' . implode(',', $kolom['opsi']);
            }
            $rules[$nama] = $r;
        }

        return $request->validate($rules);
    }
}
