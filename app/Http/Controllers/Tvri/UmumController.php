<?php

namespace App\Http\Controllers\Tvri;

use App\Http\Controllers\Controller;
use App\Models\TvriUmumRingkasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UmumController extends Controller
{
    /**
     * Get or create default ringkasan
     */
    private function getRingkasan()
    {
        $ringkasan = TvriUmumRingkasan::first();
        if (!$ringkasan) {
            $ringkasan = TvriUmumRingkasan::create([
                'periode' => 'September 2025',
                'header_waktu' => 'Selasa, 2 September 2025 | 10:24 WIT',
                'live_status' => 'Live System | Periode: September 2025',
                'slogan_header' => 'LAYANAN OPERASIONAL • SARANA PRASARANA • ASET • KEAMANAN • KENYAMANAN',
                'slogan_sub' => '"Lingkungan Kerja yang Aman, Nyaman dan Produktif untuk Siaran yang Lebih Bermakna"',
                'slogan_motto' => 'Maluku Bisa Lebih Maju!',
                'total_aset' => 1245,
                'diff_aset' => '+3% dari bulan lalu',
                'kendaraan_total' => 28,
                'kendaraan_siap' => 26,
                'kendaraan_siap_pct' => 92,
                'diff_kendaraan' => '+4%',
                'kendaraan_perbaikan' => 2,
                'kendaraan_tidak_operasi' => 0,
                'sarpras_total' => 56,
                'sarpras_baik_pct' => 89,
                'diff_sarpras' => '+5%',
                'permintaan_total' => 42,
                'permintaan_selesai' => 37,
                'permintaan_selesai_pct' => 88,
                'diff_permintaan' => '+12%',
                'permintaan_proses' => 3,
                'permintaan_ditunda' => 1,
                'permintaan_dibatalkan' => 1,
                'insiden_keamanan' => 2,
                'diff_insiden' => '-50% dari bulan lalu',
                'indeks_kenyamanan' => 92,
                'target_kenyamanan' => 90,
                'operational_readiness' => 94,
                'target_readiness' => 90,
                'aset_baik' => 892,
                'aset_rusak_ringan' => 178,
                'aset_rusak_berat' => 56,
                'aset_dalam_perbaikan' => 78,
                'aset_tidak_aktif' => 41,
                'anggaran_pagu' => 2450000000,
                'anggaran_terpakai' => 1837500000,
                'anggaran_sisa' => 612500000,
                'anggaran_persen' => 75,
                'personel_security' => 12,
                'pos_keamanan' => 3,
                'cctv_total' => 28,
                'cctv_aktif' => 26,
                'cctv_perbaikan' => 2,
                'apar_total' => 32,
                'apar_terverifikasi' => 32,
                'nilai_kebersihan_kantor' => 94,
                'nilai_kebersihan_studio' => 96,
                'nilai_kebersihan_halaman' => 90,
                'nilai_kebersihan_toilet' => 88,
            ]);
        }
        return $ringkasan;
    }

    public function index()
    {
        $ringkasan = $this->getRingkasan();

        // 1. Dynamic Aset
        $countAsetRows = DB::table('tvri_asets')->count();
        if ($countAsetRows > 0) {
            $totalAset = (int) DB::table('tvri_asets')->sum('jumlah');
            $asetBaik = (int) DB::table('tvri_asets')->where('kondisi', 'Baik')->sum('jumlah');
            $asetRusakRingan = (int) DB::table('tvri_asets')->where('kondisi', 'Rusak Ringan')->sum('jumlah');
            $asetDalamPerbaikan = (int) DB::table('tvri_asets')->where('kondisi', 'Dalam Perbaikan')->sum('jumlah');
            $asetRusakBerat = (int) DB::table('tvri_asets')->where('kondisi', 'Rusak Berat')->sum('jumlah');
            $asetTidakAktif = (int) DB::table('tvri_asets')->where('kondisi', 'Tidak Aktif')->sum('jumlah');
            $nilaiAset = (float) DB::table('tvri_asets')->sum('nilai');
        } else {
            $totalAset = $ringkasan->total_aset;
            $asetBaik = $ringkasan->aset_baik;
            $asetRusakRingan = $ringkasan->aset_rusak_ringan;
            $asetDalamPerbaikan = $ringkasan->aset_dalam_perbaikan;
            $asetRusakBerat = $ringkasan->aset_rusak_berat;
            $asetTidakAktif = $ringkasan->aset_tidak_aktif;
            $nilaiAset = 19100000000;
        }

        $pctAsetBaik = $totalAset > 0 ? round(($asetBaik / $totalAset) * 100) : 72;
        $pctAsetRusakRingan = $totalAset > 0 ? round(($asetRusakRingan / $totalAset) * 100) : 14;
        $pctAsetDalamPerbaikan = $totalAset > 0 ? round(($asetDalamPerbaikan / $totalAset) * 100) : 6;
        $pctAsetRusakBerat = $totalAset > 0 ? round(($asetRusakBerat / $totalAset) * 100) : 4;
        $pctAsetTidakAktif = $totalAset > 0 ? round(($asetTidakAktif / $totalAset) * 100) : 4;
        $asetProduktif = $asetBaik;
        $asetTindakLanjut = $totalAset - $asetBaik;

        // 2. Dynamic Kendaraan
        $countKendaraanRows = DB::table('tvri_kendaraans')->count();
        if ($countKendaraanRows > 0) {
            $kendaraanTotal = $countKendaraanRows;
            $kendaraanSiap = DB::table('tvri_kendaraans')->where('status', 'Siap Operasi')->count();
            $kendaraanPerbaikan = DB::table('tvri_kendaraans')->where('status', 'Dalam Perbaikan')->count();
            $kendaraanTidakOperasi = DB::table('tvri_kendaraans')->where('status', 'Tidak Operasi')->count();
        } else {
            $kendaraanTotal = $ringkasan->kendaraan_total;
            $kendaraanSiap = $ringkasan->kendaraan_siap;
            $kendaraanPerbaikan = $ringkasan->kendaraan_perbaikan;
            $kendaraanTidakOperasi = $ringkasan->kendaraan_tidak_operasi;
        }

        $kendaraanSiapPct = $kendaraanTotal > 0 ? round(($kendaraanSiap / $kendaraanTotal) * 100) : 92;
        $kendaraanPerbaikanPct = $kendaraanTotal > 0 ? round(($kendaraanPerbaikan / $kendaraanTotal) * 100) : 7;
        $kendaraanTidakOperasiPct = $kendaraanTotal > 0 ? round(($kendaraanTidakOperasi / $kendaraanTotal) * 100) : 0;

        $countMobil = DB::table('tvri_kendaraans')->where('jenis', 'Mobil')->count() ?: 18;
        $countMotor = DB::table('tvri_kendaraans')->where('jenis', 'Motor')->count() ?: 6;
        $countBus = DB::table('tvri_kendaraans')->where('jenis', 'Bus')->count() ?: 2;
        $countTruk = DB::table('tvri_kendaraans')->where('jenis', 'Truk')->count() ?: 2;

        // 3. Dynamic Sarpras
        $sarpras = DB::table('tvri_sarpras')->orderBy('id', 'asc')->get();
        $sarprasTotal = $sarpras->sum('jumlah_unit') ?: $ringkasan->sarpras_total;
        $sarprasBaikPct = $ringkasan->sarpras_baik_pct;

        // 4. Dynamic Permintaan Layanan
        $countLayananRows = DB::table('tvri_permintaan_layanans')->count();
        if ($countLayananRows > 0) {
            $permintaanTotal = $countLayananRows;
            $permintaanSelesai = DB::table('tvri_permintaan_layanans')->where('status', 'Selesai')->count();
            $permintaanProses = DB::table('tvri_permintaan_layanans')->whereIn('status', ['Dalam Proses', 'Proses'])->count();
            $permintaanTertunda = DB::table('tvri_permintaan_layanans')->where('status', 'Tertunda')->count();
            $permintaanDibatalkan = DB::table('tvri_permintaan_layanans')->where('status', 'Dibatalkan')->count();
        } else {
            $permintaanTotal = $ringkasan->permintaan_total;
            $permintaanSelesai = $ringkasan->permintaan_selesai;
            $permintaanProses = $ringkasan->permintaan_proses;
            $permintaanTertunda = $ringkasan->permintaan_ditunda;
            $permintaanDibatalkan = $ringkasan->permintaan_dibatalkan;
        }

        $permintaanSelesaiPct = $permintaanTotal > 0 ? round(($permintaanSelesai / $permintaanTotal) * 100) : 88;
        $permintaanProsesPct = $permintaanTotal > 0 ? round(($permintaanProses / $permintaanTotal) * 100) : 7;
        $permintaanTertundaPct = $permintaanTotal > 0 ? round(($permintaanTertunda / $permintaanTotal) * 100) : 2;
        $permintaanDibatalkanPct = $permintaanTotal > 0 ? round(($permintaanDibatalkan / $permintaanTotal) * 100) : 2;

        $tiketTerbaru = DB::table('tvri_permintaan_layanans')->orderBy('id', 'desc')->limit(3)->get();

        // 5. Pemeliharaan & Stok
        $pemeliharaans = DB::table('tvri_pemeliharaans')->orderBy('tanggal', 'asc')->get();
        $stokBarangs = DB::table('tvri_stok_barangs')->orderBy('id', 'asc')->get();
        $stokPerluPengadaan = DB::table('tvri_stok_barangs')->whereIn('status_stok', ['Perlu Pengadaan', 'Menipis', 'Habis'])->count() ?: 3;

        // 6. List Aset & Kendaraan
        $asets = DB::table('tvri_asets')->orderBy('id', 'desc')->get();
        $kendaraans = DB::table('tvri_kendaraans')->orderBy('id', 'asc')->get();

        // Sync back to ringkasan object for consistency
        $ringkasan->total_aset = $totalAset;
        $ringkasan->aset_baik = $asetBaik;
        $ringkasan->aset_rusak_ringan = $asetRusakRingan;
        $ringkasan->aset_dalam_perbaikan = $asetDalamPerbaikan;
        $ringkasan->aset_rusak_berat = $asetRusakBerat;
        $ringkasan->aset_tidak_aktif = $asetTidakAktif;
        $ringkasan->kendaraan_total = $kendaraanTotal;
        $ringkasan->kendaraan_siap = $kendaraanSiap;
        $ringkasan->kendaraan_siap_pct = $kendaraanSiapPct;
        $ringkasan->kendaraan_perbaikan = $kendaraanPerbaikan;
        $ringkasan->kendaraan_tidak_operasi = $kendaraanTidakOperasi;
        $ringkasan->sarpras_total = $sarprasTotal;
        $ringkasan->permintaan_total = $permintaanTotal;
        $ringkasan->permintaan_selesai = $permintaanSelesai;
        $ringkasan->permintaan_selesai_pct = $permintaanSelesaiPct;
        $ringkasan->permintaan_proses = $permintaanProses;
        $ringkasan->permintaan_ditunda = $permintaanTertunda;
        $ringkasan->permintaan_dibatalkan = $permintaanDibatalkan;

        return view('tvri.umum.dashboard', compact(
            'ringkasan',
            'totalAset', 'asetBaik', 'asetRusakRingan', 'asetDalamPerbaikan', 'asetRusakBerat', 'asetTidakAktif',
            'pctAsetBaik', 'pctAsetRusakRingan', 'pctAsetDalamPerbaikan', 'pctAsetRusakBerat', 'pctAsetTidakAktif',
            'nilaiAset', 'asetProduktif', 'asetTindakLanjut',
            'kendaraanTotal', 'kendaraanSiap', 'kendaraanSiapPct', 'kendaraanPerbaikan', 'kendaraanPerbaikanPct', 'kendaraanTidakOperasi', 'kendaraanTidakOperasiPct',
            'countMobil', 'countMotor', 'countBus', 'countTruk',
            'sarprasTotal', 'sarprasBaikPct', 'sarpras',
            'permintaanTotal', 'permintaanSelesai', 'permintaanSelesaiPct', 'permintaanProses', 'permintaanProsesPct', 'permintaanTertunda', 'permintaanTertundaPct', 'permintaanDibatalkan', 'permintaanDibatalkanPct',
            'tiketTerbaru', 'pemeliharaans', 'stokBarangs', 'stokPerluPengadaan',
            'asets', 'kendaraans'
        ));
    }

    public function manage()
    {
        $ringkasan = $this->getRingkasan();
        $asets = DB::table('tvri_asets')->orderBy('id', 'desc')->get();
        $kendaraans = DB::table('tvri_kendaraans')->orderBy('id', 'asc')->get();
        $permintaans = DB::table('tvri_permintaan_layanans')->orderBy('id', 'desc')->get();
        $sarpras = DB::table('tvri_sarpras')->orderBy('id', 'asc')->get();
        $pemeliharaans = DB::table('tvri_pemeliharaans')->orderBy('id', 'asc')->get();
        $stokBarangs = DB::table('tvri_stok_barangs')->orderBy('id', 'asc')->get();

        return view('tvri.umum.manage', compact(
            'ringkasan', 'asets', 'kendaraans', 'permintaans', 'sarpras', 'pemeliharaans', 'stokBarangs'
        ));
    }

    public function updateRingkasan(Request $request)
    {
        $ringkasan = $this->getRingkasan();

        $data = $request->except(['_token']);
        $ringkasan->update($data);

        return redirect()->route('tvri.umum.manage', ['tab' => 'ringkasan'])->with('success', 'Parameter ringkasan operasional berhasil diperbarui.');
    }

    // ================= CRUD ASET =================
    public function storeAset(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'kondisi' => 'required|string|max:50',
            'jumlah' => 'required|integer|min:1',
            'nilai' => 'nullable|numeric|min:0',
            'lokasi' => 'nullable|string|max:255',
            'tahun' => 'nullable|integer',
        ]);

        $kode = $request->kode ?: ('BMN-' . rand(100, 999) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT));

        DB::table('tvri_asets')->insert([
            'kode' => $kode,
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'kondisi' => $request->kondisi,
            'jumlah' => $request->jumlah,
            'nilai' => $request->nilai ?? 0,
            'lokasi' => $request->lokasi ?? 'Kantor TVRI Maluku',
            'tahun' => $request->tahun ?? date('Y'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.umum.manage', ['tab' => 'aset'])->with('success', 'Aset BMN berhasil ditambahkan.');
    }

    public function updateAset(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'kondisi' => 'required|string|max:50',
            'jumlah' => 'required|integer|min:1',
            'nilai' => 'nullable|numeric|min:0',
            'lokasi' => 'nullable|string|max:255',
        ]);

        DB::table('tvri_asets')->where('id', $id)->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'kondisi' => $request->kondisi,
            'jumlah' => $request->jumlah,
            'nilai' => $request->nilai ?? 0,
            'lokasi' => $request->lokasi ?? 'Kantor TVRI Maluku',
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.umum.manage', ['tab' => 'aset'])->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroyAset($id)
    {
        DB::table('tvri_asets')->where('id', $id)->delete();
        return redirect()->route('tvri.umum.manage', ['tab' => 'aset'])->with('success', 'Aset berhasil dihapus.');
    }

    public function bulkDestroyAset(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (!empty($ids)) {
            DB::table('tvri_asets')->whereIn('id', $ids)->delete();
            return redirect()->route('tvri.umum.manage', ['tab' => 'aset'])->with('success', count($ids) . ' data aset berhasil dihapus.');
        }
        return redirect()->route('tvri.umum.manage', ['tab' => 'aset'])->with('error', 'Tidak ada data aset yang dipilih untuk dihapus.');
    }

    // ================= CRUD KENDARAAN =================
    public function storeKendaraan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:50',
            'plat_nomor' => 'required|string|max:50',
            'status' => 'required|string|max:50',
        ]);

        DB::table('tvri_kendaraans')->insert([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'plat_nomor' => $request->plat_nomor,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.umum.manage', ['tab' => 'kendaraan'])->with('success', 'Kendaraan dinas berhasil ditambahkan.');
    }

    public function updateKendaraan(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:50',
            'plat_nomor' => 'required|string|max:50',
            'status' => 'required|string|max:50',
        ]);

        DB::table('tvri_kendaraans')->where('id', $id)->update([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'plat_nomor' => $request->plat_nomor,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.umum.manage', ['tab' => 'kendaraan'])->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroyKendaraan($id)
    {
        DB::table('tvri_kendaraans')->where('id', $id)->delete();
        return redirect()->route('tvri.umum.manage', ['tab' => 'kendaraan'])->with('success', 'Kendaraan dinas berhasil dihapus.');
    }

    public function bulkDestroyKendaraan(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (!empty($ids)) {
            DB::table('tvri_kendaraans')->whereIn('id', $ids)->delete();
            return redirect()->route('tvri.umum.manage', ['tab' => 'kendaraan'])->with('success', count($ids) . ' kendaraan dinas berhasil dihapus.');
        }
        return redirect()->route('tvri.umum.manage', ['tab' => 'kendaraan'])->with('error', 'Tidak ada kendaraan yang dipilih untuk dihapus.');
    }

    // ================= CRUD LAYANAN =================
    public function storeLayanan(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
            'pemohon' => 'required|string|max:255',
            'unit' => 'required|string|max:100',
            'status' => 'required|string|max:50',
            'tanggal' => 'nullable|date',
        ]);

        $nomor = 'REQ-' . date('Y') . '-' . str_pad(rand(50, 999), 3, '0', STR_PAD_LEFT);

        DB::table('tvri_permintaan_layanans')->insert([
            'nomor' => $nomor,
            'jenis' => $request->jenis,
            'pemohon' => $request->pemohon,
            'unit' => $request->unit,
            'status' => $request->status,
            'tanggal' => $request->tanggal ?? date('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.umum.manage', ['tab' => 'layanan'])->with('success', 'Permintaan layanan berhasil ditambahkan.');
    }

    public function updateLayanan(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
            'pemohon' => 'required|string|max:255',
            'unit' => 'required|string|max:100',
            'status' => 'required|string|max:50',
            'tanggal' => 'nullable|date',
        ]);

        DB::table('tvri_permintaan_layanans')->where('id', $id)->update([
            'jenis' => $request->jenis,
            'pemohon' => $request->pemohon,
            'unit' => $request->unit,
            'status' => $request->status,
            'tanggal' => $request->tanggal ?? date('Y-m-d'),
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.umum.manage', ['tab' => 'layanan'])->with('success', 'Data permintaan layanan diperbarui.');
    }

    public function destroyLayanan($id)
    {
        DB::table('tvri_permintaan_layanans')->where('id', $id)->delete();
        return redirect()->route('tvri.umum.manage', ['tab' => 'layanan'])->with('success', 'Permintaan layanan berhasil dihapus.');
    }

    public function bulkDestroyLayanan(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (!empty($ids)) {
            DB::table('tvri_permintaan_layanans')->whereIn('id', $ids)->delete();
            return redirect()->route('tvri.umum.manage', ['tab' => 'layanan'])->with('success', count($ids) . ' permintaan layanan berhasil dihapus.');
        }
        return redirect()->route('tvri.umum.manage', ['tab' => 'layanan'])->with('error', 'Tidak ada permintaan yang dipilih untuk dihapus.');
    }

    // ================= CRUD SARPRAS =================
    public function storeSarpras(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'jumlah_unit' => 'required|integer|min:1',
            'kondisi_baik' => 'required|string|max:20',
            'lokasi' => 'nullable|string|max:255',
        ]);

        DB::table('tvri_sarpras')->insert([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'jumlah_unit' => $request->jumlah_unit,
            'kondisi_baik' => str_ends_with($request->kondisi_baik, '%') ? $request->kondisi_baik : ($request->kondisi_baik . '%'),
            'lokasi' => $request->lokasi ?? 'TVRI Stasiun Maluku',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.umum.manage', ['tab' => 'sarpras'])->with('success', 'Sarana prasarana berhasil ditambahkan.');
    }

    public function updateSarpras(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'jumlah_unit' => 'required|integer|min:1',
            'kondisi_baik' => 'required|string|max:20',
            'lokasi' => 'nullable|string|max:255',
        ]);

        DB::table('tvri_sarpras')->where('id', $id)->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'jumlah_unit' => $request->jumlah_unit,
            'kondisi_baik' => str_ends_with($request->kondisi_baik, '%') ? $request->kondisi_baik : ($request->kondisi_baik . '%'),
            'lokasi' => $request->lokasi ?? 'TVRI Stasiun Maluku',
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.umum.manage', ['tab' => 'sarpras'])->with('success', 'Data sarana prasarana berhasil diperbarui.');
    }

    public function destroySarpras($id)
    {
        DB::table('tvri_sarpras')->where('id', $id)->delete();
        return redirect()->route('tvri.umum.manage', ['tab' => 'sarpras'])->with('success', 'Data sarpras berhasil dihapus.');
    }

    public function bulkDestroySarpras(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (!empty($ids)) {
            DB::table('tvri_sarpras')->whereIn('id', $ids)->delete();
            return redirect()->route('tvri.umum.manage', ['tab' => 'sarpras'])->with('success', count($ids) . ' data sarpras berhasil dihapus.');
        }
        return redirect()->route('tvri.umum.manage', ['tab' => 'sarpras'])->with('error', 'Tidak ada data sarpras yang dipilih untuk dihapus.');
    }

    // ================= CRUD PEMELIHARAAN =================
    public function storePemeliharaan(Request $request)
    {
        $request->validate([
            'objek' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'pic' => 'required|string|max:100',
            'status' => 'required|string|max:50',
        ]);

        DB::table('tvri_pemeliharaans')->insert([
            'bidang' => 'Umum',
            'objek' => $request->objek,
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'pic' => $request->pic,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.umum.manage', ['tab' => 'pemeliharaan'])->with('success', 'Agenda pemeliharaan berhasil ditambahkan.');
    }

    public function updatePemeliharaan(Request $request, $id)
    {
        $request->validate([
            'objek' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'pic' => 'required|string|max:100',
            'status' => 'required|string|max:50',
        ]);

        DB::table('tvri_pemeliharaans')->where('id', $id)->update([
            'objek' => $request->objek,
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'pic' => $request->pic,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.umum.manage', ['tab' => 'pemeliharaan'])->with('success', 'Data agenda pemeliharaan diperbarui.');
    }

    public function destroyPemeliharaan($id)
    {
        DB::table('tvri_pemeliharaans')->where('id', $id)->delete();
        return redirect()->route('tvri.umum.manage', ['tab' => 'pemeliharaan'])->with('success', 'Agenda pemeliharaan berhasil dihapus.');
    }

    public function bulkDestroyPemeliharaan(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (!empty($ids)) {
            DB::table('tvri_pemeliharaans')->whereIn('id', $ids)->delete();
            return redirect()->route('tvri.umum.manage', ['tab' => 'pemeliharaan'])->with('success', count($ids) . ' agenda pemeliharaan berhasil dihapus.');
        }
        return redirect()->route('tvri.umum.manage', ['tab' => 'pemeliharaan'])->with('error', 'Tidak ada data pemeliharaan yang dipilih untuk dihapus.');
    }

    // ================= CRUD STOK LOGISTIK =================
    public function storeStok(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:100',
            'status_stok' => 'required|string|max:50',
        ]);

        DB::table('tvri_stok_barangs')->insert([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
            'status_stok' => $request->status_stok,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.umum.manage', ['tab' => 'stok'])->with('success', 'Barang logistik & ATK berhasil ditambahkan.');
    }

    public function updateStok(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:100',
            'status_stok' => 'required|string|max:50',
        ]);

        DB::table('tvri_stok_barangs')->where('id', $id)->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
            'status_stok' => $request->status_stok,
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.umum.manage', ['tab' => 'stok'])->with('success', 'Data stok barang diperbarui.');
    }

    public function destroyStok($id)
    {
        DB::table('tvri_stok_barangs')->where('id', $id)->delete();
        return redirect()->route('tvri.umum.manage', ['tab' => 'stok'])->with('success', 'Data stok barang berhasil dihapus.');
    }

    public function bulkDestroyStok(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (!empty($ids)) {
            DB::table('tvri_stok_barangs')->whereIn('id', $ids)->delete();
            return redirect()->route('tvri.umum.manage', ['tab' => 'stok'])->with('success', count($ids) . ' barang logistik berhasil dihapus.');
        }
        return redirect()->route('tvri.umum.manage', ['tab' => 'stok'])->with('error', 'Tidak ada data stok yang dipilih untuk dihapus.');
    }

    public function reseedDefaults()
    {
        $ringkasan = $this->getRingkasan();

        $ringkasan->periode = 'September 2025';
        $ringkasan->header_waktu = 'Selasa, 2 September 2025 | 10:24 WIT';
        $ringkasan->live_status = 'Live System | Periode: September 2025';
        $ringkasan->slogan_header = 'LAYANAN OPERASIONAL • SARANA PRASARANA • ASET • KEAMANAN • KENYAMANAN';
        $ringkasan->slogan_sub = '"Lingkungan Kerja yang Aman, Nyaman dan Produktif untuk Siaran yang Lebih Bermakna"';
        $ringkasan->slogan_motto = 'Maluku Bisa Lebih Maju!';

        $ringkasan->total_aset = 1245;
        $ringkasan->diff_aset = '+3% dari bulan lalu';
        $ringkasan->kendaraan_total = 28;
        $ringkasan->kendaraan_siap = 26;
        $ringkasan->kendaraan_siap_pct = 92;
        $ringkasan->diff_kendaraan = '+4%';
        $ringkasan->kendaraan_perbaikan = 2;
        $ringkasan->kendaraan_tidak_operasi = 0;

        $ringkasan->sarpras_total = 56;
        $ringkasan->sarpras_baik_pct = 89;
        $ringkasan->diff_sarpras = '+5%';

        $ringkasan->permintaan_total = 42;
        $ringkasan->permintaan_selesai = 37;
        $ringkasan->permintaan_selesai_pct = 88;
        $ringkasan->diff_permintaan = '+12%';
        $ringkasan->permintaan_proses = 3;
        $ringkasan->permintaan_ditunda = 1;
        $ringkasan->permintaan_dibatalkan = 1;

        $ringkasan->insiden_keamanan = 2;
        $ringkasan->diff_insiden = '-50% dari bulan lalu';
        $ringkasan->indeks_kenyamanan = 92;
        $ringkasan->target_kenyamanan = 90;
        $ringkasan->operational_readiness = 94;
        $ringkasan->target_readiness = 90;

        $ringkasan->aset_baik = 892;
        $ringkasan->aset_rusak_ringan = 178;
        $ringkasan->aset_rusak_berat = 56;
        $ringkasan->aset_dalam_perbaikan = 78;
        $ringkasan->aset_tidak_aktif = 41;

        $ringkasan->anggaran_pagu = 2450000000;
        $ringkasan->anggaran_terpakai = 1837500000;
        $ringkasan->anggaran_sisa = 612500000;
        $ringkasan->anggaran_persen = 75;

        $ringkasan->personel_security = 12;
        $ringkasan->pos_keamanan = 3;
        $ringkasan->cctv_total = 28;
        $ringkasan->cctv_aktif = 26;
        $ringkasan->cctv_perbaikan = 2;
        $ringkasan->apar_total = 32;
        $ringkasan->apar_terverifikasi = 32;

        $ringkasan->nilai_kebersihan_kantor = 94;
        $ringkasan->nilai_kebersihan_studio = 96;
        $ringkasan->nilai_kebersihan_halaman = 90;
        $ringkasan->nilai_kebersihan_toilet = 88;

        $ringkasan->save();

        return redirect()->route('tvri.umum.manage')->with('success', 'Data operasional Umum & Logistik berhasil disinkronkan ke default.');
    }
}
