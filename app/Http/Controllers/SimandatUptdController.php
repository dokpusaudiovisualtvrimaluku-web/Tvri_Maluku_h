<?php

namespace App\Http\Controllers;

use App\Models\SimandatUptdBtkp;
use App\Models\SimandatUptdTamanBudaya;
use App\Models\SimandatUptdMuseum;
use Illuminate\Http\Request;

class SimandatUptdController extends Controller
{
    /**
     * Get all items for a given UPTD type (btkp, taman-budaya, museum)
     */
    public function index(Request $request, $type)
    {
        switch ($type) {
            case 'btkp':
                if (SimandatUptdBtkp::count() === 0) $this->seedBtkp();
                $data = SimandatUptdBtkp::orderBy('id', 'desc')->get();
                break;
            case 'taman-budaya':
                if (SimandatUptdTamanBudaya::count() === 0) $this->seedTamanBudaya();
                $data = SimandatUptdTamanBudaya::orderBy('id', 'desc')->get();
                break;
            case 'museum':
                if (SimandatUptdMuseum::count() === 0) $this->seedMuseum();
                $data = SimandatUptdMuseum::orderBy('id', 'desc')->get();
                break;
            default:
                return response()->json(['status' => 'error', 'message' => 'Tipe UPTD tidak valid'], 400);
        }

        return response()->json([
            'status' => 'success',
            'type' => $type,
            'data' => $data,
            'total' => $data->count(),
        ]);
    }

    /**
     * Store a new item for a given UPTD type
     */
    public function store(Request $request, $type)
    {
        switch ($type) {
            case 'btkp':
                $validated = $request->validate([
                    'kode' => 'required|string|max:50',
                    'nama_program' => 'required|string|max:255',
                    'penanggung_jawab' => 'nullable|string|max:255',
                    'sasaran' => 'nullable|string|max:255',
                    'platform' => 'nullable|string|max:255',
                    'anggaran' => 'nullable|numeric',
                    'status' => 'nullable|string|max:50',
                    'deskripsi' => 'nullable|string',
                ]);
                $item = SimandatUptdBtkp::updateOrCreate(['kode' => $validated['kode']], $validated);
                break;

            case 'taman-budaya':
                $validated = $request->validate([
                    'kode' => 'required|string|max:50',
                    'nama_kegiatan' => 'required|string|max:255',
                    'jenis' => 'nullable|string|max:100',
                    'lokasi' => 'nullable|string|max:255',
                    'sanggar' => 'nullable|string|max:255',
                    'peserta' => 'nullable|integer',
                    'status' => 'nullable|string|max:50',
                    'deskripsi' => 'nullable|string',
                ]);
                $item = SimandatUptdTamanBudaya::updateOrCreate(['kode' => $validated['kode']], $validated);
                break;

            case 'museum':
                $validated = $request->validate([
                    'no_inventaris' => 'required|string|max:50',
                    'nama_koleksi' => 'required|string|max:255',
                    'kategori' => 'nullable|string|max:100',
                    'asal' => 'nullable|string|max:255',
                    'bahan' => 'nullable|string|max:255',
                    'kondisi' => 'nullable|string|max:100',
                    'tahun_perolehan' => 'nullable|string|max:50',
                    'lokasi_simpan' => 'nullable|string|max:255',
                    'deskripsi' => 'nullable|string',
                ]);
                $item = SimandatUptdMuseum::updateOrCreate(['no_inventaris' => $validated['no_inventaris']], $validated);
                break;

            default:
                return response()->json(['status' => 'error', 'message' => 'Tipe UPTD tidak valid'], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data UPTD berhasil disimpan ke Database Pangkalan Data Disdikbud Maluku.',
            'data' => $item,
        ], 201);
    }

    /**
     * Update an existing UPTD item
     */
    public function update(Request $request, $type, $id)
    {
        switch ($type) {
            case 'btkp':
                $item = SimandatUptdBtkp::find($id) ?: SimandatUptdBtkp::where('kode', $id)->firstOrFail();
                $item->update($request->all());
                break;
            case 'taman-budaya':
                $item = SimandatUptdTamanBudaya::find($id) ?: SimandatUptdTamanBudaya::where('kode', $id)->firstOrFail();
                $item->update($request->all());
                break;
            case 'museum':
                $item = SimandatUptdMuseum::find($id) ?: SimandatUptdMuseum::where('no_inventaris', $id)->firstOrFail();
                $item->update($request->all());
                break;
            default:
                return response()->json(['status' => 'error', 'message' => 'Tipe UPTD tidak valid'], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data UPTD berhasil diperbarui di Database MySQL.',
            'data' => $item,
        ]);
    }

