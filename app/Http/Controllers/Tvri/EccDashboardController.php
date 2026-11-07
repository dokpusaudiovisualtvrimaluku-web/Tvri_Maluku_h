<?php

namespace App\Http\Controllers\Tvri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EccDashboardController extends Controller
{
    public function index(Request $request)
    {
        // ===== TATA USAHA =====
        $ringkasanTu = \App\Models\TvriTuRingkasan::first();
        $dbSuratMasuk = DB::table('tvri_surat_masuks')->count();
        $suratMasuk = $dbSuratMasuk > 0 ? $dbSuratMasuk : ($ringkasanTu ? (int) $ringkasanTu->surat_masuk : 128);
        $suratMasukSelesai = $dbSuratMasuk > 0 ? DB::table('tvri_surat_masuks')->where('status', 'Selesai')->count() : ($ringkasanTu ? (int) $ringkasanTu->disposisi_selesai : 78);
        $dbSuratKeluar = DB::table('tvri_surat_keluars')->count();
        $suratKeluar = $dbSuratKeluar > 0 ? $dbSuratKeluar : ($ringkasanTu ? (int) $ringkasanTu->surat_keluar : 96);
        $dbLayananAdm = (int) DB::table('tvri_layanan_adms')->sum('jumlah');
        $layananAdm = $dbLayananAdm > 0 ? $dbLayananAdm : ($ringkasanTu ? (int) $ringkasanTu->layanan_adm : 86);
        $agendaHariIni = DB::table('tvri_agenda_kegiatans')->whereDate('tanggal', today())->count();
        if ($agendaHariIni === 0) {
            $agendaHariIni = DB::table('tvri_agenda_kegiatans')->count() ?: ($ringkasanTu ? (int) $ringkasanTu->agenda_kegiatan : 24);
        }
        $disposisiBerjalan = DB::table('tvri_surat_masuks')->whereIn('status', ['Baru', 'Didisposisikan', 'Diproses'])->count();
        if ($disposisiBerjalan === 0 && $ringkasanTu) {
            $disposisiBerjalan = (int) ($ringkasanTu->disposisi_proses ?? 18);
        }

        // ===== UMUM & LOGISTIK =====
        $ringkasanUmum = \App\Models\TvriUmumRingkasan::first();
        $dbTotalAset = (int) DB::table('tvri_asets')->sum('jumlah');
        $totalAset = $dbTotalAset > 0 ? $dbTotalAset : ($ringkasanUmum ? (int) $ringkasanUmum->total_aset : 342);
        $dbAsetBaik = (int) DB::table('tvri_asets')->where('kondisi', 'Baik')->sum('jumlah');
        $asetBaik = $dbAsetBaik > 0 ? $dbAsetBaik : ($ringkasanUmum ? (int) $ringkasanUmum->aset_baik : 298);
        $dbKendaraanTotal = DB::table('tvri_kendaraans')->count();
        $kendaraanTotal = $dbKendaraanTotal > 0 ? $dbKendaraanTotal : ($ringkasanUmum ? (int) $ringkasanUmum->kendaraan_total : 28);
        $dbKendaraanSiap = DB::table('tvri_kendaraans')->where('status', 'Siap Operasi')->count();
        $kendaraanSiap = $dbKendaraanSiap > 0 ? $dbKendaraanSiap : ($ringkasanUmum ? (int) $ringkasanUmum->kendaraan_siap : 24);
        $dbPermintaanTotal = DB::table('tvri_permintaan_layanans')->count();
        $permintaanTotal = $dbPermintaanTotal > 0 ? $dbPermintaanTotal : ($ringkasanUmum ? (int) $ringkasanUmum->permintaan_total : 42);
        $dbPermintaanSelesai = DB::table('tvri_permintaan_layanans')->where('status', 'Selesai')->count();
        $permintaanSelesai = $dbPermintaanSelesai > 0 ? $dbPermintaanSelesai : ($ringkasanUmum ? (int) $ringkasanUmum->permintaan_selesai : 38);

        // ===== TRANSMISI =====
        $ringkasanTransmisi = \App\Models\TvriTransmisiRingkasan::first();
        $dbSiteTotal = DB::table('tvri_stasiun_pemancars')->count();
        $siteTotal = $dbSiteTotal > 0 ? $dbSiteTotal : ($ringkasanTransmisi ? (int) $ringkasanTransmisi->total_site : 28);
        $dbSiteOnAir = DB::table('tvri_stasiun_pemancars')->where('status', 'On-Air')->count();
        $siteOnAir = $dbSiteTotal > 0 ? $dbSiteOnAir : ($ringkasanTransmisi ? round($ringkasanTransmisi->total_site * $ringkasanTransmisi->on_air_pct / 100) : 28);
        $siteGangguan = DB::table('tvri_stasiun_pemancars')->whereIn('status', ['Gangguan', 'Maintenance'])->count();
        $kualitasRata = DB::table('tvri_stasiun_pemancars')->avg('kualitas') ?? ($ringkasanTransmisi ? (float) $ringkasanTransmisi->kualitas_rata : 4.6);
        $gangguanAktif = DB::table('tvri_gangguans')->where('status', 'Proses')->count();
        $pctTransmisi = $siteTotal > 0 ? round(($siteOnAir / $siteTotal) * 100) : ($ringkasanTransmisi ? $ringkasanTransmisi->on_air_pct : 100);

        // Dokumen & SOP Transmisi (Terintegrasi Pangkalan Dokumen)
        $transmisiSops = \App\Models\TvriTransmisiSop::orderBy('urutan')->get();
        if (class_exists(\App\Models\SimandatDocument::class)) {
            try {
                $dmsTx = \App\Models\SimandatDocument::where(function ($q) {
                    $q->where('division', 'Teknik Transmisi')
                      ->orWhere('division', 'transmisi')
                      ->orWhere('folder_id', 10)
                      ->orWhere('title', 'like', '%transmisi%')
                      ->orWhere('category', 'like', '%transmisi%');
                })->orderByDesc('id')->get();

                $existingCodes = $transmisiSops->pluck('kode')->filter()->all();
                $existingTitles = $transmisiSops->pluck('judul')->map(fn($t) => strtolower(trim($t)))->all();

                $convertedTx = $dmsTx->reject(function ($doc) use ($existingCodes, $existingTitles) {
                    $codeMatch = $doc->document_number && in_array($doc->document_number, $existingCodes);
                    $titleMatch = in_array(strtolower(trim($doc->title)), $existingTitles);
                    return $codeMatch || $titleMatch;
                })->map(function ($doc) {
                    $filePath = $doc->file_path;
                    if ($filePath && !str_starts_with($filePath, 'storage/') && !str_starts_with($filePath, 'http')) {
                        $filePath = 'storage/' . $filePath;
                    }
                    return (object) [
                        'id' => $doc->id,
                        'kode' => $doc->document_number ?: ('DOC-TX-' . $doc->id),
                        'judul' => $doc->title,
                        'kategori' => $doc->category ?: 'Dokumen Laporan Transmisi',
                        'versi' => $doc->current_version ?: 'v1.0',
                        'status' => $doc->status ?: 'Aktif',
                        'file_url' => $filePath,
                        'file_path' => $filePath,
                        'keterangan' => $doc->description ?: 'Dokumen Laporan Transmisi',
                        'urutan' => 99,
                    ];
                });

                $transmisiSops = $transmisiSops->concat($convertedTx);
            } catch (\Throwable $e) {}
        }
        $totalDokumenTransmisi = $transmisiSops->count();

        // ===== TEKNIK STUDIO =====
        $ringkasanTeknik = \App\Models\TvriTeknikRingkasan::first();
        if (!$ringkasanTeknik || \App\Models\TvriTeknikJadwal::count() === 0) {
            try {
                (new \Database\Seeders\TvriTeknikDataSeeder())->run();
                $ringkasanTeknik = \App\Models\TvriTeknikRingkasan::first();
            } catch (\Throwable $e) {}
        }
        $txTotal = \App\Models\TvriTeknikTransmitter::count();
        $txBaik = \App\Models\TvriTeknikTransmitter::where('status', 'Operasional')->count();
        $alatTotal = $txTotal > 0 ? (62 + $txTotal) : 78;
        $alatBaik = $txTotal > 0 ? (56 + $txBaik) : 71;
        $insidenHariIni = \App\Models\TvriTeknikInsiden::whereIn('status', ['Open', 'Investigasi', 'Proses'])->count() ?: ($ringkasanTeknik ? (int) $ringkasanTeknik->insiden_hari_ini : 1);
        $pemeliharaanProses = \App\Models\TvriTeknikMaintenance::whereIn('status', ['Proses', 'Terjadwal'])->count() ?: 1;
        $skorTeknik = $ringkasanTeknik ? (int) $ringkasanTeknik->readiness_overall : round(($alatBaik / max(1, $alatTotal)) * 100);

        // ===== KEUANGAN =====
        $currentYear = (int) date('Y');
        $ringkasanKeuangan = \App\Models\TvriKeuanganRingkasan::where('tahun', $currentYear)->first()
            ?? \App\Models\TvriKeuanganRingkasan::latest('tahun')->first();
        $dbPagu = (float) DB::table('tvri_anggarans')->sum('pagu');
        $pagu = $dbPagu > 0 ? $dbPagu : ($ringkasanKeuangan ? (float) $ringkasanKeuangan->pagu_anggaran : 45750000000);
        $dbRealisasi = (float) DB::table('tvri_anggarans')->sum('realisasi');
        $realisasiAnggaran = $dbRealisasi > 0 ? $dbRealisasi : ($ringkasanKeuangan ? (float) $ringkasanKeuangan->realisasi_anggaran : 34356500000);
        $sisaAnggaran = max(0, $pagu - $realisasiAnggaran);
        $pctAnggaran = $pagu > 0 ? round(($realisasiAnggaran / $pagu) * 100, 1) : 75.1;
        $dbPiutang = (float) DB::table('tvri_piutangs')->sum('nilai');
        $piutangTotal = $dbPiutang > 0 ? $dbPiutang : ($ringkasanKeuangan ? (float) $ringkasanKeuangan->piutang_tagihan : 425000000);
        $piutangBelumBayar = DB::table('tvri_piutangs')->where('status', 'Belum Bayar')->count();
        $ikpaSkor = $ringkasanKeuangan ? (float) $ringkasanKeuangan->ikpa_skor : 92.13;

        // ===== PENGEMBANGAN USAHA (PNBP) =====
        $ringkasanUsaha = \App\Models\TvriUsahaRingkasan::first();
        $dbPnbpTarget = (float) DB::table('tvri_pnbps')->sum('target');
        $pnbpTarget = $dbPnbpTarget > 0 ? $dbPnbpTarget : ($ringkasanUsaha ? (float) $ringkasanUsaha->target_pendapatan : 3400000000);
        $dbPnbpRealisasi = (float) DB::table('tvri_pnbps')->sum('realisasi');
        $pnbpRealisasi = $dbPnbpRealisasi > 0 ? $dbPnbpRealisasi : ($ringkasanUsaha ? (float) $ringkasanUsaha->total_pendapatan : 2850000000);
        $pctPnbp = $pnbpTarget > 0 ? round(($pnbpRealisasi / $pnbpTarget) * 100, 1) : 83.8;
        $dbMitraAktif = DB::table('tvri_mitras')->where('status', 'Aktif')->count();
        $mitraAktif = $dbMitraAktif > 0 ? $dbMitraAktif : ($ringkasanUsaha ? (int) $ringkasanUsaha->jumlah_mitra : 5);
        $dbKegiatanUsaha = DB::table('tvri_kegiatan_usahas')->count();
        $kegiatanUsaha = $dbKegiatanUsaha > 0 ? $dbKegiatanUsaha : ($ringkasanUsaha ? (int) $ringkasanUsaha->kegiatan_usaha : 5);
        $indeksKinerjaUsaha = $ringkasanUsaha ? (int) $ringkasanUsaha->indeks_kinerja : 82;

        // ===== PROGRAM & SIARAN =====
        $progRingkasan = Schema::hasTable('tvri_program_ringkasans') ? DB::table('tvri_program_ringkasans')->latest()->first() : null;
        $dbProgramTotal = DB::table('tvri_programs')->count();
        $programTotal = $dbProgramTotal > 0 ? $dbProgramTotal : ($progRingkasan->total_direncanakan ?? 51);
        $dbProgramTayang = DB::table('tvri_programs')->whereIn('status', ['Tayang', 'On Schedule', 'Selesai'])->count();
        $programTayang = $dbProgramTotal > 0 ? $dbProgramTayang : ($progRingkasan->tayang ?? 48);
        $programProduksi = DB::table('tvri_programs')->whereIn('status', ['Dalam Produksi', 'Produksi', 'Pra Produksi', 'Pasca Produksi', 'Editing'])->count() ?: ($progRingkasan->dalam_produksi ?? 18);
        $programTerlambat = DB::table('tvri_programs')->where('status', 'Terlambat')->count();
        $pctProgram = $programTotal > 0 ? round(($programTayang / $programTotal) * 100) : ($progRingkasan->schedule_compliance ?? 94);

        // ===== BERITA =====
        $beritaRencana = DB::table('tvri_beritas')->count() ?: 120;
        $beritaSelesai = DB::table('tvri_beritas')->whereIn('status', ['Selesai', 'On Air'])->count() ?: 60;
        $beritaTayang = DB::table('tvri_beritas')->whereNotNull('tanggal_tayang')->count() ?: 45;
        $beritaTerkendala = DB::table('tvri_beritas')->where('status', 'Terkendala')->count();
        $beritaAgendaCount = DB::table('tvri_berita_agenda_liputans')->count();
        $beritaProduksiCount = Schema::hasTable('tvri_berita_produksis') ? DB::table('tvri_berita_produksis')->count() : 6;
        $beritaMonitoringCount = Schema::hasTable('tvri_berita_monitorings') ? DB::table('tvri_berita_monitorings')->count() : 6;
        $beritaDokumentasiCount = Schema::hasTable('tvri_berita_dokumentasis') ? DB::table('tvri_berita_dokumentasis')->count() : 6;
        $pctBerita = $beritaRencana > 0 ? min(100, round(($beritaSelesai / $beritaRencana) * 100)) : 85;

        // ===== KONTEN MEDIA BARU =====
        $ringkasanKmb = \App\Models\TvriKmbRingkasan::first();
        $kontenTotal = DB::table('tvri_kontens')->count() ?: ($ringkasanKmb ? (int) $ringkasanKmb->total_konten : 256);
        $dbKontenViews = (int) DB::table('tvri_kontens')->sum('views');
        $kontenViews = $dbKontenViews > 0 ? $dbKontenViews : ($ringkasanKmb ? (int) $ringkasanKmb->total_views : 1200000);
        $dbKontenReach = (int) DB::table('tvri_kontens')->sum('reach');
        $kontenReach = $dbKontenReach > 0 ? $dbKontenReach : ($ringkasanKmb ? (int) $ringkasanKmb->total_reach : 980000);
        $dbKontenEng = (int) DB::table('tvri_kontens')->sum('engagement');
        $kontenEngagement = $dbKontenEng > 0 ? $dbKontenEng : ($ringkasanKmb ? (int) $ringkasanKmb->engagement : 84000);

        // ===== PROMO =====
        $ringkasanPromo = Schema::hasTable('tvri_promo_ringkasans') ? \App\Models\TvriPromoRingkasan::first() : null;
        $kampanyeTotal = DB::table('tvri_kampanyes')->count() ?: ($ringkasanPromo ? $ringkasanPromo->total_kampanye : 24);
        $materiTotal = DB::table('tvri_materi_promos')->count() ?: ($ringkasanPromo ? $ringkasanPromo->materi_diproduksi : 56);
        $materiSelesai = DB::table('tvri_materi_promos')->where('status', 'Selesai')->count() ?: 51;
        $kampanyeReach = 1800000;
        $pctPromo = $materiTotal > 0 ? round(($materiSelesai / $materiTotal) * 100) : ($ringkasanPromo ? (int) $ringkasanPromo->tingkat_capaian : 92);
        $reachPromoLabel = $ringkasanPromo ? $ringkasanPromo->reach_total : '1,8 Jt';

        $pct = fn ($a, $b) => $b > 0 ? min(100, round($a / $b * 100)) : 0;

        // ===== SKOR KINERJA PER UNIT KERJA (REAL TIME) =====
        $units = [
            ['key' => 'tu', 'nama' => 'Tata Usaha', 'ikon' => 'clipboard', 'warna' => 'blue',
             'skor' => round(($suratMasukSelesai + $permintaanSelesai) / max(1, $suratMasuk + $permintaanTotal) * 100),
             'metrics' => [
                 ['label' => 'Surat Masuk', 'value' => number_format($suratMasuk)],
                 ['label' => 'Disposisi Selesai', 'value' => $suratMasukSelesai . ' / ' . $suratMasuk],
                 ['label' => 'Service Rate', 'value' => round(($suratMasukSelesai / max(1, $suratMasuk)) * 100) . '%']
             ]],
            ['key' => 'umum', 'nama' => 'Umum', 'ikon' => 'building', 'warna' => 'emerald',
             'skor' => round(($asetBaik + $kendaraanSiap) / max(1, $totalAset + $kendaraanTotal) * 100),
             'metrics' => [
                 ['label' => 'Aset Siap Pakai', 'value' => number_format($asetBaik) . ' / ' . number_format($totalAset)],
                 ['label' => 'Kendaraan Siap', 'value' => $kendaraanSiap . ' / ' . $kendaraanTotal],
                 ['label' => 'Layanan Selesai', 'value' => $permintaanSelesai . ' / ' . $permintaanTotal]
             ]],
            ['key' => 'transmisi', 'nama' => 'Transmisi', 'ikon' => 'broadcast', 'warna' => 'cyan',
             'skor' => $pctTransmisi,
             'metrics' => [
                 ['label' => 'Site On-Air', 'value' => $siteOnAir . ' / ' . $siteTotal],
                 ['label' => 'Kualitas Rata-rata', 'value' => number_format((float) $kualitasRata, 1) . ' / 5 (' . ($ringkasanTransmisi->kualitas_diff ?? '+0.2') . ')'],
                 ['label' => 'Gangguan Aktif', 'value' => $gangguanAktif . ' (' . ($ringkasanTransmisi->gangguan_diff ?? '-60%') . ')']
             ]],
            ['key' => 'teknik', 'nama' => 'Teknik', 'ikon' => 'wrench', 'warna' => 'amber',
             'skor' => $skorTeknik,
             'metrics' => [['label' => 'Technical Readiness', 'value' => $skorTeknik . '%'], ['label' => 'Peralatan Siap', 'value' => $alatBaik . ' / ' . $alatTotal], ['label' => 'Insiden Hari Ini', 'value' => $insidenHariIni]]],
            ['key' => 'keuangan', 'nama' => 'Keuangan', 'ikon' => 'money', 'warna' => 'teal',
             'skor' => (int) round($pctAnggaran),
             'metrics' => [
                 ['label' => 'Serapan Anggaran', 'value' => number_format($pctAnggaran, 1, ',', '.') . '%'],
                 ['label' => 'Realisasi', 'value' => 'Rp ' . number_format($realisasiAnggaran / 1e9, 2, ',', '.') . ' M'],
                 ['label' => 'Sisa Anggaran', 'value' => 'Rp ' . number_format($sisaAnggaran / 1e9, 2, ',', '.') . ' M'],
                 ['label' => 'Nilai IKPA', 'value' => number_format($ikpaSkor, 2, ',', '.') . ' (Sangat Baik)']
             ]],
            ['key' => 'usaha', 'nama' => 'Pengembangan Usaha', 'ikon' => 'handshake', 'warna' => 'orange',
             'skor' => (int) round($pctPnbp),
             'metrics' => [
                 ['label' => 'Total Pendapatan', 'value' => 'Rp ' . number_format($pnbpRealisasi / 1e6, 0) . ' Jt'],
                 ['label' => 'Capaian Target', 'value' => $pctPnbp . '% (' . ($ringkasanUsaha->diff_total_pendapatan ?? '+18.6%') . ')'],
                 ['label' => 'Mitra / Klien', 'value' => $mitraAktif . ' Mitra']
             ]],
            ['key' => 'program', 'nama' => 'Program & Siaran', 'ikon' => 'tv', 'warna' => 'purple',
             'skor' => $pctProgram,
             'metrics' => [
                 ['label' => 'Program Tayang', 'value' => $programTayang . ' / ' . $programTotal],
                 ['label' => 'Schedule Compliance', 'value' => $pctProgram . '%'],
                 ['label' => 'Dalam Produksi', 'value' => $programProduksi]
             ]],
            ['key' => 'berita', 'nama' => 'Berita', 'ikon' => 'news', 'warna' => 'rose',
             'skor' => $pctBerita,
             'metrics' => [
                 ['label' => 'Berita Tayang', 'value' => $beritaTayang],
                 ['label' => 'Produksi Paket', 'value' => $beritaProduksiCount . ' Paket'],
                 ['label' => 'Monitoring Harian', 'value' => $beritaMonitoringCount . ' Siaran'],
                 ['label' => 'Dokumentasi Berita', 'value' => $beritaDokumentasiCount . ' Arsip'],
             ]],
            ['key' => 'mbn', 'nama' => 'Konten Media Baru', 'ikon' => 'mobile', 'warna' => 'pink',
             'skor' => $ringkasanKmb ? min(100, 60 + (int) $ringkasanKmb->growth_rate) : min(100, 90 + (int) round($kontenTotal / 30)),
             'metrics' => [
                 ['label' => 'Total Konten', 'value' => number_format($kontenTotal) . ' Konten'],
                 ['label' => 'Total Views', 'value' => number_format($kontenViews / 1e6, 1) . ' Jt (' . ($ringkasanKmb->diff_total_views ?? '+35%') . ')'],
                 ['label' => 'Engagement', 'value' => number_format($kontenEngagement / 1e3, 0) . ' K (' . ($ringkasanKmb->diff_engagement ?? '+38%') . ')']
             ]],
            ['key' => 'promo', 'nama' => 'Promo', 'ikon' => 'megaphone', 'warna' => 'indigo',
             'skor' => $pctPromo,
             'metrics' => [['label' => 'Tingkat Capaian', 'value' => $pctPromo . '%'], ['label' => 'Total Kampanye', 'value' => $kampanyeTotal], ['label' => 'Reach Total', 'value' => $reachPromoLabel]]],
        ];

        $performanceIndex = count($units) ? (int) round(array_sum(array_column($units, 'skor')) / count($units)) : 0;

        // ===== TOP EXECUTIVE ALERTS =====
        $alerts = [];
        foreach (DB::table('tvri_stasiun_pemancars')->where('status', 'Gangguan')->get() as $s) {
            $alerts[] = ['prioritas' => 'Kritis', 'isu' => 'Transmitter ' . $s->nama . ' berstatus gangguan', 'unit' => 'Transmisi', 'dampak' => 'Risiko siaran'];
        }
        foreach (DB::table('tvri_gangguans')->where('status', 'Proses')->get() as $g) {
            $alerts[] = ['prioritas' => 'Kritis', 'isu' => $g->site . ': ' . $g->jenis . ' belum tertangani', 'unit' => 'Teknik', 'dampak' => 'Kualitas siaran'];
        }
        if (Schema::hasTable('tvri_teknik_insidens')) {
            foreach (DB::table('tvri_teknik_insidens')->whereIn('status', ['Open', 'Investigasi'])->get() as $ins) {
                $alerts[] = ['prioritas' => 'Kritis', 'isu' => ($ins->peralatan ?? 'Peralatan') . ': ' . ($ins->deskripsi ?? 'Perlu perbaikan'), 'unit' => 'Teknik', 'dampak' => 'Kesiapan studio'];
            }
        }
        foreach (DB::table('tvri_piutangs')->where('status', 'Belum Bayar')->get() as $p) {
            $alerts[] = ['prioritas' => 'Perhatian', 'isu' => 'Piutang ' . $p->klien . ' (Rp ' . number_format($p->nilai / 1e6, 0) . ' Jt) belum tertagih', 'unit' => 'Keuangan', 'dampak' => 'Arus kas'];
        }
        if ($beritaTerkendala > 0) {
            $alerts[] = ['prioritas' => 'Perhatian', 'isu' => $beritaTerkendala . ' rencana liputan terkendala', 'unit' => 'Berita', 'dampak' => 'Jadwal tayang'];
        }
        if ($programTerlambat > 0) {
            $alerts[] = ['prioritas' => 'Perhatian', 'isu' => $programTerlambat . ' program terlambat dari jadwal produksi', 'unit' => 'Program', 'dampak' => 'Schedule compliance'];
        }
        if ($pctPnbp < 75) {
            $alerts[] = ['prioritas' => 'Kritis', 'isu' => 'Capaian PNBP ' . $pctPnbp . '% masih di bawah 75%', 'unit' => 'Usaha', 'dampak' => 'Capaian tahunan'];
        } elseif ($pctPnbp < 85) {
            $alerts[] = ['prioritas' => 'Perhatian', 'isu' => 'Capaian PNBP ' . $pctPnbp . '% on-track menuju target tahunan', 'unit' => 'Usaha', 'dampak' => 'Optimalisasi sisa triwulan'];
        }

        $criticalCount = collect($alerts)->where('prioritas', 'Kritis')->count();

        // JIKA REQUEST VIA AJAX / POLLING REAL-TIME
        if ($request->ajax() || $request->query('realtime') == 1) {
            $idrHelper = fn ($v) => 'Rp ' . number_format($v / 1e6, 1) . ' Jt';
            return response()->json([
                'performanceIndex' => $performanceIndex,
                'performanceLabel' => ($performanceIndex >= 90 ? 'Sangat Baik' : 'Baik') . ' • On Track',
                'pnbp' => [
                    'pct' => $pctPnbp,
                    'realisasiLabel' => $idrHelper($pnbpRealisasi),
                    'targetLabel' => $idrHelper($pnbpTarget),
                    'status' => $pctPnbp >= 90 ? 'ON TRACK' : 'PERLU PERHATIAN',
                ],
                'program' => [
                    'pct' => $pctProgram,
                    'tayang' => $programTayang,
                    'total' => $programTotal,
                    'status' => 'ON TRACK',
                ],
                'audiens' => [
                    'views' => number_format($kontenViews / 1e6, 1) . ' Jt',
                    'reach' => number_format($kontenReach / 1e6, 1) . ' Jt',
                    'status' => 'ON TRACK',
                ],
                'keuangan' => [
                    'pct' => $pctAnggaran,
                    'realisasiLabel' => $idrHelper($realisasiAnggaran),
                    'paguLabel' => $idrHelper($pagu),
                    'status' => $pctAnggaran >= 60 ? 'ON TRACK' : 'PERLU PERHATIAN',
                ],
                'teknik' => [
                    'readiness' => round($alatBaik / max(1, $alatTotal) * 100),
                    'status' => 'ON TRACK',
                ],
                'operasional' => [
                    'readiness' => round($asetBaik / max(1, $totalAset) * 100),
                    'status' => 'ON TRACK',
                ],
                'tataKelola' => [
                    'totalSurat' => $suratMasuk + $suratKeluar,
                    'layananAdm' => $layananAdm,
                    'status' => 'ON TRACK',
                ],
                'critical' => [
                    'count' => $criticalCount,
                    'status' => $criticalCount > 0 ? 'CRITICAL' : 'NORMAL',
                ],
                'units' => $units,
                'alerts' => $alerts,
                'siteOnAir' => $siteOnAir,
                'siteGangguan' => $siteGangguan,
                'kualitasRata' => number_format((float) $kualitasRata, 1),
                'beritaTayang' => $beritaTayang,
                'disposisiBerjalan' => $disposisiBerjalan,
                'totalDokumenTransmisi' => $totalDokumenTransmisi,
                'timestamp' => now()->isoFormat('dddd, D MMMM Y') . ' • ' . now()->format('H:i:s') . ' WIT',
            ]);
        }

        return view('tvri.ecc', compact(
            'units', 'performanceIndex', 'alerts', 'criticalCount',
            'suratMasuk', 'suratKeluar', 'layananAdm', 'agendaHariIni', 'disposisiBerjalan',
            'totalAset', 'asetBaik', 'kendaraanSiap', 'kendaraanTotal', 'permintaanTotal', 'permintaanSelesai',
            'siteTotal', 'siteOnAir', 'siteGangguan', 'kualitasRata', 'gangguanAktif', 'transmisiSops', 'totalDokumenTransmisi',
            'alatBaik', 'alatTotal', 'insidenHariIni', 'pemeliharaanProses',
            'pagu', 'realisasiAnggaran', 'pctAnggaran', 'piutangTotal', 'piutangBelumBayar',
            'pnbpTarget', 'pnbpRealisasi', 'pctPnbp', 'mitraAktif', 'kegiatanUsaha',
            'programTotal', 'programTayang', 'programTerlambat', 'pctProgram',
            'beritaRencana', 'beritaTayang', 'beritaTerkendala', 'pctBerita',
            'kontenTotal', 'kontenViews', 'kontenReach', 'kontenEngagement',
            'materiTotal', 'materiSelesai', 'kampanyeTotal', 'kampanyeReach', 'pctPromo'
        ));
    }
}
