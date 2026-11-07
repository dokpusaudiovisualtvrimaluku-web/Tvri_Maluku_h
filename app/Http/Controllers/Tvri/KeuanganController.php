<?php

namespace App\Http\Controllers\Tvri;

use App\Http\Controllers\Controller;
use App\Models\SimandatDocument;
use App\Models\SimandatDocumentFolder;
use App\Models\TvriAnggaran;
use App\Models\TvriKeuanganArusKas;
use App\Models\TvriKeuanganIkpa;
use App\Models\TvriKeuanganKegiatan;
use App\Models\TvriKeuanganRingkasan;
use App\Models\TvriPiutang;
use App\Models\TvriPnbp;
use App\Models\TvriKeuanganSop;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KeuanganController extends Controller
{
    /**
     * Tampilan Dashboard Utama Bidang Keuangan (Visual Infografis)
     */
    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->username === 'berita') {
            return redirect()->route('tvri.berita.dashboard');
        }

        $tahun = (int) $request->get('tahun', date('Y'));

        // 1. Ringkasan Keuangan
        $defaultIkpaSkor = $tahun == 2025 ? 99.00 : ($tahun == 2026 ? 99.28 : 92.13);
        $ringkasan = TvriKeuanganRingkasan::firstOrCreate(
            ['tahun' => $tahun],
            [
                'pagu_anggaran' => 45750000000,
                'realisasi_anggaran' => 34356500000,
                'target_pnbp' => 3400000000,
                'realisasi_pnbp' => 2850000000,
                'piutang_tagihan' => 425000000,
                'ikpa_skor' => $defaultIkpaSkor,
                'ikpa_predikat' => 'Sangat Baik',
                'aset_lancar' => 12500000000,
                'aset_tetap' => 68300000000,
                'aset_lainnya' => 5200000000,
                'kewajiban_lancar' => 3800000000,
                'kewajiban_panjang' => 1200000000,
                'ekuitas' => 81000000000,
            ]
        );

        // 2. Rincian Anggaran Belanja
        $anggaranRows = TvriAnggaran::where('tahun', $tahun)->orderBy('kode')->get();
        if ($anggaranRows->isEmpty()) {
            $defaultAnggaran = [
                ['kode' => '51', 'uraian' => 'Belanja Pegawai', 'pagu' => 19500000000, 'realisasi' => 16006500000, 'tahun' => $tahun],
                ['kode' => '52', 'uraian' => 'Belanja Barang', 'pagu' => 17000000000, 'realisasi' => 12114000000, 'tahun' => $tahun],
                ['kode' => '53', 'uraian' => 'Belanja Modal', 'pagu' => 8500000000, 'realisasi' => 5780000000, 'tahun' => $tahun],
                ['kode' => '54', 'uraian' => 'Belanja Lainnya', 'pagu' => 750000000, 'realisasi' => 456000000, 'tahun' => $tahun],
            ];
            foreach ($defaultAnggaran as $item) {
                TvriAnggaran::create($item);
            }
            $anggaranRows = TvriAnggaran::where('tahun', $tahun)->orderBy('kode')->get();
        }

        // Kalkulasi dinamis Pagu & Realisasi dari ringkasan (atau fallback ke rincian)
        $sumPagu = (float) $anggaranRows->sum('pagu');
        $sumRealisasi = (float) $anggaranRows->sum('realisasi');
        $paguAnggaran = (float) $ringkasan->pagu_anggaran > 0 ? (float) $ringkasan->pagu_anggaran : ($sumPagu > 0 ? $sumPagu : 45750000000);
        $realisasiAnggaran = (float) $ringkasan->realisasi_anggaran > 0 ? (float) $ringkasan->realisasi_anggaran : ($sumRealisasi > 0 ? $sumRealisasi : 34356500000);
        $sisaAnggaran = max(0, $paguAnggaran - $realisasiAnggaran);
        $persenRealisasiAnggaran = $paguAnggaran > 0 ? round(($realisasiAnggaran / $paguAnggaran) * 100, 2) : 0;
        $persenSisaAnggaran = $paguAnggaran > 0 ? round(($sisaAnggaran / $paguAnggaran) * 100, 2) : 0;

        $colorList = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'];
        $rincianAnggaran = [];
        foreach ($anggaranRows as $idx => $row) {
            $pagu = (float) $row->pagu;
            $real = (float) $row->realisasi;
            $persen = $pagu > 0 ? round(($real / $pagu) * 100, 2) : 0;
            $proporsi = $realisasiAnggaran > 0 ? round(($real / $realisasiAnggaran) * 100, 1) : 0;
            $rincianAnggaran[] = [
                'id' => $row->id,
                'kode' => $row->kode,
                'uraian' => $row->uraian,
                'pagu' => $pagu,
                'realisasi' => $real,
                'persen' => $persen,
                'proporsi' => $proporsi,
                'color' => $colorList[$idx % count($colorList)],
            ];
        }

        // 3. Rincian PNBP
        $pnbpRows = TvriPnbp::where('tahun', $tahun)->get();
        if ($pnbpRows->isEmpty()) {
            $defaultPnbp = [
                ['jenis' => 'Infotorial', 'target' => 5000000, 'realisasi' => 400000000, 'tahun' => $tahun],
            ];
            foreach ($defaultPnbp as $item) {
                TvriPnbp::create($item);
            }
            $pnbpRows = TvriPnbp::where('tahun', $tahun)->get();
        }

        $sumTargetPnbp = (float) $pnbpRows->sum('target');
        $sumRealPnbp = (float) $pnbpRows->sum('realisasi');
        $targetPnbp = (float) $ringkasan->target_pnbp > 0 ? (float) $ringkasan->target_pnbp : ($sumTargetPnbp > 0 ? $sumTargetPnbp : 5000000);
        $realisasiPnbp = (float) $ringkasan->realisasi_pnbp > 0 ? (float) $ringkasan->realisasi_pnbp : ($sumRealPnbp > 0 ? $sumRealPnbp : 400000000);
        $persenRealisasiPnbp = $targetPnbp > 0 ? round(($realisasiPnbp / $targetPnbp) * 100, 2) : 0;

        $rincianPnbp = [];
        foreach ($pnbpRows as $idx => $p) {
            $tgt = (float) $p->target;
            $rl = (float) $p->realisasi;
            $persen = $tgt > 0 ? round(($rl / $tgt) * 100, 2) : 0;
            $proporsi = $realisasiPnbp > 0 ? round(($rl / $realisasiPnbp) * 100, 1) : 0;
            $rincianPnbp[] = [
                'id' => $p->id,
                'jenis' => $p->jenis,
                'target' => $tgt,
                'realisasi' => $rl,
                'persen' => $persen,
                'proporsi' => $proporsi,
                'color' => $colorList[$idx % count($colorList)],
            ];
        }

        // 4. Piutang / Tagihan
        $piutangRows = TvriPiutang::orderBy('jatuh_tempo')->get();
        if ($piutangRows->isEmpty()) {
            $defaultPiutang = [
                ['klien' => 'Pemda Maluku', 'nilai' => 150000000, 'jatuh_tempo' => '2025-11-25', 'status' => 'Belum Bayar'],
                ['klien' => 'Telkom Indonesia', 'nilai' => 100000000, 'jatuh_tempo' => '2025-11-30', 'status' => 'Proses'],
                ['klien' => 'Bank Maluku Malut', 'nilai' => 75000000, 'jatuh_tempo' => '2025-12-05', 'status' => 'Belum Bayar'],
                ['klien' => 'Sponsorship Event', 'nilai' => 100000000, 'jatuh_tempo' => '2025-12-10', 'status' => 'Proses'],
            ];
            foreach ($defaultPiutang as $piu) {
                TvriPiutang::create($piu);
            }
            $piutangRows = TvriPiutang::orderBy('jatuh_tempo')->get();
        }

        $sumPiutang = (float) $piutangRows->sum('nilai');
        $piutangTagihan = (float) $ringkasan->piutang_tagihan > 0 ? (float) $ringkasan->piutang_tagihan : ($sumPiutang > 0 ? $sumPiutang : 425000000);

        // Inisialisasi awal ke model ringkasan jika field ringkasan masih kosong
        $syncChanged = false;
        if ((empty($ringkasan->pagu_anggaran) || $ringkasan->pagu_anggaran == 0) && $sumPagu > 0) {
            $ringkasan->pagu_anggaran = $sumPagu;
            $ringkasan->realisasi_anggaran = $sumRealisasi;
            $syncChanged = true;
        }
        if ((empty($ringkasan->target_pnbp) || $ringkasan->target_pnbp == 0) && $sumTargetPnbp > 0) {
            $ringkasan->target_pnbp = $sumTargetPnbp;
            $ringkasan->realisasi_pnbp = $sumRealPnbp;
            $syncChanged = true;
        }
        if ((empty($ringkasan->piutang_tagihan) || $ringkasan->piutang_tagihan == 0) && $sumPiutang > 0) {
            $ringkasan->piutang_tagihan = $sumPiutang;
            $syncChanged = true;
        }
        if ($syncChanged) {
            $ringkasan->save();
        }
        $piutangList = [];
        foreach ($piutangRows as $piu) {
            $statusLower = strtolower($piu->status ?? '');
            $badge = str_contains($statusLower, 'belum') ? 'danger' : (str_contains($statusLower, 'lunas') ? 'success' : 'warning');
            $jt = $piu->jatuh_tempo ? (is_string($piu->jatuh_tempo) ? date('d M Y', strtotime($piu->jatuh_tempo)) : $piu->jatuh_tempo->format('d M Y')) : '-';
            $piutangList[] = [
                'id' => $piu->id,
                'klien' => $piu->klien,
                'nilai' => (float) $piu->nilai,
                'jatuh_tempo' => $jt,
                'status' => $piu->status,
                'badge' => $badge,
            ];
        }

        // 5. Kinerja IKPA
        $ikpaRows = self::getOrSeedIkpa($tahun);
        $ikpaSkor = (float) $ringkasan->ikpa_skor;
        if ($ikpaSkor <= 0 && $ikpaRows->isNotEmpty()) {
            $sumIkpa = 0;
            foreach ($ikpaRows as $ikRow) {
                $sumIkpa += (float) str_replace(['%', ','], ['', '.'], $ikRow->skor);
            }
            $ikpaSkor = $sumIkpa > 0 ? round($sumIkpa, 2) : ($tahun == 2025 ? 99.00 : ($tahun == 2026 ? 99.28 : 92.13));
        }
        $ikpaPredikat = $ringkasan->ikpa_predikat ?? ($ikpaSkor >= 90 ? 'Sangat Baik' : 'Baik');

        $ikpaIndicators = [];
        foreach ($ikpaRows as $ik) {
            $ikpaIndicators[] = [
                'id' => $ik->id,
                'indikator' => $ik->indikator,
                'nilai' => $ik->nilai,
                'bobot' => $ik->bobot,
                'skor' => $ik->skor,
            ];
        }

        // 6. Arus Kas (Cash Flow)
        $cashRows = TvriKeuanganArusKas::where('tahun', $tahun)->orderBy('urutan')->get();
        if ($cashRows->isEmpty()) {
            $defaultCash = [
                ['bulan' => 'Jan', 'urutan' => 1, 'penerimaan' => 3200000000, 'pengeluaran' => 2800000000, 'saldo' => 400000000, 'tahun' => $tahun],
                ['bulan' => 'Feb', 'urutan' => 2, 'penerimaan' => 3500000000, 'pengeluaran' => 3000000000, 'saldo' => 500000000, 'tahun' => $tahun],
                ['bulan' => 'Mar', 'urutan' => 3, 'penerimaan' => 4100000000, 'pengeluaran' => 3600000000, 'saldo' => 500000000, 'tahun' => $tahun],
                ['bulan' => 'Apr', 'urutan' => 4, 'penerimaan' => 3800000000, 'pengeluaran' => 3400000000, 'saldo' => 400000000, 'tahun' => $tahun],
                ['bulan' => 'Mei', 'urutan' => 5, 'penerimaan' => 4500000000, 'pengeluaran' => 3900000000, 'saldo' => 600000000, 'tahun' => $tahun],
                ['bulan' => 'Jun', 'urutan' => 6, 'penerimaan' => 5200000000, 'pengeluaran' => 4500000000, 'saldo' => 700000000, 'tahun' => $tahun],
                ['bulan' => 'Jul', 'urutan' => 7, 'penerimaan' => 4800000000, 'pengeluaran' => 4200000000, 'saldo' => 600000000, 'tahun' => $tahun],
                ['bulan' => 'Agt', 'urutan' => 8, 'penerimaan' => 5000000000, 'pengeluaran' => 4300000000, 'saldo' => 700000000, 'tahun' => $tahun],
                ['bulan' => 'Sep', 'urutan' => 9, 'penerimaan' => 4600000000, 'pengeluaran' => 4000000000, 'saldo' => 600000000, 'tahun' => $tahun],
                ['bulan' => 'Okt', 'urutan' => 10, 'penerimaan' => 5500000000, 'pengeluaran' => 4800000000, 'saldo' => 700000000, 'tahun' => $tahun],
                ['bulan' => 'Nov', 'urutan' => 11, 'penerimaan' => 4900000000, 'pengeluaran' => 4200000000, 'saldo' => 700000000, 'tahun' => $tahun],
                ['bulan' => 'Des', 'urutan' => 12, 'penerimaan' => 5800000000, 'pengeluaran' => 5000000000, 'saldo' => 800000000, 'tahun' => $tahun],
            ];
            foreach ($defaultCash as $c) {
                TvriKeuanganArusKas::create($c);
            }
            $cashRows = TvriKeuanganArusKas::where('tahun', $tahun)->orderBy('urutan')->get();
        }

        $cashFlow = [];
        foreach ($cashRows as $cr) {
            $cashFlow[] = [
                'bulan' => $cr->bulan,
                'masuk' => round(((float) $cr->penerimaan) / 1e9, 2),
                'keluar' => round(((float) $cr->pengeluaran) / 1e9, 2),
                'saldo' => round(((float) $cr->saldo) / 1e9, 2),
            ];
        }

        // 7. Posisi Aset & Kewajiban (dalam miliar format text)
        $posisiAset = [
            'lancar' => number_format(((float) $ringkasan->aset_lancar) / 1e9, 2, ',', '.'),
            'tetap' => number_format(((float) $ringkasan->aset_tetap) / 1e9, 2, ',', '.'),
            'lainnya' => number_format(((float) $ringkasan->aset_lainnya) / 1e9, 2, ',', '.'),
            'total' => number_format((((float) $ringkasan->aset_lancar) + ((float) $ringkasan->aset_tetap) + ((float) $ringkasan->aset_lainnya)) / 1e9, 2, ',', '.'),
        ];
        $kewajibanEkuitas = [
            'kewajiban_lancar' => number_format(((float) $ringkasan->kewajiban_lancar) / 1e9, 2, ',', '.'),
            'kewajiban_panjang' => number_format(((float) $ringkasan->kewajiban_panjang) / 1e9, 2, ',', '.'),
            'ekuitas' => number_format(((float) $ringkasan->ekuitas) / 1e9, 2, ',', '.'),
            'total_pasiva' => number_format((((float) $ringkasan->kewajiban_lancar) + ((float) $ringkasan->kewajiban_panjang) + ((float) $ringkasan->ekuitas)) / 1e9, 2, ',', '.'),
        ];

        // 8. Rencana Kegiatan Keuangan
        $kegiatanRows = TvriKeuanganKegiatan::where('tahun', $tahun)->orderBy('no_urut')->get();
        if ($kegiatanRows->isEmpty()) {
            $defaultKegiatan = [
                ['no_urut' => 1, 'kegiatan' => 'Rekonsiliasi BMN', 'target' => 'Nov ' . $tahun, 'realisasi' => 'Dalam Proses', 'status' => 'On Track', 'badge' => 'success', 'tahun' => $tahun],
                ['no_urut' => 2, 'kegiatan' => 'Penyusunan LK Semester II', 'target' => 'Des ' . $tahun, 'realisasi' => '-', 'status' => 'Belum Mulai', 'badge' => 'neutral', 'tahun' => $tahun],
                ['no_urut' => 3, 'kegiatan' => 'Review Tarif PNBP', 'target' => 'Des ' . $tahun, 'realisasi' => 'Draft', 'status' => 'On Track', 'badge' => 'success', 'tahun' => $tahun],
                ['no_urut' => 4, 'kegiatan' => 'Peningkatan IKPA', 'target' => 'Des ' . $tahun, 'realisasi' => '85%', 'status' => 'On Track', 'badge' => 'success', 'tahun' => $tahun],
                ['no_urut' => 5, 'kegiatan' => 'Digitalisasi Pembayaran', 'target' => 'Des ' . $tahun, 'realisasi' => 'Implementasi', 'status' => 'On Track', 'badge' => 'success', 'tahun' => $tahun],
            ];
            foreach ($defaultKegiatan as $k) {
                TvriKeuanganKegiatan::create($k);
            }
            $kegiatanRows = TvriKeuanganKegiatan::where('tahun', $tahun)->orderBy('no_urut')->get();
        }

        $rencanaKegiatan = [];
        foreach ($kegiatanRows as $kr) {
            $rencanaKegiatan[] = [
                'id' => $kr->id,
                'no' => $kr->no_urut,
                'kegiatan' => $kr->kegiatan,
                'target' => $kr->target,
                'realisasi' => $kr->realisasi,
                'status' => $kr->status,
                'badge' => $kr->badge,
            ];
        }

        // Pastikan dokumen keuangan sudah tersinkronisasi di Pangkalan Dokumen
        if (SimandatDocument::where('folder_id', 6)->count() < 5) {
            self::syncFinancialDocuments($tahun);
        }

        // 9. Dokumen Keuangan yang Terhubung ke Pangkalan Dokumen (DMS)
        $dokumenKeuangan = SimandatDocument::where(function ($q) {
            $q->where('folder_id', 6)
              ->orWhere('category', 'RKA & DPA Perencanaan')
              ->orWhere('tags', 'like', '%keuangan%')
              ->orWhere('tags', 'like', '%dipa%')
              ->orWhere('tags', 'like', '%spj%')
              ->orWhere('tags', 'like', '%lra%')
              ->orWhere('tags', 'like', '%pnbp%')
              ->orWhere('title', 'like', '%Anggaran%')
              ->orWhere('title', 'like', '%DPA%')
              ->orWhere('title', 'like', '%DIPA%')
              ->orWhere('title', 'like', '%LRA%')
              ->orWhere('title', 'like', '%Keuangan%');
        })
        ->orderByDesc('created_at')
        ->take(6)
        ->get();

        // 10. Data Dinamis Trend Realisasi Kumulatif (Line Chart)
        $paguM = round($paguAnggaran / 1e9, 2);
        $realAngM = round($realisasiAnggaran / 1e9, 2);
        $realPnbpM = round($realisasiPnbp / 1e9, 2);

        $trendPaguKumulatif = [];
        for ($m = 1; $m <= 12; $m++) {
            $trendPaguKumulatif[] = round(($paguM / 12) * $m, 2);
        }

        $anggaranWeights = [0.07, 0.15, 0.25, 0.35, 0.45, 0.56, 0.66, 0.77, 0.85, 0.92, 1.0];
        $trendAnggaranKumulatif = [];
        foreach ($anggaranWeights as $w) {
            $trendAnggaranKumulatif[] = round($realAngM * $w, 2);
        }
        $trendAnggaranKumulatif[] = null;

        $pnbpWeights = [0.07, 0.16, 0.25, 0.37, 0.47, 0.60, 0.70, 0.79, 0.88, 0.95, 1.0];
        $trendPnbpKumulatif = [];
        foreach ($pnbpWeights as $w) {
            $trendPnbpKumulatif[] = round($realPnbpM * $w, 2);
        }
        $trendPnbpKumulatif[] = null;

        $sops = self::getOrSeedSops();

        return view('tvri.keuangan.dashboard', compact(
            'paguAnggaran', 'realisasiAnggaran', 'sisaAnggaran', 'persenRealisasiAnggaran', 'persenSisaAnggaran',
            'targetPnbp', 'realisasiPnbp', 'persenRealisasiPnbp', 'piutangTagihan',
            'rincianAnggaran', 'rincianPnbp', 'piutangList', 'ikpaSkor', 'ikpaPredikat', 'ikpaIndicators',
            'cashFlow', 'posisiAset', 'kewajibanEkuitas', 'rencanaKegiatan', 'dokumenKeuangan', 'tahun',
            'trendPaguKumulatif', 'trendAnggaranKumulatif', 'trendPnbpKumulatif', 'sops'
        ));
    }

    /**
     * Halaman Manajemen / CRUD Data Bidang Keuangan
     */
    public function manage(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->username === 'berita') {
                return redirect()->route('tvri.berita.dashboard');
            }
            $isKepalaStasiun = $user->hasRole('KEPALA_DINAS') || $user->hasRole('Kepala Stasiun') || $user->username === 'kepala.stasiun' || str_contains(strtolower($user->name ?? ''), 'kepala stasiun');
            if ($isKepalaStasiun) {
                return redirect()->route('tvri.keuangan.dashboard')->with('warning', 'Akun Kepala Stasiun memiliki akses monitoring eksekutif.');
            }
        }

        $tahun = (int) $request->get('tahun', date('Y'));
        $tab = $request->get('tab', 'ringkasan');

        $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $tahun]);
        $anggarans = TvriAnggaran::where('tahun', $tahun)->orderBy('kode')->get();
        $pnbps = TvriPnbp::where('tahun', $tahun)->get();
        $piutangs = TvriPiutang::orderBy('jatuh_tempo')->get();
        $arusKas = TvriKeuanganArusKas::where('tahun', $tahun)->orderBy('urutan')->get();
        $kegiatans = TvriKeuanganKegiatan::where('tahun', $tahun)->orderBy('no_urut')->get();
        $ikpas = self::getOrSeedIkpa($tahun);
        $sops = self::getOrSeedSops();

        // Dokumen Keuangan yang tersimpan di Pangkalan Dokumen
        if (SimandatDocument::where('folder_id', 6)->count() < 5) {
            self::syncFinancialDocuments($tahun);
        }

        // Sinkronisasi inisialisasi awal ke Ringkasan Keuangan jika nilai masih kosong
        $sumPagu = (float) $anggarans->sum('pagu');
        $sumReal = (float) $anggarans->sum('realisasi');
        $sumTgt = (float) $pnbps->sum('target');
        $sumPnbpReal = (float) $pnbps->sum('realisasi');
        $sumPiu = (float) $piutangs->sum('nilai');

        $changed = false;
        if ((empty($ringkasan->pagu_anggaran) || $ringkasan->pagu_anggaran == 0) && $sumPagu > 0) {
            $ringkasan->pagu_anggaran = $sumPagu;
            $ringkasan->realisasi_anggaran = $sumReal;
            $changed = true;
        }
        if ((empty($ringkasan->target_pnbp) || $ringkasan->target_pnbp == 0) && $sumTgt > 0) {
            $ringkasan->target_pnbp = $sumTgt;
            $ringkasan->realisasi_pnbp = $sumPnbpReal;
            $changed = true;
        }
        if ((empty($ringkasan->piutang_tagihan) || $ringkasan->piutang_tagihan == 0) && $sumPiu > 0) {
            $ringkasan->piutang_tagihan = $sumPiu;
            $changed = true;
        }
        if ($changed) {
            $ringkasan->save();
        }

        $dokumenKeuangans = SimandatDocument::where(function ($q) {
            $q->where('folder_id', 6)
              ->orWhere('category', 'RKA & DPA Perencanaan')
              ->orWhere('tags', 'like', '%keuangan%');
        })->orderByDesc('created_at')->get();

        return view('tvri.keuangan.manage', compact(
            'ringkasan', 'anggarans', 'pnbps', 'piutangs', 'arusKas', 'kegiatans', 'ikpas', 'dokumenKeuangans', 'tahun', 'tab', 'sops'
        ));
    }

    /**
     * 1. Update Ringkasan, Aset & Kewajiban
     */
    public function updateRingkasan(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'pagu_anggaran' => 'nullable|numeric|min:0',
            'realisasi_anggaran' => 'nullable|numeric|min:0',
            'target_pnbp' => 'nullable|numeric|min:0',
            'realisasi_pnbp' => 'nullable|numeric|min:0',
            'piutang_tagihan' => 'nullable|numeric|min:0',
            'ikpa_skor' => 'nullable|numeric|min:0|max:100',
            'ikpa_predikat' => 'nullable|string',
            'aset_lancar' => 'nullable|numeric|min:0',
            'aset_tetap' => 'nullable|numeric|min:0',
            'aset_lainnya' => 'nullable|numeric|min:0',
            'kewajiban_lancar' => 'nullable|numeric|min:0',
            'kewajiban_panjang' => 'nullable|numeric|min:0',
            'ekuitas' => 'nullable|numeric|min:0',
        ]);

        $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $request->tahun]);
        $ringkasan->update($request->only([
            'pagu_anggaran', 'realisasi_anggaran', 'target_pnbp', 'realisasi_pnbp', 'piutang_tagihan',
            'ikpa_skor', 'ikpa_predikat', 'aset_lancar', 'aset_tetap', 'aset_lainnya',
            'kewajiban_lancar', 'kewajiban_panjang', 'ekuitas'
        ]));

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($request->tahun);

        ActivityLogger::log(
            'KEUANGAN_RINGKASAN_UPDATED',
            "Pembaruan Ringkasan Keuangan TA {$request->tahun}: Pagu Rp " . number_format($request->pagu_anggaran ?? 0, 0, ',', '.') . ", Realisasi Rp " . number_format($request->realisasi_anggaran ?? 0, 0, ',', '.') . " tersinkronisasi ke Pangkalan Dokumen & Dashboard Kearsipan."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $request->tahun, 'tab' => 'ringkasan'])
            ->with('success', 'Ringkasan Keuangan, Posisi Aset & Pasiva berhasil diperbarui dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    /**
     * 2. CRUD Rincian Anggaran Belanja
     */
    public function storeAnggaran(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20',
            'uraian' => 'required|string|max:255',
            'pagu' => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
            'tahun' => 'required|integer',
        ]);

        TvriAnggaran::create($request->only(['kode', 'uraian', 'pagu', 'realisasi', 'tahun']));

        // Sinkronisasi otomatis ke Ringkasan Keuangan
        $allAnggaran = TvriAnggaran::where('tahun', $request->tahun)->get();
        $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $request->tahun]);
        $ringkasan->update([
            'pagu_anggaran' => (float) $allAnggaran->sum('pagu'),
            'realisasi_anggaran' => (float) $allAnggaran->sum('realisasi'),
        ]);

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($request->tahun);

        ActivityLogger::log(
            'KEUANGAN_ANGGARAN_UPDATED',
            "Menambahkan rincian belanja anggaran akun {$request->kode} ({$request->uraian}) pagu Rp " . number_format($request->pagu, 0, ',', '.') . " TA {$request->tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $request->tahun, 'tab' => 'anggaran'])
            ->with('success', 'Rincian Belanja Anggaran baru berhasil ditambahkan dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    public function updateAnggaran(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:20',
            'uraian' => 'required|string|max:255',
            'pagu' => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
        ]);

        $anggaran = TvriAnggaran::findOrFail($id);
        $anggaran->update($request->only(['kode', 'uraian', 'pagu', 'realisasi']));

        // Sinkronisasi otomatis ke Ringkasan Keuangan
        $allAnggaran = TvriAnggaran::where('tahun', $anggaran->tahun)->get();
        $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $anggaran->tahun]);
        $ringkasan->update([
            'pagu_anggaran' => (float) $allAnggaran->sum('pagu'),
            'realisasi_anggaran' => (float) $allAnggaran->sum('realisasi'),
        ]);

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($anggaran->tahun);

        ActivityLogger::log(
            'KEUANGAN_ANGGARAN_UPDATED',
            "Pembaruan rincian belanja anggaran akun {$anggaran->kode} ({$anggaran->uraian}) TA {$anggaran->tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $anggaran->tahun, 'tab' => 'anggaran'])
            ->with('success', 'Rincian Belanja Anggaran berhasil diperbarui dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    public function destroyAnggaran($id)
    {
        $anggaran = TvriAnggaran::findOrFail($id);
        $tahun = $anggaran->tahun;
        $kode = $anggaran->kode;
        $anggaran->delete();

        // Sinkronisasi otomatis ke Ringkasan Keuangan
        $allAnggaran = TvriAnggaran::where('tahun', $tahun)->get();
        $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $tahun]);
        $ringkasan->update([
            'pagu_anggaran' => (float) $allAnggaran->sum('pagu'),
            'realisasi_anggaran' => (float) $allAnggaran->sum('realisasi'),
        ]);

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($tahun);

        ActivityLogger::log(
            'KEUANGAN_ANGGARAN_UPDATED',
            "Menghapus rincian belanja anggaran akun {$kode} TA {$tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'anggaran'])
            ->with('success', 'Rincian Belanja Anggaran berhasil dihapus dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    /**
     * 3. CRUD Rincian PNBP
     */
    public function storePnbp(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
            'target' => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
            'tahun' => 'required|integer',
        ]);

        TvriPnbp::create($request->only(['jenis', 'target', 'realisasi', 'tahun']));

        // Sinkronisasi otomatis ke Ringkasan Keuangan
        $allPnbp = TvriPnbp::where('tahun', $request->tahun)->get();
        $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $request->tahun]);
        $ringkasan->update([
            'target_pnbp' => (float) $allPnbp->sum('target'),
            'realisasi_pnbp' => (float) $allPnbp->sum('realisasi'),
        ]);

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($request->tahun);

        ActivityLogger::log(
            'KEUANGAN_PNBP_UPDATED',
            "Menambahkan kategori PNBP {$request->jenis} target Rp " . number_format($request->target, 0, ',', '.') . " TA {$request->tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $request->tahun, 'tab' => 'pnbp'])
            ->with('success', 'Kategori PNBP baru berhasil ditambahkan dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    public function updatePnbp(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
            'target' => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
        ]);

        $pnbp = TvriPnbp::findOrFail($id);
        $pnbp->update($request->only(['jenis', 'target', 'realisasi']));

        // Sinkronisasi otomatis ke Ringkasan Keuangan
        $allPnbp = TvriPnbp::where('tahun', $pnbp->tahun)->get();
        $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $pnbp->tahun]);
        $ringkasan->update([
            'target_pnbp' => (float) $allPnbp->sum('target'),
            'realisasi_pnbp' => (float) $allPnbp->sum('realisasi'),
        ]);

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($pnbp->tahun);

        ActivityLogger::log(
            'KEUANGAN_PNBP_UPDATED',
            "Pembaruan kategori PNBP {$pnbp->jenis} TA {$pnbp->tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $pnbp->tahun, 'tab' => 'pnbp'])
            ->with('success', 'Kategori PNBP berhasil diperbarui dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    public function destroyPnbp($id)
    {
        $pnbp = TvriPnbp::findOrFail($id);
        $tahun = $pnbp->tahun;
        $jenis = $pnbp->jenis;
        $pnbp->delete();

        // Sinkronisasi otomatis ke Ringkasan Keuangan
        $allPnbp = TvriPnbp::where('tahun', $tahun)->get();
        $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $tahun]);
        $ringkasan->update([
            'target_pnbp' => (float) $allPnbp->sum('target'),
            'realisasi_pnbp' => (float) $allPnbp->sum('realisasi'),
        ]);

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($tahun);

        ActivityLogger::log(
            'KEUANGAN_PNBP_UPDATED',
            "Menghapus kategori PNBP {$jenis} TA {$tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'pnbp'])
            ->with('success', 'Kategori PNBP berhasil dihapus dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    /**
     * 4. CRUD Piutang / Tagihan
     */
    public function storePiutang(Request $request)
    {
        $request->validate([
            'klien' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0',
            'jatuh_tempo' => 'nullable|date',
            'status' => 'required|string|max:50',
        ]);

        $tahun = (int) $request->input('tahun', date('Y'));
        TvriPiutang::create($request->only(['klien', 'nilai', 'jatuh_tempo', 'status']));

        // Sinkronisasi otomatis ke Ringkasan Keuangan
        $allPiutang = TvriPiutang::where('status', '!=', 'Lunas')->get();
        $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $tahun]);
        $ringkasan->update([
            'piutang_tagihan' => (float) $allPiutang->sum('nilai'),
        ]);

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($tahun);

        ActivityLogger::log(
            'KEUANGAN_PIUTANG_UPDATED',
            "Menambahkan tagihan piutang mitra {$request->klien} nilai Rp " . number_format($request->nilai, 0, ',', '.') . " TA {$tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'piutang'])
            ->with('success', 'Data Piutang/Tagihan berhasil ditambahkan dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    public function updatePiutang(Request $request, $id)
    {
        $request->validate([
            'klien' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0',
            'jatuh_tempo' => 'nullable|date',
            'status' => 'required|string|max:50',
        ]);

        $tahun = (int) $request->input('tahun', date('Y'));
        $piutang = TvriPiutang::findOrFail($id);
        $piutang->update($request->only(['klien', 'nilai', 'jatuh_tempo', 'status']));

        // Sinkronisasi otomatis ke Ringkasan Keuangan
        $allPiutang = TvriPiutang::where('status', '!=', 'Lunas')->get();
        $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $tahun]);
        $ringkasan->update([
            'piutang_tagihan' => (float) $allPiutang->sum('nilai'),
        ]);

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($tahun);

        ActivityLogger::log(
            'KEUANGAN_PIUTANG_UPDATED',
            "Pembaruan piutang mitra {$piutang->klien} TA {$tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'piutang'])
            ->with('success', 'Data Piutang/Tagihan berhasil diperbarui dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    public function destroyPiutang(Request $request, $id)
    {
        $tahun = (int) $request->input('tahun', date('Y'));
        $piutang = TvriPiutang::findOrFail($id);
        $klien = $piutang->klien;
        $piutang->delete();

        // Sinkronisasi otomatis ke Ringkasan Keuangan
        $allPiutang = TvriPiutang::where('status', '!=', 'Lunas')->get();
        $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $tahun]);
        $ringkasan->update([
            'piutang_tagihan' => (float) $allPiutang->sum('nilai'),
        ]);

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($tahun);

        ActivityLogger::log(
            'KEUANGAN_PIUTANG_UPDATED',
            "Menghapus data piutang mitra {$klien} TA {$tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'piutang'])
            ->with('success', 'Data Piutang/Tagihan berhasil dihapus dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    /**
     * 5. Update Arus Kas (Batch Jan - Des)
     */
    public function updateArusKas(Request $request)
    {
        $items = $request->input('arus_kas', []);
        $tahun = (int) $request->input('tahun', date('Y'));

        foreach ($items as $id => $val) {
            $ak = TvriKeuanganArusKas::find($id);
            if ($ak) {
                $masuk = (float) ($val['penerimaan'] ?? 0);
                $keluar = (float) ($val['pengeluaran'] ?? 0);
                $ak->update([
                    'penerimaan' => $masuk,
                    'pengeluaran' => $keluar,
                    'saldo' => $masuk - $keluar,
                ]);
            }
        }

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($tahun);

        ActivityLogger::log(
            'KEUANGAN_ARUSKAS_UPDATED',
            "Pembaruan Arus Kas Bulanan TA {$tahun} tersinkronisasi ke Pangkalan Dokumen & Dashboard Kearsipan."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'aruskas'])
            ->with('success', 'Arus Kas Bulanan berhasil diperbarui dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    /**
     * 6. CRUD Rencana Kegiatan
     */
    public function storeKegiatan(Request $request)
    {
        $request->validate([
            'no_urut' => 'required|integer',
            'kegiatan' => 'required|string|max:255',
            'target' => 'nullable|string|max:100',
            'realisasi' => 'nullable|string|max:100',
            'status' => 'required|string|max:50',
            'tahun' => 'required|integer',
        ]);

        $badge = 'success';
        if (str_contains(strtolower($request->status), 'belum')) {
            $badge = 'neutral';
        } elseif (str_contains(strtolower($request->status), 'kendala') || str_contains(strtolower($request->status), 'tunda')) {
            $badge = 'warning';
        }

        TvriKeuanganKegiatan::create([
            'no_urut' => $request->no_urut,
            'kegiatan' => $request->kegiatan,
            'target' => $request->target,
            'realisasi' => $request->realisasi,
            'status' => $request->status,
            'badge' => $badge,
            'tahun' => $request->tahun,
        ]);

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($request->tahun);

        ActivityLogger::log(
            'KEUANGAN_KEGIATAN_UPDATED',
            "Menambahkan rencana kegiatan keuangan ({$request->kegiatan}) TA {$request->tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $request->tahun, 'tab' => 'kegiatan'])
            ->with('success', 'Rencana Kegiatan Keuangan baru berhasil ditambahkan dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    public function updateKegiatan(Request $request, $id)
    {
        $request->validate([
            'no_urut' => 'required|integer',
            'kegiatan' => 'required|string|max:255',
            'target' => 'nullable|string|max:100',
            'realisasi' => 'nullable|string|max:100',
            'status' => 'required|string|max:50',
        ]);

        $kegiatan = TvriKeuanganKegiatan::findOrFail($id);
        $badge = 'success';
        if (str_contains(strtolower($request->status), 'belum')) {
            $badge = 'neutral';
        } elseif (str_contains(strtolower($request->status), 'kendala') || str_contains(strtolower($request->status), 'tunda')) {
            $badge = 'warning';
        }

        $kegiatan->update([
            'no_urut' => $request->no_urut,
            'kegiatan' => $request->kegiatan,
            'target' => $request->target,
            'realisasi' => $request->realisasi,
            'status' => $request->status,
            'badge' => $badge,
        ]);

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($kegiatan->tahun);

        ActivityLogger::log(
            'KEUANGAN_KEGIATAN_UPDATED',
            "Pembaruan rencana kegiatan keuangan ({$kegiatan->kegiatan}) TA {$kegiatan->tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $kegiatan->tahun, 'tab' => 'kegiatan'])
            ->with('success', 'Rencana Kegiatan Keuangan berhasil diperbarui dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    public function destroyKegiatan($id)
    {
        $kegiatan = TvriKeuanganKegiatan::findOrFail($id);
        $tahun = $kegiatan->tahun;
        $nama = $kegiatan->kegiatan;
        $kegiatan->delete();

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($tahun);

        ActivityLogger::log(
            'KEUANGAN_KEGIATAN_UPDATED',
            "Menghapus kegiatan keuangan ({$nama}) TA {$tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'kegiatan'])
            ->with('success', 'Rencana Kegiatan Keuangan berhasil dihapus dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    /**
     * 7. CRUD Indikator IKPA
     */
    public function updateIkpa(Request $request)
    {
        $ikpaInputs = $request->input('ikpa', []);
        $tahun = (int) $request->input('tahun', date('Y'));

        $totScore = 0;
        foreach ($ikpaInputs as $id => $val) {
            $item = TvriKeuanganIkpa::find($id);
            if ($item) {
                $nilai = isset($val['nilai']) ? (float) str_replace(',', '.', $val['nilai']) : $item->nilai;
                $bobot = isset($val['bobot']) ? (str_contains($val['bobot'], '%') ? $val['bobot'] : $val['bobot'] . '%') : $item->bobot;
                $skor = isset($val['skor']) ? trim($val['skor']) : $item->skor;

                $item->update([
                    'indikator' => $val['indikator'] ?? $item->indikator,
                    'nilai' => $nilai,
                    'bobot' => $bobot,
                    'skor' => $skor,
                ]);
                $skorNum = (float) str_replace(['%', ','], ['', '.'], $skor);
                $totScore += $skorNum;
            }
        }

        if ($totScore > 0) {
            $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $tahun]);
            $predikat = $totScore >= 90 ? 'Sangat Baik' : ($totScore >= 80 ? 'Baik' : ($totScore >= 70 ? 'Cukup' : 'Perlu Evaluasi'));
            $ringkasan->update(['ikpa_skor' => round($totScore, 2), 'ikpa_predikat' => $predikat]);
        }

        // Sinkronkan ke Pangkalan Dokumen & Kearsipan
        self::syncFinancialDocuments($tahun);

        ActivityLogger::log(
            'KEUANGAN_IKPA_UPDATED',
            "Pembaruan indikator kinerja IKPA Satker TVRI Stasiun Maluku TA {$tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'ringkasan'])
            ->with('success', 'Rincian Indikator IKPA berhasil diperbarui dan tersinkronisasi ke Pangkalan Dokumen & Kearsipan.');
    }

    public function storeIkpa(Request $request)
    {
        $request->validate([
            'indikator' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0|max:100',
            'bobot' => 'required|string|max:10',
            'skor' => 'required|string|max:10',
            'tahun' => 'required|integer',
        ]);

        $maxUrutan = (int) TvriKeuanganIkpa::where('tahun', $request->tahun)->max('urutan');
        TvriKeuanganIkpa::create([
            'tahun' => $request->tahun,
            'urutan' => $maxUrutan + 1,
            'indikator' => $request->indikator,
            'nilai' => $request->nilai,
            'bobot' => str_contains($request->bobot, '%') ? $request->bobot : $request->bobot . '%',
            'skor' => $request->skor,
        ]);

        $this->recalculateIkpaTotal($request->tahun);
        self::syncFinancialDocuments($request->tahun);

        ActivityLogger::log(
            'KEUANGAN_IKPA_CREATED',
            "Menambahkan indikator IKPA ({$request->indikator}) TA {$request->tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $request->tahun, 'tab' => 'ringkasan'])
            ->with('success', 'Indikator IKPA baru berhasil ditambahkan.');
    }

    public function destroyIkpa(Request $request, $id)
    {
        $item = TvriKeuanganIkpa::findOrFail($id);
        $tahun = $item->tahun;
        $nama = $item->indikator;
        $item->delete();

        $this->recalculateIkpaTotal($tahun);
        self::syncFinancialDocuments($tahun);

        ActivityLogger::log(
            'KEUANGAN_IKPA_DELETED',
            "Menghapus indikator IKPA ({$nama}) TA {$tahun} tersinkronisasi ke Pangkalan Dokumen."
        );

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'ringkasan'])
            ->with('success', 'Indikator IKPA berhasil dihapus.');
    }

    private function recalculateIkpaTotal(int $tahun)
    {
        $items = TvriKeuanganIkpa::where('tahun', $tahun)->get();
        $tot = 0;
        foreach ($items as $ik) {
            $num = (float) str_replace(['%', ','], ['', '.'], $ik->skor);
            $tot += $num;
        }
        if ($tot > 0) {
            $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $tahun]);
            $predikat = $tot >= 90 ? 'Sangat Baik' : ($tot >= 80 ? 'Baik' : ($tot >= 70 ? 'Cukup' : 'Perlu Evaluasi'));
            $ringkasan->update(['ikpa_skor' => round($tot, 2), 'ikpa_predikat' => $predikat]);
        }
    }

    public static function getOrSeedIkpa(int $tahun)
    {
        $ikpaRows = TvriKeuanganIkpa::where('tahun', $tahun)->orderBy('urutan')->get();
        if ($ikpaRows->isEmpty()) {
            if ($tahun == 2025) {
                $defaultIkpa = [
                    ['urutan' => 1, 'indikator' => 'Revisi DIPA', 'nilai' => 100.00, 'bobot' => '10%', 'skor' => '10,00', 'tahun' => $tahun],
                    ['urutan' => 2, 'indikator' => 'Deviasi Hal III DIPA', 'nilai' => 100.00, 'bobot' => '15%', 'skor' => '15,00', 'tahun' => $tahun],
                    ['urutan' => 3, 'indikator' => 'Penyerapan Anggaran', 'nilai' => 98.34, 'bobot' => '20%', 'skor' => '19,67', 'tahun' => $tahun],
                    ['urutan' => 4, 'indikator' => 'Belanja Kontraktual', 'nilai' => 100.00, 'bobot' => '10%', 'skor' => '10,00', 'tahun' => $tahun],
                    ['urutan' => 5, 'indikator' => 'Penyelesaian Tagihan', 'nilai' => 100.00, 'bobot' => '10%', 'skor' => '10,00', 'tahun' => $tahun],
                    ['urutan' => 6, 'indikator' => 'Pengelolaan UP dan TUP', 'nilai' => 83.31, 'bobot' => '10%', 'skor' => '9,33', 'tahun' => $tahun],
                    ['urutan' => 7, 'indikator' => 'Capaian Output', 'nilai' => 100.00, 'bobot' => '25%', 'skor' => '25,00', 'tahun' => $tahun],
                ];
            } elseif ($tahun == 2026) {
                $defaultIkpa = [
                    ['urutan' => 1, 'indikator' => 'Revisi DIPA', 'nilai' => 100.00, 'bobot' => '10%', 'skor' => '10,00', 'tahun' => $tahun],
                    ['urutan' => 2, 'indikator' => 'Deviasi Hal III DIPA', 'nilai' => 100.00, 'bobot' => '15%', 'skor' => '15,00', 'tahun' => $tahun],
                    ['urutan' => 3, 'indikator' => 'Penyerapan Anggaran', 'nilai' => 97.50, 'bobot' => '20%', 'skor' => '19,50', 'tahun' => $tahun],
                    ['urutan' => 4, 'indikator' => 'Belanja Kontraktual', 'nilai' => 100.00, 'bobot' => '10%', 'skor' => '10,00', 'tahun' => $tahun],
                    ['urutan' => 5, 'indikator' => 'Penyelesaian Tagihan', 'nilai' => 100.00, 'bobot' => '10%', 'skor' => '10,00', 'tahun' => $tahun],
                    ['urutan' => 6, 'indikator' => 'Pengelolaan UP dan TUP', 'nilai' => 97.75, 'bobot' => '10%', 'skor' => '9,78', 'tahun' => $tahun],
                    ['urutan' => 7, 'indikator' => 'Capaian Output', 'nilai' => 100.00, 'bobot' => '25%', 'skor' => '25,00', 'tahun' => $tahun],
                ];
            } else {
                $defaultIkpa = [
                    ['urutan' => 1, 'indikator' => 'Revisi DIPA', 'nilai' => 100, 'bobot' => '10%', 'skor' => '10,00', 'tahun' => $tahun],
                    ['urutan' => 2, 'indikator' => 'Deviasi Hal III DIPA', 'nilai' => 90, 'bobot' => '15%', 'skor' => '13,50', 'tahun' => $tahun],
                    ['urutan' => 3, 'indikator' => 'Penyerapan Anggaran', 'nilai' => 93, 'bobot' => '20%', 'skor' => '18,60', 'tahun' => $tahun],
                    ['urutan' => 4, 'indikator' => 'Belanja Kontraktual', 'nilai' => 88, 'bobot' => '10%', 'skor' => '8,80', 'tahun' => $tahun],
                    ['urutan' => 5, 'indikator' => 'Penyelesaian Tagihan', 'nilai' => 95, 'bobot' => '15%', 'skor' => '14,25', 'tahun' => $tahun],
                    ['urutan' => 6, 'indikator' => 'Pengelolaan UP/TUP', 'nilai' => 90, 'bobot' => '10%', 'skor' => '9,00', 'tahun' => $tahun],
                    ['urutan' => 7, 'indikator' => 'Capaian Output', 'nilai' => 100, 'bobot' => '20%', 'skor' => '20,00', 'tahun' => $tahun],
                ];
            }
            foreach ($defaultIkpa as $ik) {
                TvriKeuanganIkpa::create($ik);
            }
            $ikpaRows = TvriKeuanganIkpa::where('tahun', $tahun)->orderBy('urutan')->get();
        }
        return $ikpaRows;
    }

    /**
     * 8. Trigger Sinkronisasi Manual ke Pangkalan Dokumen & Kearsipan
     */
    public function syncDokumenManual(Request $request)
    {
        $tahun = (int) $request->get('tahun', date('Y'));
        self::syncFinancialDocuments($tahun);

        ActivityLogger::log(
            'KEUANGAN_SYNC_MANUAL',
            "Sinkronisasi manual seluruh data keuangan TA {$tahun} ke Pangkalan Dokumen (DMS) & Dashboard Kearsipan."
        );

        return redirect()->back()
            ->with('success', "Seluruh data keuangan TA {$tahun} berhasil disinkronkan ke Dashboard Keuangan, Dashboard Kearsipan, dan Pangkalan Dokumen.");
    }

    /**
     * Sinkronisasi data keuangan ke Pangkalan Dokumen (SimandatDocument)
     */
    public static function syncFinancialDocuments($tahun = null)
    {
        $tahun = $tahun ?: (int) date('Y');
        $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $tahun]);
        $anggaranRows = TvriAnggaran::where('tahun', $tahun)->orderBy('kode')->get();
        $pnbpRows = TvriPnbp::where('tahun', $tahun)->get();
        $piutangRows = TvriPiutang::where('status', '!=', 'Lunas')->get();

        $sumPagu = (float) $anggaranRows->sum('pagu');
        $sumReal = (float) $anggaranRows->sum('realisasi');
        $pagu = $sumPagu > 0 ? $sumPagu : (float) $ringkasan->pagu_anggaran;
        $real = $sumReal > 0 ? $sumReal : (float) $ringkasan->realisasi_anggaran;
        $persen = $pagu > 0 ? round(($real / $pagu) * 100, 2) : 0;

        $sumTgtPnbp = (float) $pnbpRows->sum('target');
        $sumRealPnbp = (float) $pnbpRows->sum('realisasi');
        $targetPnbp = $sumTgtPnbp > 0 ? $sumTgtPnbp : (float) $ringkasan->target_pnbp;
        $realPnbp = $sumRealPnbp > 0 ? $sumRealPnbp : (float) $ringkasan->realisasi_pnbp;
        $persenPnbp = $targetPnbp > 0 ? round(($realPnbp / $targetPnbp) * 100, 2) : 0;

        $totalPiutang = (float) $piutangRows->sum('nilai');
        if ($totalPiutang == 0 && $ringkasan->piutang_tagihan > 0) {
            $totalPiutang = (float) $ringkasan->piutang_tagihan;
        }

        // Pastikan folder 6 (Subbag Perencanaan & Keuangan) tersedia
        $folder = SimandatDocumentFolder::firstOrCreate(
            ['id' => 6],
            ['name' => 'Subbag Perencanaan & Keuangan (DPA/RKA)', 'code' => 'FLD-SEKR-KEU']
        );

        // Siapkan file fisik di storage agar preview/unduh berfungsi
        $keuDir = storage_path('app/public/documents/keuangan');
        if (!file_exists($keuDir)) {
            @mkdir($keuDir, 0755, true);
        }
        $samplePdf = storage_path('app/public/documents/2026/09/0ORh4HIfYSHKjUhz8199PhIuAhBSCTWGKxN34zRW.pdf');

        $filesToCreate = [
            "LRA_TVRI_Maluku_{$tahun}.pdf" => $samplePdf,
            "DIPA_Petikan_TVRI_Maluku_{$tahun}.pdf" => $samplePdf,
            "Laporan_PNBP_TVRI_Maluku_{$tahun}.pdf" => $samplePdf,
            "Laporan_Piutang_Mitra_TVRI_Maluku_{$tahun}.xlsx" => $samplePdf,
            "Laporan_Arus_Kas_TVRI_Maluku_{$tahun}.xlsx" => $samplePdf,
        ];
        foreach ($filesToCreate as $fName => $srcFile) {
            $dest = $keuDir . DIRECTORY_SEPARATOR . $fName;
            if (!file_exists($dest)) {
                if (file_exists($srcFile)) {
                    @copy($srcFile, $dest);
                } else {
                    @file_put_contents($dest, "%PDF-1.4 Dokumen Resmi Keuangan TVRI Maluku");
                }
            }
        }

        $user = auth()->user();
        $uploaderId = $user ? $user->id : 1;
        $uploaderName = $user ? $user->name : 'Subbag Keuangan TVRI Maluku';

        // 1. Dokumen LRA
        SimandatDocument::updateOrCreate(
            ['document_number' => "LRA/KEU/TVRI-MALUKU/{$tahun}/TW4"],
            [
                'folder_id' => $folder->id,
                'title' => "Laporan Realisasi Anggaran (LRA) Satker TVRI Maluku TA {$tahun}",
                'category' => 'RKA & DPA Perencanaan',
                'division' => 'Sekretariat',
                'description' => "Laporan Realisasi Anggaran belanja Satker TVRI Stasiun Maluku TA {$tahun}. Total Pagu: Rp " . number_format($pagu, 0, ',', '.') . ", Realisasi: Rp " . number_format($real, 0, ',', '.') . " ({$persen}%). Meliputi serapan Belanja Pegawai, Belanja Barang, dan Belanja Modal.",
                'document_date' => now()->toDateString(),
                'year' => $tahun,
                'confidentiality' => 'INTERNAL',
                'status' => 'AKTIF',
                'current_version' => 'v1.0',
                'file_path' => "documents/keuangan/LRA_TVRI_Maluku_{$tahun}.pdf",
                'file_name' => "LRA_TVRI_Maluku_{$tahun}.pdf",
                'file_type' => 'PDF',
                'file_size' => '1.42 MB',
                'tags' => 'keuangan, lra, anggaran, realisasi, dipa, spj, belanja',
                'qr_token' => (string) Str::uuid(),
                'uploader_id' => $uploaderId,
                'uploader_name' => $uploaderName,
            ]
        );

        // 2. Dokumen DIPA Petikan
        SimandatDocument::updateOrCreate(
            ['document_number' => "DIPA-023.04.2.401826/{$tahun}"],
            [
                'folder_id' => $folder->id,
                'title' => "DIPA Petikan & Rincian Alokasi Belanja Satker TVRI Maluku TA {$tahun}",
                'category' => 'RKA & DPA Perencanaan',
                'division' => 'Sekretariat',
                'description' => "Dokumen Isian Pelaksanaan Anggaran (DIPA) Petikan Satker TVRI Maluku TA {$tahun} dengan pagu alokasi belanja definitif Rp " . number_format($pagu, 0, ',', '.') . ".",
                'document_date' => now()->toDateString(),
                'year' => $tahun,
                'confidentiality' => 'TERBATAS',
                'status' => 'AKTIF',
                'current_version' => 'v1.0',
                'file_path' => "documents/keuangan/DIPA_Petikan_TVRI_Maluku_{$tahun}.pdf",
                'file_name' => "DIPA_Petikan_TVRI_Maluku_{$tahun}.pdf",
                'file_type' => 'PDF',
                'file_size' => '2.85 MB',
                'tags' => 'dipa, rka, anggaran, pagu, belanja, keuangan',
                'qr_token' => (string) Str::uuid(),
                'uploader_id' => $uploaderId,
                'uploader_name' => $uploaderName,
            ]
        );

        // 3. Dokumen PNBP
        SimandatDocument::updateOrCreate(
            ['document_number' => "PNBP/KEU/TVRI-MALUKU/{$tahun}"],
            [
                'folder_id' => $folder->id,
                'title' => "Laporan Realisasi Penerimaan Negara Bukan Pajak (PNBP) TVRI Maluku TA {$tahun}",
                'category' => 'Laporan Kinerja & Akuntabilitas',
                'division' => 'Sekretariat',
                'description' => "Rekapitulasi target dan realisasi pendapatan PNBP TVRI Maluku TA {$tahun}. Target: Rp " . number_format($targetPnbp, 0, ',', '.') . ", Realisasi: Rp " . number_format($realPnbp, 0, ',', '.') . " ({$persenPnbp}%).",
                'document_date' => now()->toDateString(),
                'year' => $tahun,
                'confidentiality' => 'INTERNAL',
                'status' => 'AKTIF',
                'current_version' => 'v1.0',
                'file_path' => "documents/keuangan/Laporan_PNBP_TVRI_Maluku_{$tahun}.pdf",
                'file_name' => "Laporan_PNBP_TVRI_Maluku_{$tahun}.pdf",
                'file_type' => 'PDF',
                'file_size' => '850 KB',
                'tags' => 'pnbp, pendapatan, penerimaan, iklan, sewa, keuangan',
                'qr_token' => (string) Str::uuid(),
                'uploader_id' => $uploaderId,
                'uploader_name' => $uploaderName,
            ]
        );

        // 4. Dokumen Piutang
        SimandatDocument::updateOrCreate(
            ['document_number' => "PIU/KEU/TVRI-MALUKU/{$tahun}"],
            [
                'folder_id' => $folder->id,
                'title' => "Laporan Posisi Piutang & Tagihan Mitra TVRI Maluku",
                'category' => 'Data Pokok & Statistik',
                'division' => 'Sekretariat',
                'description' => "Daftar rincian piutang dan tagihan mitra penyiaran, pemerintah daerah, dan sponsor TVRI Maluku. Total Tagihan Outstanding: Rp " . number_format($totalPiutang, 0, ',', '.') . ".",
                'document_date' => now()->toDateString(),
                'year' => $tahun,
                'confidentiality' => 'TERBATAS',
                'status' => 'AKTIF',
                'current_version' => 'v1.0',
                'file_path' => "documents/keuangan/Laporan_Piutang_Mitra_TVRI_Maluku_{$tahun}.xlsx",
                'file_name' => "Laporan_Piutang_Mitra_TVRI_Maluku_{$tahun}.xlsx",
                'file_type' => 'XLSX',
                'file_size' => '420 KB',
                'tags' => 'piutang, tagihan, mitra, sponsor, keuangan',
                'qr_token' => (string) Str::uuid(),
                'uploader_id' => $uploaderId,
                'uploader_name' => $uploaderName,
            ]
        );

        // 5. Dokumen Arus Kas
        SimandatDocument::updateOrCreate(
            ['document_number' => "BKU/ARUSKAS/TVRI-MALUKU/{$tahun}"],
            [
                'folder_id' => $folder->id,
                'title' => "Buku Kas Umum & Laporan Arus Kas Satker TVRI Maluku TA {$tahun}",
                'category' => 'Laporan Kinerja & Akuntabilitas',
                'division' => 'Sekretariat',
                'description' => "Laporan pergerakan arus kas bulanan penerimaan vs pengeluaran dan posisi saldo akhir kas satker TVRI Maluku TA {$tahun}.",
                'document_date' => now()->toDateString(),
                'year' => $tahun,
                'confidentiality' => 'TERBATAS',
                'status' => 'AKTIF',
                'current_version' => 'v1.0',
                'file_path' => "documents/keuangan/Laporan_Arus_Kas_TVRI_Maluku_{$tahun}.xlsx",
                'file_name' => "Laporan_Arus_Kas_TVRI_Maluku_{$tahun}.xlsx",
                'file_type' => 'XLSX',
                'file_size' => '610 KB',
                'tags' => 'aruskas, kas, bku, keuangan, saldo',
                'qr_token' => (string) Str::uuid(),
                'uploader_id' => $uploaderId,
                'uploader_name' => $uploaderName,
            ]
        );
    }

    /**
     * Dapatkan daftar SOP Keuangan atau inisialisasi default jika kosong
     */
    public static function getOrSeedSops()
    {
        $sops = TvriKeuanganSop::orderBy('urutan')->get();
        if ($sops->isEmpty()) {
            $defaultSops = [
                [
                    'kode' => 'SOP-KEU-01',
                    'judul' => 'SOP Pengajuan & Penerbitan Surat Perintah Membayar (SPM)',
                    'kategori' => 'Pengelolaan Anggaran',
                    'versi' => 'v2.1',
                    'status' => 'Aktif',
                    'keterangan' => 'Prosedur baku verifikasi dokumen SPP, kelengkapan berkas kuitansi/faktur, hingga terbit SPM LS dan UP.',
                    'urutan' => 1,
                ],
                [
                    'kode' => 'SOP-KEU-02',
                    'judul' => 'SOP Penatausahaan & Penyetoran PNBP via Aplikasi SIMPONI',
                    'kategori' => 'Pengelolaan PNBP',
                    'versi' => 'v2.0',
                    'status' => 'Aktif',
                    'keterangan' => 'Standar pembuatan kode billing SIMPONI, konfirmasi bukti setor perbankan, dan pencatatan kas negara.',
                    'urutan' => 2,
                ],
                [
                    'kode' => 'SOP-KEU-03',
                    'judul' => 'SOP Pengelolaan Uang Persediaan (UP) & Ganti Uang (GUP)',
                    'kategori' => 'Perbendaharaan',
                    'versi' => 'v1.4',
                    'status' => 'Aktif',
                    'keterangan' => 'Tata cara revolving UP/TUP bendahara pengeluaran dengan batas waktu pertanggungjawaban 30 hari.',
                    'urutan' => 3,
                ],
                [
                    'kode' => 'SOP-KEU-04',
                    'judul' => 'SOP Penatausahaan & Penagihan Piutang Mitra Penyiaran',
                    'kategori' => 'Pengelolaan Piutang',
                    'versi' => 'v1.2',
                    'status' => 'Aktif',
                    'keterangan' => 'Mekanisme surat tagihan I s.d III, rekonsiliasi piutang mitra, dan pelaporan piutang macet ke KPKNL.',
                    'urutan' => 4,
                ],
                [
                    'kode' => 'SOP-KEU-05',
                    'judul' => 'SOP Rekonsiliasi Internal BMN & Laporan Keuangan Semesteran',
                    'kategori' => 'Akuntansi & Pelaporan',
                    'versi' => 'v2.0',
                    'status' => 'Aktif',
                    'keterangan' => 'Sinkronisasi modul GLP, Persediaan, dan Aset Tetap SAKTI dengan KPPN untuk menghasilkan LRA dan Neraca.',
                    'urutan' => 5,
                ],
                [
                    'kode' => 'SOP-KEU-06',
                    'judul' => 'SOP Monitoring & Evaluasi Indikator Kinerja Pelaksanaan Anggaran (IKPA)',
                    'kategori' => 'Kinerja Anggaran',
                    'versi' => 'v1.3',
                    'status' => 'Aktif',
                    'keterangan' => 'Langkah pencegahan deviasi Hal III DIPA, optimalisasi revisi anggaran, dan percepatan penyelesaian tagihan.',
                    'urutan' => 6,
                ],
            ];
            foreach ($defaultSops as $item) {
                TvriKeuanganSop::create($item);
            }
            $sops = TvriKeuanganSop::orderBy('urutan')->get();
        }
        return $sops;
    }

    /**
     * CRUD: Tambah SOP Keuangan
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
            'urutan' => $request->urutan ?? (TvriKeuanganSop::max('urutan') + 1),
        ];

        if ($request->hasFile('file')) {
            $f = $request->file('file');
            $cleanName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $f->getClientOriginalName());
            $path = $f->storeAs('sops', $cleanName, 'public');
            $data['file_url'] = 'storage/' . $path;
            $data['file_path'] = 'storage/' . $path;
        }

        TvriKeuanganSop::create($data);

        ActivityLogger::log('KEUANGAN_SOP_CREATED', "Menambahkan SOP Keuangan: {$request->kode} - {$request->judul}");

        return redirect()->route('tvri.keuangan.manage', ['tahun' => (int) $request->get('tahun', date('Y')), 'tab' => 'sop'])
            ->with('success', "SOP Keuangan {$request->kode} berhasil ditambahkan.");
    }

    /**
     * CRUD: Update SOP Keuangan
     */
    public function updateSop(Request $request, $id)
    {
        $sop = TvriKeuanganSop::findOrFail($id);

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

        ActivityLogger::log('KEUANGAN_SOP_UPDATED', "Memperbarui SOP Keuangan: {$sop->kode} - {$sop->judul}");

        return redirect()->route('tvri.keuangan.manage', ['tahun' => (int) $request->get('tahun', date('Y')), 'tab' => 'sop'])
            ->with('success', "SOP Keuangan {$sop->kode} berhasil diperbarui.");
    }

    /**
     * CRUD: Hapus SOP Keuangan
     */
    public function destroySop(Request $request, $id)
    {
        $sop = TvriKeuanganSop::findOrFail($id);
        $kode = $sop->kode;
        $sop->delete();

        ActivityLogger::log('KEUANGAN_SOP_DELETED', "Menghapus SOP Keuangan: {$kode}");

        return redirect()->route('tvri.keuangan.manage', ['tahun' => (int) $request->get('tahun', date('Y')), 'tab' => 'sop'])
            ->with('success', "SOP Keuangan {$kode} berhasil dihapus.");
    }

    /**
     * CRUD: Bulk Delete SOP Keuangan
     */
    public function bulkDestroySop(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriKeuanganSop::whereIn('id', $ids)->delete();
            ActivityLogger::log('KEUANGAN_SOP_BULK_DELETED', "Menghapus " . count($ids) . " SOP Keuangan");
        }

        return redirect()->route('tvri.keuangan.manage', ['tahun' => (int) $request->get('tahun', date('Y')), 'tab' => 'sop'])
            ->with('success', "SOP Keuangan terpilih berhasil dihapus.");
    }

    public function bulkDestroyAnggaran(Request $request)
    {
        $ids = $request->input('ids', []);
        $tahun = (int) $request->get('tahun', date('Y'));
        if (!empty($ids)) {
            TvriAnggaran::whereIn('id', $ids)->delete();

            // Sinkronisasi otomatis ke Ringkasan Keuangan
            $allAnggaran = TvriAnggaran::where('tahun', $tahun)->get();
            $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $tahun]);
            $ringkasan->update([
                'pagu_anggaran' => (float) $allAnggaran->sum('pagu'),
                'realisasi_anggaran' => (float) $allAnggaran->sum('realisasi'),
            ]);
            self::syncFinancialDocuments($tahun);

            ActivityLogger::log('KEUANGAN_ANGGARAN_BULK_DELETED', "Menghapus " . count($ids) . " rincian anggaran");
        }

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'anggaran'])
            ->with('success', "Rincian anggaran terpilih berhasil dihapus.");
    }

    public function bulkDestroyPnbp(Request $request)
    {
        $ids = $request->input('ids', []);
        $tahun = (int) $request->get('tahun', date('Y'));
        if (!empty($ids)) {
            TvriPnbp::whereIn('id', $ids)->delete();

            // Sinkronisasi otomatis ke Ringkasan Keuangan
            $allPnbp = TvriPnbp::where('tahun', $tahun)->get();
            $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $tahun]);
            $ringkasan->update([
                'target_pnbp' => (float) $allPnbp->sum('target'),
                'realisasi_pnbp' => (float) $allPnbp->sum('realisasi'),
            ]);
            self::syncFinancialDocuments($tahun);

            ActivityLogger::log('KEUANGAN_PNBP_BULK_DELETED', "Menghapus " . count($ids) . " item PNBP");
        }

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'pnbp'])
            ->with('success', "Item PNBP terpilih berhasil dihapus.");
    }

    public function bulkDestroyPiutang(Request $request)
    {
        $ids = $request->input('ids', []);
        $tahun = (int) $request->get('tahun', date('Y'));
        if (!empty($ids)) {
            TvriPiutang::whereIn('id', $ids)->delete();

            // Sinkronisasi otomatis ke Ringkasan Keuangan
            $allPiutang = TvriPiutang::where('status', '!=', 'Lunas')->get();
            $ringkasan = TvriKeuanganRingkasan::firstOrCreate(['tahun' => $tahun]);
            $ringkasan->update([
                'piutang_tagihan' => (float) $allPiutang->sum('nilai'),
            ]);
            self::syncFinancialDocuments($tahun);

            ActivityLogger::log('KEUANGAN_PIUTANG_BULK_DELETED', "Menghapus " . count($ids) . " piutang");
        }

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'piutang'])
            ->with('success', "Piutang terpilih berhasil dihapus.");
    }

    public function bulkDestroyKegiatan(Request $request)
    {
        $ids = $request->input('ids', []);
        $tahun = (int) $request->get('tahun', date('Y'));
        if (!empty($ids)) {
            TvriKeuanganKegiatan::whereIn('id', $ids)->delete();
            self::syncFinancialDocuments($tahun);
            ActivityLogger::log('KEUANGAN_KEGIATAN_BULK_DELETED', "Menghapus " . count($ids) . " rencana kegiatan");
        }

        return redirect()->route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'kegiatan'])
            ->with('success', "Rencana kegiatan terpilih berhasil dihapus.");
    }
}