    /**
     * Delete an item from UPTD
     */
    public function destroy(Request $request, $type, $id)
    {
        switch ($type) {
            case 'btkp':
                $item = SimandatUptdBtkp::find($id) ?: SimandatUptdBtkp::where('kode', $id)->first();
                break;
            case 'taman-budaya':
                $item = SimandatUptdTamanBudaya::find($id) ?: SimandatUptdTamanBudaya::where('kode', $id)->first();
                break;
            case 'museum':
                $item = SimandatUptdMuseum::find($id) ?: SimandatUptdMuseum::where('no_inventaris', $id)->first();
                break;
            default:
                return response()->json(['status' => 'error', 'message' => 'Tipe UPTD tidak valid'], 400);
        }

        if ($item) {
            $item->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data UPTD berhasil dihapus (Soft-Deleted).',
            'id' => $id,
        ]);
    }

    private function seedBtkp()
    {
        $data = [
            ['kode' => 'BTKP-001', 'nama_program' => 'Digitalisasi Konten Pembelajaran & E-Modul Maluku', 'penanggung_jawab' => 'Drs. Ronald Pattipeilohy', 'sasaran' => 'SMA/SMK se-Maluku (11 Kab/Kota)', 'platform' => 'SIWALIMA LMS / Cloud', 'status' => 'Aktif', 'anggaran' => 185000000],
            ['kode' => 'BTKP-002', 'nama_program' => 'Studio Podcast & Produksi Video Edukasi Maluku Cerdas', 'penanggung_jawab' => 'Merry Souhoka, S.Kom', 'sasaran' => 'Guru & Peserta Didik Wilayah 3T', 'platform' => 'YouTube / Portal Disdikbud', 'status' => 'Aktif', 'anggaran' => 120000000],
            ['kode' => 'BTKP-003', 'nama_program' => 'Penguatan Infrastruktur Jaringan & Server CBT Sekolah Terluar', 'penanggung_jawab' => 'Hendrik Tahitu, ST', 'sasaran' => '35 Titik Sekolah Daerah 3T (Kep. Aru & MBD)', 'platform' => 'VSAT & Server Lokal', 'status' => 'Optimal', 'anggaran' => 240000000],
            ['kode' => 'BTKP-004', 'nama_program' => 'Bimtek Pemanfaatan AI & Media Pembelajaran Interaktif GTK', 'penanggung_jawab' => 'Grace Nanlohy, M.Pd', 'sasaran' => '450 Guru Jenjang SMA/SMK/SLB', 'platform' => 'Zoom & Workshop Hybrid', 'status' => 'Aktif', 'anggaran' => 95000000],
        ];
        foreach ($data as $d) SimandatUptdBtkp::create($d);
    }

    private function seedTamanBudaya()
    {
        $data = [
            ['kode' => 'TB-MAL-01', 'nama_kegiatan' => 'Festival Gelar Seni Budaya Kepulauan Siwalima 2026', 'jenis' => 'Festival & Pagelaran', 'lokasi' => 'Panggung Terbuka Taman Budaya Karpan Ambon', 'sanggar' => 'Sanggar Siwalima, Ina Boiratan, Saureka-reka (18 Sanggar)', 'status' => 'Terjadwal', 'peserta' => 650],
            ['kode' => 'TB-MAL-02', 'nama_kegiatan' => 'Workshop Regenerasi Musik Tradisi Tabuh Totobuang & Tifa', 'jenis' => 'Workshop & Pelatihan', 'lokasi' => 'Gedung Kesenian Tertutup Taman Budaya', 'sanggar' => 'Komunitas Musik Etnis Maluku & Pelajar', 'status' => 'Aktif', 'peserta' => 120],
            ['kode' => 'TB-MAL-03', 'nama_kegiatan' => 'Pameran Seni Rupa & Kriya Etnik Bahari Maluku', 'jenis' => 'Pameran Seni', 'lokasi' => 'Galeri Pameran Seni Utama', 'sanggar' => 'Perupa Maluku & Pengrajin Tenun Ikat Tanimbar', 'status' => 'Persiapan', 'peserta' => 250],
            ['kode' => 'TB-MAL-04', 'nama_kegiatan' => 'Pentas Kolaborasi Teater Tradisi Cerita Rakyat Maluku', 'jenis' => 'Pentas Teater', 'lokasi' => 'Auditorium Budaya', 'sanggar' => 'Teater Remaja Maluku & Sanggar Teratai Putih', 'status' => 'Terjadwal', 'peserta' => 300],
        ];
        foreach ($data as $d) SimandatUptdTamanBudaya::create($d);
    }

    private function seedMuseum()
    {
        $data = [
            ['no_inventaris' => 'MSL-K-001', 'nama_koleksi' => 'Busana Adat Kebesaran Kapitan & Tenun Ikat Maluku Kuno', 'kategori' => 'Etnografika', 'bahan' => 'Kain Tenun Serat Kulit Kayu & Benang Emas', 'asal' => 'Kepulauan Kei & Tanimbar', 'tahun_perolehan' => '1978', 'kondisi' => 'Sangat Baik', 'lokasi_simpan' => 'Gedung A Ruang Busana Tradisional'],
            ['no_inventaris' => 'MSL-K-002', 'nama_koleksi' => 'Meriam VOC Perunggu Abad Ke-17 & Senjata Tradisional Parang Salawaku', 'kategori' => 'Historika', 'bahan' => 'Perunggu & Kayu Besi Terukir', 'asal' => 'Kota Ambon & Banda Neira', 'tahun_perolehan' => '1982', 'kondisi' => 'Terawat', 'lokasi_simpan' => 'Gedung B Ruang Sejarah Rempah'],
            ['no_inventaris' => 'MSL-K-003', 'nama_koleksi' => 'Fosil Biota Laut Maluku & Kerangka Paus Raksasa Siwalima', 'kategori' => 'Biologika', 'bahan' => 'Kerangka Tulang Alami', 'asal' => 'Laut Banda & Perairan Maluku Tenggara', 'tahun_perolehan' => '1986', 'kondisi' => 'Utuh / Terpelihara', 'lokasi_simpan' => 'Gedung Kelautan & Biologi'],
            ['no_inventaris' => 'MSL-K-004', 'nama_koleksi' => 'Naskah Kuno Daun Lontar & Alkitab Kuno Terjemahan Leijdecker', 'kategori' => 'Filologika', 'bahan' => 'Kertas Kuno Kering & Tinta Alami', 'asal' => 'Negeri Saparua & Hila Leihitu', 'tahun_perolehan' => '1990', 'kondisi' => 'Sangat Baik (Ruang Kedap)', 'lokasi_simpan' => 'Ruang Koleksi Khusus Filologi'],
        ];
        foreach ($data as $d) SimandatUptdMuseum::create($d);
    }
}
