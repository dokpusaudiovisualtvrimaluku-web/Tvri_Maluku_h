<?php

namespace App\Http\Controllers\Tvri;

use App\Http\Controllers\Controller;
use App\Models\TvriProgram;
use App\Models\TvriProgramRingkasan;
use App\Models\TvriProgramAgenda;
use App\Models\TvriProgramHighlight;
use App\Models\TvriProgramSop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProgramController extends Controller
{
    /**
     * Tampilan Utama DASHBOARD PROGRAM TVRI MALUKU
     */
    public function index(Request $request)
    {
        $ringkasan = Schema::hasTable('tvri_program_ringkasans') 
            ? TvriProgramRingkasan::latest()->first() 
            : null;

        // Total program nyata di database
        $totalProgramDb = TvriProgram::count();

        // 1. Metrik Utama Atas (100% sinkron dinamis dengan database jika ada program)
        if ($totalProgramDb > 0) {
            $tayangCount = TvriProgram::where('status', 'Tayang')->count();
            $terlambatCount = TvriProgram::where('status', 'Terlambat')->count();
            $dalamProduksiCount = TvriProgram::whereIn('tahap', ['Pra Produksi', 'Produksi', 'Pasca Produksi'])->count();
            $selesaiProduksiCount = TvriProgram::whereIn('tahap', ['QC', 'Tayang'])->count();
            $compliance = round((($totalProgramDb - $terlambatCount) / $totalProgramDb) * 100);

            $metrik = [
                'total_direncanakan' => $totalProgramDb,
                'diff_total' => $ringkasan->diff_total ?? '+6%',
                'comp_total' => $ringkasan->comp_total ?? 'vs Agustus 2025',
                'dalam_produksi' => $dalamProduksiCount,
                'diff_produksi' => $ringkasan->diff_produksi ?? '+20%',
                'selesai_produksi' => $selesaiProduksiCount,
                'diff_selesai' => $ringkasan->diff_selesai ?? '+12%',
                'tayang' => $tayangCount,
                'diff_tayang' => $ringkasan->diff_tayang ?? '+14%',
                'terlambat' => $terlambatCount,
                'diff_terlambat' => $ringkasan->diff_terlambat ?? '-50%',
                'schedule_compliance' => $compliance,
                'target_compliance' => $ringkasan->target_compliance ?? 90,
            ];
        } else {
            $metrik = [
                'total_direncanakan' => $ringkasan->total_direncanakan ?? 51,
                'diff_total' => $ringkasan->diff_total ?? '+6%',
                'comp_total' => $ringkasan->comp_total ?? 'vs Agustus 2025',
                'dalam_produksi' => $ringkasan->dalam_produksi ?? 18,
                'diff_produksi' => $ringkasan->diff_produksi ?? '+20%',
                'selesai_produksi' => $ringkasan->selesai_produksi ?? 28,
                'diff_selesai' => $ringkasan->diff_selesai ?? '+12%',
                'tayang' => $ringkasan->tayang ?? 48,
                'diff_tayang' => $ringkasan->diff_tayang ?? '+14%',
                'terlambat' => $ringkasan->terlambat ?? 3,
                'diff_terlambat' => $ringkasan->diff_terlambat ?? '-50%',
                'schedule_compliance' => $ringkasan->schedule_compliance ?? 94,
                'target_compliance' => $ringkasan->target_compliance ?? 90,
            ];
        }

        // 2. Tahap Produksi Program (Alur Pipeline Produksi)
        if ($totalProgramDb > 0) {
            $calcTahap = function($nama, $filterTahap, $color, $badge) use ($totalProgramDb) {
                $cnt = is_array($filterTahap) 
                    ? TvriProgram::whereIn('tahap', $filterTahap)->count()
                    : TvriProgram::where('tahap', $filterTahap)->count();
                $pct = round(($cnt / $totalProgramDb) * 100);
                return [
                    'nama' => $nama,
                    'jumlah' => $cnt,
                    'persen' => $pct,
                    'color' => $color,
                    'badge' => $badge,
                ];
            };

            $tahapProduksi = [
                [
                    'nama' => 'Perencanaan',
                    'jumlah' => $totalProgramDb,
                    'persen' => 100,
                    'color' => 'bg-purple-600',
                    'badge' => 'bg-purple-50 text-purple-700 border-purple-200',
                ],
                $calcTahap('Pra Produksi', ['Pra Produksi', 'Produksi', 'Pasca Produksi', 'QC', 'Tayang'], 'bg-blue-600', 'bg-blue-50 text-blue-700 border-blue-200'),
                $calcTahap('Produksi', ['Produksi', 'Pasca Produksi', 'QC', 'Tayang'], 'bg-cyan-600', 'bg-cyan-50 text-cyan-700 border-cyan-200'),
                $calcTahap('Pasca Produksi', ['Pasca Produksi', 'QC', 'Tayang'], 'bg-indigo-600', 'bg-indigo-50 text-indigo-700 border-indigo-200'),
                $calcTahap('QC', ['QC', 'Tayang'], 'bg-amber-600', 'bg-amber-50 text-amber-700 border-amber-200'),
                $calcTahap('Tayang', 'Tayang', 'bg-emerald-600', 'bg-emerald-50 text-emerald-700 border-emerald-200'),
            ];
        } else {
            $tahapProduksi = [
                [
                    'nama' => 'Perencanaan', 
                    'jumlah' => $ringkasan->tahap_perencanaan_count ?? 51, 
                    'persen' => $ringkasan->tahap_perencanaan_pct ?? 100, 
                    'color' => 'bg-purple-600', 
                    'badge' => 'bg-purple-50 text-purple-700 border-purple-200'
                ],
                [
                    'nama' => 'Pra Produksi', 
                    'jumlah' => $ringkasan->tahap_pra_produksi_count ?? 45, 
                    'persen' => $ringkasan->tahap_pra_produksi_pct ?? 88, 
                    'color' => 'bg-blue-600', 
                    'badge' => 'bg-blue-50 text-blue-700 border-blue-200'
                ],
                [
                    'nama' => 'Produksi', 
                    'jumlah' => $ringkasan->tahap_produksi_count ?? 36, 
                    'persen' => $ringkasan->tahap_produksi_pct ?? 71, 
                    'color' => 'bg-cyan-600', 
                    'badge' => 'bg-cyan-50 text-cyan-700 border-cyan-200'
                ],
                [
                    'nama' => 'Pasca Produksi', 
                    'jumlah' => $ringkasan->tahap_pasca_produksi_count ?? 28, 
                    'persen' => $ringkasan->tahap_pasca_produksi_pct ?? 55, 
                    'color' => 'bg-indigo-600', 
                    'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200'
                ],
                [
                    'nama' => 'QC', 
                    'jumlah' => $ringkasan->tahap_qc_count ?? 28, 
                    'persen' => $ringkasan->tahap_qc_pct ?? 55, 
                    'color' => 'bg-amber-600', 
                    'badge' => 'bg-amber-50 text-amber-700 border-amber-200'
                ],
                [
                    'nama' => 'Tayang', 
                    'jumlah' => $ringkasan->tahap_tayang_count ?? 48, 
                    'persen' => $ringkasan->tahap_tayang_pct ?? 94, 
                    'color' => 'bg-emerald-600', 
                    'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'
                ],
            ];
        }

        // 3. Tren Produksi & Penayangan (Jan - Sep)
        $trenBulan = [
            ['bulan' => 'Jan', 'rencana' => 38, 'tayang' => 36, 'realisasi' => 95],
            ['bulan' => 'Feb', 'rencana' => 40, 'tayang' => 38, 'realisasi' => 95],
            ['bulan' => 'Mar', 'rencana' => 42, 'tayang' => 40, 'realisasi' => 95],
            ['bulan' => 'Apr', 'rencana' => 44, 'tayang' => 41, 'realisasi' => 93],
            ['bulan' => 'Mei', 'rencana' => 45, 'tayang' => 43, 'realisasi' => 96],
            ['bulan' => 'Jun', 'rencana' => 46, 'tayang' => 44, 'realisasi' => 96],
            ['bulan' => 'Jul', 'rencana' => 48, 'tayang' => 45, 'realisasi' => 94],
            ['bulan' => 'Agu', 'rencana' => 48, 'tayang' => 46, 'realisasi' => 96],
            ['bulan' => 'Sep', 'rencana' => ($totalProgramDb ?: 51), 'tayang' => ($totalProgramDb > 0 ? $metrik['tayang'] : 48), 'realisasi' => ($totalProgramDb > 0 ? round(($metrik['tayang'] / $totalProgramDb) * 100) : 94)],
        ];

        // 4. Distribusi Jenis Program (Dinamis tanpa distorsi fallback)
        if ($totalProgramDb > 0) {
            $categories = [
                ['jenis' => 'Berita', 'color' => 'bg-blue-600', 'textColor' => 'text-blue-600'],
                ['jenis' => 'Current Affairs', 'color' => 'bg-indigo-600', 'textColor' => 'text-indigo-600'],
                ['jenis' => 'Budaya', 'color' => 'bg-amber-600', 'textColor' => 'text-amber-600'],
                ['jenis' => 'Pendidikan', 'color' => 'bg-emerald-600', 'textColor' => 'text-emerald-600'],
                ['jenis' => 'Pariwisata', 'color' => 'bg-teal-600', 'textColor' => 'text-teal-600'],
                ['jenis' => 'Talkshow', 'color' => 'bg-purple-600', 'textColor' => 'text-purple-600'],
                ['jenis' => 'Religi', 'color' => 'bg-cyan-600', 'textColor' => 'text-cyan-600'],
                ['jenis' => 'Lainnya', 'color' => 'bg-slate-600', 'textColor' => 'text-slate-600'],
            ];

            $distribusiJenis = array_map(function($c) use ($totalProgramDb) {
                $cnt = TvriProgram::where('kategori', $c['jenis'])->count();
                $pct = round(($cnt / $totalProgramDb) * 100);
                return [
                    'jenis' => $c['jenis'],
                    'persen' => $pct,
                    'jumlah' => $cnt,
                    'color' => $c['color'],
                    'textColor' => $c['textColor'],
                ];
            }, $categories);
        } else {
            $distribusiJenis = [
                ['jenis' => 'Berita', 'persen' => 24, 'jumlah' => 12, 'color' => 'bg-blue-600', 'textColor' => 'text-blue-600'],
                ['jenis' => 'Current Affairs', 'persen' => 16, 'jumlah' => 8, 'color' => 'bg-indigo-600', 'textColor' => 'text-indigo-600'],
                ['jenis' => 'Budaya', 'persen' => 14, 'jumlah' => 7, 'color' => 'bg-amber-600', 'textColor' => 'text-amber-600'],
                ['jenis' => 'Pendidikan', 'persen' => 12, 'jumlah' => 6, 'color' => 'bg-emerald-600', 'textColor' => 'text-emerald-600'],
                ['jenis' => 'Pariwisata', 'persen' => 12, 'jumlah' => 6, 'color' => 'bg-teal-600', 'textColor' => 'text-teal-600'],
                ['jenis' => 'Talkshow', 'persen' => 10, 'jumlah' => 5, 'color' => 'bg-purple-600', 'textColor' => 'text-purple-600'],
                ['jenis' => 'Religi', 'persen' => 8, 'jumlah' => 4, 'color' => 'bg-cyan-600', 'textColor' => 'text-cyan-600'],
                ['jenis' => 'Lainnya', 'persen' => 6, 'jumlah' => 3, 'color' => 'bg-slate-600', 'textColor' => 'text-slate-600'],
            ];
        }

        // 5. Status Program (Dinamis dari database nyata)
        if ($totalProgramDb > 0) {
            $statuses = [
                ['status' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['status' => 'On Schedule', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'],
                ['status' => 'Dalam Produksi', 'badge' => 'bg-purple-50 text-purple-700 border-purple-200'],
                ['status' => 'Terlambat', 'badge' => 'bg-rose-50 text-rose-700 border-rose-200'],
                ['status' => 'Dibatalkan', 'badge' => 'bg-slate-100 text-slate-500 border-slate-200'],
            ];

            $statusProgram = array_map(function($s) use ($totalProgramDb) {
                $cnt = TvriProgram::where('status', $s['status'])->count();
                $pct = round(($cnt / $totalProgramDb) * 100);
                return [
                    'status' => $s['status'],
                    'persen' => $pct,
                    'jumlah' => $cnt,
                    'badge' => $s['badge'],
                ];
            }, $statuses);
        } else {
            $statusProgram = [
                ['status' => 'Tayang', 'persen' => 94, 'jumlah' => 48, 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['status' => 'On Schedule', 'persen' => 92, 'jumlah' => 47, 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'],
                ['status' => 'Dalam Produksi', 'persen' => 6, 'jumlah' => 3, 'badge' => 'bg-purple-50 text-purple-700 border-purple-200'],
                ['status' => 'Terlambat', 'persen' => 6, 'jumlah' => 3, 'badge' => 'bg-rose-50 text-rose-700 border-rose-200'],
                ['status' => 'Dibatalkan', 'persen' => 0, 'jumlah' => 0, 'badge' => 'bg-slate-100 text-slate-500 border-slate-200'],
            ];
        }

        // 6. Program Unggulan (Top 5 Tayang Bulan Ini - Dinamis dari TvriProgram)
        $knownTayang = [
            'Maluku Hari Ini' => 12,
            'Pesona Maluku' => 8,
            'Dialog Maluku' => 6,
            'Maluku Inspirasi' => 5,
            'Jejak Budaya' => 5,
            'Maluku Pagi Ini' => 10,
            'Maluku Siang Ini' => 8,
            'Maluku Malam Ini' => 9,
            'Warta Ambon Manise' => 7,
            'Dunia Dalam Berita Lokal' => 6,
        ];

        $topPrograms = TvriProgram::orderByDesc('rating')->orderByDesc('audience')->take(5)->get();
        if ($topPrograms->isNotEmpty()) {
            $programUnggulan = $topPrograms->map(function($p) use ($knownTayang) {
                $aud = is_numeric($p->audience) ? (round($p->audience / 1000) . 'K') : ($p->audience ?: '300K');
                $tayang = $knownTayang[$p->judul] ?? max(1, round(($p->rating ?? 2.5) * 3));
                return [
                    'nama' => $p->judul,
                    'kategori' => $p->kategori,
                    'tayang' => $tayang,
                    'rating' => number_format((float)($p->rating ?: 2.8), 1),
                    'audience' => $aud,
                ];
            })->toArray();
        } else {
            $programUnggulan = [
                ['nama' => 'Maluku Hari Ini', 'kategori' => 'Berita', 'tayang' => 12, 'rating' => 2.9, 'audience' => '420K'],
                ['nama' => 'Pesona Maluku', 'kategori' => 'Pariwisata', 'tayang' => 8, 'rating' => 2.8, 'audience' => '360K'],
                ['nama' => 'Dialog Maluku', 'kategori' => 'Talkshow', 'tayang' => 6, 'rating' => 2.7, 'audience' => '320K'],
                ['nama' => 'Maluku Inspirasi', 'kategori' => 'Talkshow', 'tayang' => 5, 'rating' => 2.7, 'audience' => '280K'],
                ['nama' => 'Jejak Budaya', 'kategori' => 'Budaya', 'tayang' => 5, 'rating' => 2.8, 'audience' => '310K'],
            ];
        }

        // 7. Performa Penayangan
        $performa = [
            'total_audience' => $ringkasan->total_audience ?? '1,2 Juta',
            'diff_audience' => $ringkasan->diff_audience ?? '+18%',
            'rating_rata' => $ringkasan->rating_rata ?? '2,8',
            'diff_rating' => $ringkasan->diff_rating ?? '+12%',
            'share' => $ringkasan->share ?? '12%',
            'diff_share' => $ringkasan->diff_share ?? '+20%',
            'durasi_tonton' => $ringkasan->durasi_tonton ?? '18 Menit',
            'diff_durasi' => $ringkasan->diff_durasi ?? '+15%',
        ];

        // 8. Kalender Penayangan & Agenda Penting
        $badgeForTipe = function($tipe) {
            return match($tipe) {
                'Tayang' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'Produksi' => 'bg-blue-50 text-blue-700 border-blue-200',
                'Deadline' => 'bg-rose-50 text-rose-700 border-rose-200',
                'Event Khusus' => 'bg-amber-50 text-amber-700 border-amber-200 animate-pulse',
                default => 'bg-slate-100 text-slate-700 border-slate-200',
            };
        };

        if (Schema::hasTable('tvri_program_agendas')) {
            $dbAgendas = TvriProgramAgenda::orderBy('urutan', 'asc')->get();
            if ($dbAgendas->isNotEmpty()) {
                $agendaPenting = $dbAgendas->map(function($a) use ($badgeForTipe) {
                    return [
                        'tanggal' => $a->tanggal,
                        'kegiatan' => $a->kegiatan,
                        'tipe' => $a->tipe,
                        'badge' => $badgeForTipe($a->tipe),
                    ];
                })->toArray();
            }
        }

        if (empty($agendaPenting)) {
            $agendaPenting = [
                ['tanggal' => '2 Sep 2025', 'kegiatan' => 'Tayang: Maluku Hari Ini (Live)', 'tipe' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['tanggal' => '5 Sep 2025', 'kegiatan' => 'Syuting: Pesona Maluku (Pantai Ora)', 'tipe' => 'Produksi', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'],
                ['tanggal' => '7 Sep 2025', 'kegiatan' => 'Tayang: Dialog Maluku', 'tipe' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['tanggal' => '10 Sep 2025', 'kegiatan' => 'Produksi: Maluku Belajar', 'tipe' => 'Produksi', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'],
                ['tanggal' => '15 Sep 2025', 'kegiatan' => 'Special Program: HUT Maluku', 'tipe' => 'Event Khusus', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200 animate-pulse'],
                ['tanggal' => '20 Sep 2025', 'kegiatan' => 'Tayang: Jejak Budaya', 'tipe' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['tanggal' => '25 Sep 2025', 'kegiatan' => 'Evaluasi Program Triwulan III', 'tipe' => 'Deadline', 'badge' => 'bg-rose-50 text-rose-700 border-rose-200'],
            ];
        }

        // 9. Program Highlight
        $colorPalettes = [
            'from-blue-600 to-indigo-700',
            'from-teal-600 to-emerald-700',
            'from-purple-600 to-indigo-700',
            'from-amber-600 to-orange-700',
            'from-cyan-600 to-blue-700',
        ];

        if (Schema::hasTable('tvri_program_highlights')) {
            $dbHighlights = TvriProgramHighlight::orderBy('urutan', 'asc')->get();
            if ($dbHighlights->isNotEmpty()) {
                $highlights = $dbHighlights->values()->map(function($h, $i) use ($colorPalettes) {
                    return [
                        'judul' => $h->judul,
                        'tagline' => $h->tagline,
                        'icon' => $h->icon ?? 'tv',
                        'color' => $colorPalettes[$i % count($colorPalettes)],
                    ];
                })->toArray();
            }
        }

        if (empty($highlights)) {
            $highlights = [
                ['judul' => 'MALUKU HARI INI', 'tagline' => 'Berita Terpercaya untuk Anda', 'icon' => 'newspaper', 'color' => 'from-blue-600 to-indigo-700'],
                ['judul' => 'PESONA MALUKU', 'tagline' => 'Keindahan Tiada Akhir', 'icon' => 'camera', 'color' => 'from-teal-600 to-emerald-700'],
                ['judul' => 'DIALOG MALUKU', 'tagline' => 'Ruang Aspirasi Masyarakat', 'icon' => 'chat', 'color' => 'from-purple-600 to-indigo-700'],
                ['judul' => 'JEJAK BUDAYA', 'tagline' => 'Merawat Warisan Leluhur', 'icon' => 'sparkles', 'color' => 'from-amber-600 to-orange-700'],
                ['judul' => 'MALUKU BELAJAR', 'tagline' => 'Untuk Generasi Emas', 'icon' => 'academic', 'color' => 'from-cyan-600 to-blue-700'],
            ];
        }

        // 10. Dokumen & Laporan
        $dokumenLaporan = [
            ['nama' => 'Rundown Program', 'desc' => 'Jadwal detail penayangan harian & mingguan', 'badge' => 'PDF', 'rute' => route('dokumen.documents.index')],
            ['nama' => 'Laporan Produksi', 'desc' => 'Rekapitulasi progres pra, taping, dan editing', 'badge' => 'DOC', 'rute' => route('dokumen.documents.index')],
            ['nama' => 'Laporan Penayangan', 'desc' => 'Kepatuhan jam tayang & log pemancar siaran', 'badge' => 'XLS', 'rute' => route('dokumen.documents.index')],
            ['nama' => 'Evaluasi Program', 'desc' => 'Hasil telaah rating, share & kepatuhan jadwal', 'badge' => 'PDF', 'rute' => route('dokumen.documents.index')],
            ['nama' => 'Arsip Program', 'desc' => 'Koleksi master rekaman & dokumentasi video', 'badge' => 'DMS', 'rute' => route('dokumen.folders.index')],
        ];

        // 11. Data SOP Program & Siaran
        if (Schema::hasTable('tvri_program_sops')) {
            if (TvriProgramSop::count() === 0) {
                $this->seedDefaultSops();
            }
            $sops = TvriProgramSop::orderBy('urutan', 'asc')->get();
        } else {
            $sops = collect();
        }

        // 12. Daftar Program Berjalan (Dinamis dari Database)
        $programs = TvriProgram::orderBy('id', 'asc')->paginate(15);

        // Metadata Header & Waktu Real-Time
        $nowWit = now()->setTimezone('Asia/Jayapura')->locale('id');
        $hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$nowWit->dayOfWeek];
        $bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$nowWit->month - 1];

        $meta = [
            'waktu' => $ringkasan->header_waktu ?? ($hariIndo . ', ' . $nowWit->day . ' ' . $bulanIndo . ' ' . $nowWit->year . ' | ' . $nowWit->format('H:i') . ' WIT'),
            'status' => $ringkasan->live_status ?? ('Live System | Periode: ' . ($ringkasan->periode ?? 'September 2025')),
            'periode' => $ringkasan->periode ?? 'September 2025',
            'slogan_header' => $ringkasan->slogan_header ?? 'KONTEN BERKUALITAS, MENGANGKAT POTENSI MALUKU, UNTUK INDONESIA',
            'slogan_sub' => $ringkasan->slogan_sub ?? 'Dari Maluku untuk Indonesia',
            'footer_slogan' => $ringkasan->footer_slogan ?? 'Maluku Bisa! Indonesia Maju!',
            'footer_nilai' => $ringkasan->footer_nilai ?? 'Profesional | Konten Lokal, Dampak Global | Kolaboratif | Inovatif | Berkelanjutan',
        ];

        return view('tvri.program.dashboard', compact(
            'metrik', 'tahapProduksi', 'trenBulan', 'distribusiJenis', 'statusProgram',
            'programUnggulan', 'performa', 'agendaPenting', 'highlights', 'dokumenLaporan',
            'sops', 'programs', 'totalProgramDb', 'meta'
        ));
    }

    /**
     * Halaman Input & Kelola Data Program TVRI Maluku
     */
    public function manage(Request $request)
    {
        // Proteksi Kepala Stasiun: Kepala Stasiun hanya monitoring
        if (auth()->check()) {
            $user = auth()->user();
            $isKepalaStasiun = $user->hasRole('KEPALA_DINAS') || $user->hasRole('Kepala Stasiun') || $user->username === 'kepala.stasiun' || str_contains(strtolower($user->name ?? ''), 'kepala stasiun');
            if ($isKepalaStasiun) {
                return redirect()->route('tvri.program.dashboard')->with('warning', 'Akun Kepala Stasiun memiliki akses monitoring eksekutif.');
            }
        }

        $query = TvriProgram::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->get('kategori'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $programs = $query->orderBy('id', 'desc')->paginate(12)->withQueryString();
        $totalPrograms = TvriProgram::count();

        $kategoriList = ['Berita', 'Current Affairs', 'Budaya', 'Pendidikan', 'Pariwisata', 'Talkshow', 'Religi', 'Lainnya'];
        $tahapList = ['Perencanaan', 'Pra Produksi', 'Produksi', 'Pasca Produksi', 'QC', 'Tayang'];
        $statusList = ['Tayang', 'On Schedule', 'Dalam Produksi', 'Editing', 'Terlambat', 'Selesai'];

        // Ringkasan & Agenda & Highlight & SOP
        $ringkasan = Schema::hasTable('tvri_program_ringkasans') 
            ? TvriProgramRingkasan::latest()->first() 
            : null;

        $agendas = Schema::hasTable('tvri_program_agendas')
            ? TvriProgramAgenda::orderBy('urutan', 'asc')->get()
            : collect();

        $highlights = Schema::hasTable('tvri_program_highlights')
            ? TvriProgramHighlight::orderBy('urutan', 'asc')->get()
            : collect();

        if (Schema::hasTable('tvri_program_sops')) {
            if (TvriProgramSop::count() === 0) {
                $this->seedDefaultSops();
            }
            $sops = TvriProgramSop::orderBy('urutan', 'asc')->get();
        } else {
            $sops = collect();
        }

        $tab = $request->get('tab', 'program');

        return view('tvri.program.manage', compact(
            'programs', 'totalPrograms', 'kategoriList', 'tahapList', 'statusList',
            'ringkasan', 'agendas', 'highlights', 'sops', 'tab'
        ));
    }

    /**
     * Simpan Program Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'tahap' => 'required|string|max:100',
            'target_tayang' => 'nullable|date',
            'status' => 'required|string|max:100',
            'pic' => 'required|string|max:100',
            'audience' => 'nullable|numeric',
            'rating' => 'nullable|numeric',
        ]);

        TvriProgram::create($validated);

        return redirect()->route('tvri.program.manage', ['tab' => 'program'])->with('success', "Program '{$validated['judul']}' berhasil ditambahkan ke pangkalan data TVRI Maluku.");
    }

    /**
     * Update Program
     */
    public function update(Request $request, $id)
    {
        $program = TvriProgram::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'tahap' => 'required|string|max:100',
            'target_tayang' => 'nullable|date',
            'status' => 'required|string|max:100',
            'pic' => 'required|string|max:100',
            'audience' => 'nullable|numeric',
            'rating' => 'nullable|numeric',
        ]);

        $program->update($validated);

        return redirect()->route('tvri.program.manage', ['tab' => 'program'])->with('success', "Program '{$program->judul}' berhasil diperbarui.");
    }

    /**
     * Hapus Program
     */
    public function destroy($id)
    {
        $program = TvriProgram::findOrFail($id);
        $judul = $program->judul;
        $program->delete();

        return redirect()->route('tvri.program.manage', ['tab' => 'program'])->with('success', "Program '{$judul}' berhasil dihapus.");
    }

    /**
     * Hapus Masal / Hapus Semua Program (Centrang)
     */
    public function bulkDestroy(Request $request)
    {
        if ($request->input('action') === 'all') {
            $count = TvriProgram::count();
            TvriProgram::query()->delete();
            return redirect()->route('tvri.program.manage', ['tab' => 'program'])->with('success', "Seluruh data program ({$count} data) berhasil dihapus.");
        }

        $ids = $request->input('ids');
        if (is_string($ids)) {
            $ids = array_filter(array_map('intval', explode(',', $ids)));
        }

        if (!empty($ids) && is_array($ids)) {
            $count = TvriProgram::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.program.manage', ['tab' => 'program'])->with('success', "{$count} data program terpilih berhasil dihapus.");
        }

        return redirect()->route('tvri.program.manage', ['tab' => 'program'])->with('warning', 'Tidak ada data program yang dipilih.');
    }

    /**
     * Update Ringkasan & Metrik Program TVRI Maluku
     */
    public function updateRingkasan(Request $request)
    {
        $validated = $request->validate([
            'periode' => 'required|string|max:100',
            'total_direncanakan' => 'required|integer|min:0',
            'diff_total' => 'nullable|string|max:20',
            'dalam_produksi' => 'required|integer|min:0',
            'diff_produksi' => 'nullable|string|max:20',
            'selesai_produksi' => 'required|integer|min:0',
            'diff_selesai' => 'nullable|string|max:20',
            'tayang' => 'required|integer|min:0',
            'diff_tayang' => 'nullable|string|max:20',
            'terlambat' => 'required|integer|min:0',
            'diff_terlambat' => 'nullable|string|max:20',
            'schedule_compliance' => 'required|integer|min:0|max:100',
            'target_compliance' => 'required|integer|min:0|max:100',

            'tahap_perencanaan_count' => 'nullable|integer',
            'tahap_perencanaan_pct' => 'nullable|integer',
            'tahap_pra_produksi_count' => 'nullable|integer',
            'tahap_pra_produksi_pct' => 'nullable|integer',
            'tahap_produksi_count' => 'nullable|integer',
            'tahap_produksi_pct' => 'nullable|integer',
            'tahap_pasca_produksi_count' => 'nullable|integer',
            'tahap_pasca_produksi_pct' => 'nullable|integer',
            'tahap_qc_count' => 'nullable|integer',
            'tahap_qc_pct' => 'nullable|integer',
            'tahap_tayang_count' => 'nullable|integer',
            'tahap_tayang_pct' => 'nullable|integer',

            'total_audience' => 'nullable|string|max:50',
            'diff_audience' => 'nullable|string|max:20',
            'rating_rata' => 'nullable|string|max:20',
            'diff_rating' => 'nullable|string|max:20',
            'share' => 'nullable|string|max:20',
            'diff_share' => 'nullable|string|max:20',
            'durasi_tonton' => 'nullable|string|max:50',
            'diff_durasi' => 'nullable|string|max:20',

            'header_waktu' => 'nullable|string|max:255',
            'live_status' => 'nullable|string|max:255',
            'slogan_header' => 'nullable|string|max:255',
            'slogan_sub' => 'nullable|string|max:255',
            'footer_slogan' => 'nullable|string|max:255',
            'footer_nilai' => 'nullable|string|max:255',
        ]);

        $ringkasan = TvriProgramRingkasan::first();
        if ($ringkasan) {
            $ringkasan->update($validated);
        } else {
            TvriProgramRingkasan::create($validated);
        }

        return redirect()->route('tvri.program.manage', ['tab' => 'ringkasan'])->with('success', 'Metrik Utama & Parameter Program TVRI Maluku berhasil diperbarui dan disinkronkan ke Executive Command Center!');
    }

    /**
     * Tambah Agenda Kalender Penayangan
     */
    public function storeAgenda(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|string|max:50',
            'kegiatan' => 'required|string|max:255',
            'tipe' => 'required|string|max:50',
            'urutan' => 'nullable|integer',
        ]);

        $validated['urutan'] = $validated['urutan'] ?? ((TvriProgramAgenda::max('urutan') ?? 0) + 1);

        TvriProgramAgenda::create($validated);

        return redirect()->route('tvri.program.manage', ['tab' => 'agenda'])->with('success', 'Agenda Kalender Penayangan berhasil ditambahkan.');
    }

    /**
     * Hapus Agenda Kalender Penayangan
     */
    public function destroyAgenda($id)
    {
        $agenda = TvriProgramAgenda::findOrFail($id);
        $agenda->delete();

        return redirect()->route('tvri.program.manage', ['tab' => 'agenda'])->with('success', 'Agenda berhasil dihapus.');
    }

    /**
     * Hapus Masal / Hapus Semua Agenda Penayangan (Centrang)
     */
    public function bulkDestroyAgenda(Request $request)
    {
        if ($request->input('action') === 'all') {
            $count = TvriProgramAgenda::count();
            TvriProgramAgenda::query()->delete();
            return redirect()->route('tvri.program.manage', ['tab' => 'agenda'])->with('success', "Seluruh agenda penayangan ({$count} data) berhasil dihapus.");
        }

        $ids = $request->input('ids');
        if (is_string($ids)) {
            $ids = array_filter(array_map('intval', explode(',', $ids)));
        }

        if (!empty($ids) && is_array($ids)) {
            $count = TvriProgramAgenda::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.program.manage', ['tab' => 'agenda'])->with('success', "{$count} agenda penayangan terpilih berhasil dihapus.");
        }

        return redirect()->route('tvri.program.manage', ['tab' => 'agenda'])->with('warning', 'Tidak ada agenda yang dipilih.');
    }

    /**
     * Tambah Highlight Program
     */
    public function storeHighlight(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tagline' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'urutan' => 'nullable|integer',
        ]);

        $validated['urutan'] = $validated['urutan'] ?? ((TvriProgramHighlight::max('urutan') ?? 0) + 1);

        TvriProgramHighlight::create($validated);

        return redirect()->route('tvri.program.manage', ['tab' => 'highlight'])->with('success', 'Program Highlight berhasil ditambahkan.');
    }

    /**
     * Hapus Highlight Program
     */
    public function destroyHighlight($id)
    {
        $hl = TvriProgramHighlight::findOrFail($id);
        $hl->delete();

        return redirect()->route('tvri.program.manage', ['tab' => 'highlight'])->with('success', 'Program Highlight berhasil dihapus.');
    }

    /**
     * Hapus Masal / Hapus Semua Highlight Program (Centrang)
     */
    public function bulkDestroyHighlight(Request $request)
    {
        if ($request->input('action') === 'all') {
            $count = TvriProgramHighlight::count();
            TvriProgramHighlight::query()->delete();
            return redirect()->route('tvri.program.manage', ['tab' => 'highlight'])->with('success', "Seluruh program highlight ({$count} data) berhasil dihapus.");
        }

        $ids = $request->input('ids');
        if (is_string($ids)) {
            $ids = array_filter(array_map('intval', explode(',', $ids)));
        }

        if (!empty($ids) && is_array($ids)) {
            $count = TvriProgramHighlight::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.program.manage', ['tab' => 'highlight'])->with('success', "{$count} program highlight terpilih berhasil dihapus.");
        }

        return redirect()->route('tvri.program.manage', ['tab' => 'highlight'])->with('warning', 'Tidak ada program highlight yang dipilih.');
    }

    /**
     * Simpan SOP Baru
     */
    public function storeSop(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'suara_siaran' => 'nullable|string|max:255',
            'revisi' => 'nullable|string|max:50',
            'status' => 'required|string|max:50',
            'file_url' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        $validated['urutan'] = $validated['urutan'] ?? ((TvriProgramSop::max('urutan') ?? 0) + 1);

        if ($request->hasFile("file")) { $clean = time()."_".preg_replace("/[^a-zA-Z0-9_\.-]/","_", $request->file("file")->getClientOriginalName()); $path = $request->file("file")->storeAs("sops", $clean, "public"); $validated["file_url"] = "storage/".$path; } TvriProgramSop::create($validated);

        if ($request->input('redirect_to') === 'dokumen.dashboard') {
            return redirect()->to(route('dokumen.dashboard') . '#sop')->with('success', "Dokumen SOP '{$validated['judul']}' ({$validated['kode']}) berhasil ditambahkan ke pangkalan data.");
        }

        return redirect()->route('tvri.program.manage', ['tab' => 'sop'])->with('success', "SOP '{$validated['judul']}' ({$validated['kode']}) berhasil ditambahkan.");
    }

    /**
     * Update SOP
     */
    public function updateSop(Request $request, $id)
    {
        $sop = TvriProgramSop::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|string|max:50',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'suara_siaran' => 'nullable|string|max:255',
            'revisi' => 'nullable|string|max:50',
            'status' => 'required|string|max:50',
            'file_url' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        if ($request->hasFile("file")) { $clean = time()."_".preg_replace("/[^a-zA-Z0-9_\.-]/","_", $request->file("file")->getClientOriginalName()); $path = $request->file("file")->storeAs("sops", $clean, "public"); $validated["file_url"] = "storage/".$path; } $sop->update($validated);

        return redirect()->route('tvri.program.manage', ['tab' => 'sop'])->with('success', "SOP '{$sop->judul}' berhasil diperbarui.");
    }

    /**
     * Hapus SOP
     */
    public function destroySop($id)
    {
        $sop = TvriProgramSop::findOrFail($id);
        $judul = $sop->judul;
        $sop->delete();

        return redirect()->route('tvri.program.manage', ['tab' => 'sop'])->with('success', "SOP '{$judul}' berhasil dihapus.");
    }

    /**
     * Hapus Masal SOP (Centrang)
     */
    public function bulkDestroySop(Request $request)
    {
        if ($request->input('action') === 'all') {
            $count = TvriProgramSop::count();
            TvriProgramSop::query()->delete();
            return redirect()->route('tvri.program.manage', ['tab' => 'sop'])->with('success', "Seluruh data SOP ({$count} data) berhasil dihapus.");
        }

        $ids = $request->input('ids');
        if (is_string($ids)) {
            $ids = array_filter(array_map('intval', explode(',', $ids)));
        }

        if (!empty($ids) && is_array($ids)) {
            $count = TvriProgramSop::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.program.manage', ['tab' => 'sop'])->with('success', "{$count} data SOP terpilih berhasil dihapus.");
        }

        return redirect()->route('tvri.program.manage', ['tab' => 'sop'])->with('warning', 'Tidak ada data SOP yang dipilih.');
    }

    /**
     * Seed Standar 8 SOP Program & Siaran TVRI Maluku jika kosong
     */
    protected function seedDefaultSops()
    {
        $defaultSops = [
            ['kode' => 'SOP-PRG-001', 'judul' => 'SOP Perencanaan & Pra Produksi Program Acara', 'kategori' => 'Pra Produksi', 'suara_siaran' => 'Naskah Dubbing & Audio Guide', 'revisi' => 'Rev. 03 / 2024', 'status' => 'Aktif', 'urutan' => 1, 'keterangan' => 'Prosedur penyusunan proposal, rundown kasar, anggaran biaya, dan penetapan tim produksi program.'],
            ['kode' => 'SOP-PRG-002', 'judul' => 'SOP Produksi Studio & Outside Broadcasting (OB-Van)', 'kategori' => 'Produksi', 'suara_siaran' => 'Multi-Mic & Mixer Studio (EBU R128)', 'revisi' => 'Rev. 04 / 2024', 'status' => 'Aktif', 'urutan' => 2, 'keterangan' => 'Standar operasional teknis taping siaran di Master Control Room (MCR), Studio 1 & 2, serta OB-Van.'],
            ['kode' => 'SOP-PRG-003', 'judul' => 'SOP Pasca Produksi, Editing & Penyelarasan Audio/Video', 'kategori' => 'Pasca Produksi', 'suara_siaran' => 'Audio Loudness -23 LUFS / Stereo 48kHz', 'revisi' => 'Rev. 02 / 2024', 'status' => 'Aktif', 'urutan' => 3, 'keterangan' => 'Ketentuan non-linear editing (NLE), color grading, graphic overlay, dan standardisasi level audio EBU R128.'],
            ['kode' => 'SOP-PRG-004', 'judul' => 'SOP Quality Control (QC) Master Siaran On-Air', 'kategori' => 'Quality Control', 'suara_siaran' => 'Peak Level Limiting & QC Audio Meter', 'revisi' => 'Rev. 03 / 2025', 'status' => 'Aktif', 'urutan' => 4, 'keterangan' => 'Verifikasi kelayakan teknis file master siaran, sensor isi siaran (P3SPS KPID), serta aspek legalitas.'],
            ['kode' => 'SOP-PRG-005', 'judul' => 'SOP Ingest Server, Otomasi Play-out & Master Control', 'kategori' => 'Penayangan', 'suara_siaran' => 'Audio Playout Server Channel 1 & 2', 'revisi' => 'Rev. 03 / 2025', 'status' => 'Aktif', 'urutan' => 5, 'keterangan' => 'Prosedur transfer materi siaran digital ke Video Server Transmisi dan penjadwalan playlist harian.'],
            ['kode' => 'SOP-PRG-006', 'judul' => 'SOP Penayangan Live Relay & Breaking News Terjadwal', 'kategori' => 'Penayangan', 'suara_siaran' => 'Audio Relay Line & Emergency Overdrive', 'revisi' => 'Rev. 02 / 2024', 'status' => 'Aktif', 'urutan' => 6, 'keterangan' => 'Protokol pengalihan siaran pusat TVRI Nasional ke siaran lokal Ambon atau liputan siaga kebencanaan.'],
            ['kode' => 'SOP-PRG-007', 'judul' => 'SOP Monitoring On-Air, Logger Rekaman & As-Run Log', 'kategori' => 'Monitoring', 'suara_siaran' => 'Dual-Track Logger Rec 24/7', 'revisi' => 'Rev. 02 / 2025', 'status' => 'Aktif', 'urutan' => 7, 'keterangan' => 'Tata cara pencatatan jam tayang aktual, penyimpanan bukti tayang iklan/sponsor, serta pelaporan KPID.'],
            ['kode' => 'SOP-PRG-008', 'judul' => 'SOP Evaluasi Kepemirsaan, Rating & Kepatuhan Jadwal', 'kategori' => 'Evaluasi', 'suara_siaran' => 'Audio Clarity & Broadcast Monitoring', 'revisi' => 'Rev. 01 / 2025', 'status' => 'Aktif', 'urutan' => 8, 'keterangan' => 'Evaluasi mingguan dan bulanan capaian pemirsa, kepatuhan rundown tayang, serta telaah feedback audiens.'],
        ];

        foreach ($defaultSops as $sop) {
            TvriProgramSop::create($sop);
        }
    }

    /**
     * Reset / Muat Ulang Data Standar (51 Program & Parameter Resmi)
     */
    public function reseedDefaults()
    {
        $seeder = new \Database\Seeders\TvriDataSeeder();
        $seeder->run();

        if (Schema::hasTable('tvri_program_sops') && TvriProgramSop::count() === 0) {
            $this->seedDefaultSops();
        }

        return redirect()->route('tvri.program.manage', ['tab' => 'program'])->with('success', 'Data standar 51 Program TVRI Maluku berhasil dimuat ulang ke sistem.');
    }
}
