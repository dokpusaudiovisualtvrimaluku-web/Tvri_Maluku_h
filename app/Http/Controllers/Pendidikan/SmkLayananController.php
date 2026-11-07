<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\SimandatSmkLayanan;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class SmkLayananController extends Controller
{
    /**
     * Display listing of Bidang Pembinaan SMK services and document applications.
     */
    public function index(Request $request)
    {
        $query = SimandatSmkLayanan::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('nama_pemohon', 'like', "%{$search}%")
                  ->orWhere('nomor_identitas', 'like', "%{$search}%")
                  ->orWhere('nama_sekolah_asal', 'like', "%{$search}%")
                  ->orWhere('konsentrasi_keahlian', 'like', "%{$search}%")
                  ->orWhere('nomor_surat_keluar', 'like', "%{$search}%")
                  ->orWhere('perihal_uraian', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_layanan') && $request->jenis_layanan !== 'all') {
            $query->where('jenis_layanan', $request->jenis_layanan);
        }

        if ($request->filled('status_layanan') && $request->status_layanan !== 'all') {
            $query->where('status_layanan', $request->status_layanan);
        }

        if ($request->filled('kabupaten_kota') && $request->kabupaten_kota !== 'all') {
            $query->where('kabupaten_kota', $request->kabupaten_kota);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_pengajuan', $request->tahun);
        }

        // Export to CSV if requested
        if ($request->get('export') === 'csv') {
            return $this->exportCsv($query->get());
        }

        $layananList = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Services counts for quick tab filtering
        $serviceCounts = [];
        foreach (array_keys(SimandatSmkLayanan::JENIS_LAYANAN_LIST) as $serviceName) {
            $serviceCounts[$serviceName] = SimandatSmkLayanan::where('jenis_layanan', $serviceName)->count();
        }

                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        $trend = [];
        foreach ($months as $idx => $m) {
            $monthNum = $idx + 1;
            $trend[] = SimandatSmkLayanan::whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $monthNum)
                ->count();
        }

        // Summary Metric Cards
        $totalLayanan = SimandatSmkLayanan::count();
        $totalProses = SimandatSmkLayanan::where('status_layanan', 'PROSES_VERIFIKASI')->count();
        $totalDisetujui = SimandatSmkLayanan::where('status_layanan', 'DISETUJUI')->count();
        $totalSelesai = SimandatSmkLayanan::where('status_layanan', 'SELESAI')->count();

        $allJenisLayanan = SimandatSmkLayanan::JENIS_LAYANAN_LIST;
        $statusOptions = SimandatSmkLayanan::STATUS_LIST;

        $kabupatenOptions = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        return view('pendidikan.smk.index', compact(
            'layananList', 'serviceCounts', 'totalLayanan', 'totalProses', 'totalDisetujui', 'totalSelesai',
            'allJenisLayanan', 'trend', 'statusOptions', 'kabupatenOptions'
        ));
    }

    /**
     * Show form to create new SMK service / document application.
     */
    public function create(Request $request)
    {
        $selectedService = $request->get('jenis', 'Rekomendasi Mutasi (Perpindahan) Siswa SMK');
        $allJenisLayanan = SimandatSmkLayanan::JENIS_LAYANAN_LIST;
        $statusOptions = SimandatSmkLayanan::STATUS_LIST;
        $konsentrasiList = SimandatSmkLayanan::KONSENTRASI_KEAHLIAN_POPULER;

        $kabupatenOptions = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        return view('pendidikan.smk.create', compact(
            'selectedService', 'allJenisLayanan', 'statusOptions', 'konsentrasiList', 'kabupatenOptions'
        ));
    }

    /**
     * Store new SMK service record in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_layanan' => 'required|string|in:' . implode(',', array_keys(SimandatSmkLayanan::JENIS_LAYANAN_LIST)),
            'nama_pemohon' => 'required|string|max:255',
            'nomor_identitas' => 'nullable|string|max:100',
            'nama_sekolah_asal' => 'required|string|max:255',
            'npsn_sekolah' => 'nullable|string|max:20',
            'konsentrasi_keahlian' => 'nullable|string|max:255',
            'kabupaten_kota' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_pengajuan',
            'nomor_surat_keluar' => 'nullable|string|max:255',
            'perihal_uraian' => 'required|string',
            'status_layanan' => 'required|string|in:' . implode(',', array_keys(SimandatSmkLayanan::STATUS_LIST)),
            'catatan_verifikator' => 'nullable|string',
            'petugas_verifikator' => 'nullable|string|max:255',
            'file_permohonan' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar|max:25600',
            'file_hasil_sk' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar|max:25600',
        ]);

        // Generate registration number
        $meta = SimandatSmkLayanan::JENIS_LAYANAN_LIST[$request->jenis_layanan] ?? ['prefix' => 'SMK-LAY'];
        $prefix = $meta['prefix'];
        $year = date('Y', strtotime($request->tanggal_pengajuan));
        $count = SimandatSmkLayanan::where('jenis_layanan', $request->jenis_layanan)->whereYear('tanggal_pengajuan', $year)->count() + 1;
        $nomorRegistrasi = sprintf('%s/%s/%04d/%s', $prefix, $year, $count, strtoupper(Str::random(4)));

        $filePermohonanPath = null;
        $filePermohonanName = null;
        $fileHasilSkPath = null;
        $fileHasilSkName = null;
        $fileSize = null;

        if ($request->hasFile('file_permohonan')) {
            $file = $request->file('file_permohonan');
            $filePermohonanName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileName = 'smk_req_' . time() . '_' . Str::slug(pathinfo($filePermohonanName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePermohonanPath = $file->storeAs('smk_documents', $fileName, 'public');
        }

        if ($request->hasFile('file_hasil_sk')) {
            $file = $request->file('file_hasil_sk');
            $fileHasilSkName = $file->getClientOriginalName();
            $fileName = 'smk_out_' . time() . '_' . Str::slug(pathinfo($fileHasilSkName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $fileHasilSkPath = $file->storeAs('smk_documents', $fileName, 'public');
        }

        $layanan = SimandatSmkLayanan::create([
            'nomor_registrasi' => $nomorRegistrasi,
            'jenis_layanan' => $request->jenis_layanan,
            'nama_pemohon' => $request->nama_pemohon,
            'nomor_identitas' => $request->nomor_identitas,
            'nama_sekolah_asal' => $request->nama_sekolah_asal,
            'npsn_sekolah' => $request->npsn_sekolah,
            'konsentrasi_keahlian' => $request->konsentrasi_keahlian,
            'kabupaten_kota' => $request->kabupaten_kota,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'tanggal_selesai' => $request->tanggal_selesai,
            'nomor_surat_keluar' => $request->nomor_surat_keluar,
            'perihal_uraian' => $request->perihal_uraian,
            'status_layanan' => $request->status_layanan,
            'file_permohonan' => $filePermohonanPath,
            'file_nama_permohonan' => $filePermohonanName,
            'file_hasil_sk' => $fileHasilSkPath,
            'file_nama_hasil_sk' => $fileHasilSkName,
            'file_size' => $fileSize,
            'catatan_verifikator' => $request->catatan_verifikator,
            'petugas_verifikator' => $request->petugas_verifikator,
            'user_id' => Auth::id(),
        ]);

        // SYNC 1: Dokumen Layanan
        if (isset($filePermohonanPath)) {
            \App\Models\SimandatDokumenLayanan::create([
                'kategori' => 'Kearsipan',
                'nama_dokumen' => 'Permohonan SMK: ' . $layanan->nomor_registrasi . ' - ' . $layanan->nama_pemohon,
                'keterangan' => 'Layanan SMK (' . $layanan->jenis_layanan . '). ' . $layanan->perihal_uraian,
                'file_path' => $filePermohonanPath,

                'file_type' => pathinfo($filePermohonanName ?? '', PATHINFO_EXTENSION) ?: 'pdf',
                'file_size' => $fileSize ?? 0,

                'user_id' => $layanan->user_id,
                'petugas_nama' => auth()->user()->name ?? 'Pemohon SMK',
                'status' => 'AKTIF',
            ]);

            // SYNC 2: Laporan
            \App\Models\SimandatLaporan::create([
                'nomor_laporan' => $layanan->nomor_registrasi,
                'kategori_laporan' => 'Pelayanan Publik',
                'judul_laporan' => 'Permohonan SMK: ' . $layanan->nama_pemohon,
                'tanggal_laporan' => $layanan->tanggal_pengajuan,
                'pembuat_laporan' => $layanan->nama_pemohon,
                'file_dokumen' => $filePermohonanPath,
                'user_id' => $layanan->user_id,
            ]);

            // SYNC 3: Pangkalan Dokumen
            \App\Models\SimandatDocument::create([
                'document_number' => $layanan->nomor_registrasi,
                'title' => 'Berkas SMK: ' . $layanan->nama_pemohon,
                'category' => 'Lainnya',
                'division' => 'Bidang Pembinaan SMK',
                'description' => 'Berkas persyaratan permohonan layanan SMK.',
                'file_path' => $filePermohonanPath,
                'file_size' => $fileSize ?? 0,

                'status' => 'AKTIF',
                'uploaded_by' => $layanan->user_id,
            ]);
        }

        ActivityLogger::log(
              'create_smk_layanan',
            "Mendaftarkan permohonan layanan SMK [{$layanan->nomor_registrasi}] '{$layanan->jenis_layanan}' a.n {$layanan->nama_pemohon}"
        );

        return redirect()->route('pendidikan.smk.layanan.show', $layanan->id)
            ->with('success', "Permohonan Layanan SMK [{$layanan->nomor_registrasi}] berhasil didaftarkan.");
    }

    /**
     * Show detail view of SMK service application.
     */
    public function show($id)
    {
        $layanan = SimandatSmkLayanan::with('user')->findOrFail($id);
        $allJenisLayanan = SimandatSmkLayanan::JENIS_LAYANAN_LIST;
        $statusOptions = SimandatSmkLayanan::STATUS_LIST;

        return view('pendidikan.smk.show', compact('layanan', 'allJenisLayanan', 'statusOptions'));
    }

    /**
     * Show form to edit SMK service record.
     */
    public function edit($id)
    {
        $layanan = SimandatSmkLayanan::findOrFail($id);
        $allJenisLayanan = SimandatSmkLayanan::JENIS_LAYANAN_LIST;
        $statusOptions = SimandatSmkLayanan::STATUS_LIST;
        $konsentrasiList = SimandatSmkLayanan::KONSENTRASI_KEAHLIAN_POPULER;

        $kabupatenOptions = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        return view('pendidikan.smk.edit', compact(
            'layanan', 'allJenisLayanan', 'statusOptions', 'konsentrasiList', 'kabupatenOptions'
        ));
    }

    /**
     * Update existing SMK service record.
     */
    public function update(Request $request, $id)
    {
        $layanan = SimandatSmkLayanan::findOrFail($id);

        $request->validate([
            'jenis_layanan' => 'required|string|in:' . implode(',', array_keys(SimandatSmkLayanan::JENIS_LAYANAN_LIST)),
            'nama_pemohon' => 'required|string|max:255',
            'nomor_identitas' => 'nullable|string|max:100',
            'nama_sekolah_asal' => 'required|string|max:255',
            'npsn_sekolah' => 'nullable|string|max:20',
            'konsentrasi_keahlian' => 'nullable|string|max:255',
            'kabupaten_kota' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_pengajuan',
            'nomor_surat_keluar' => 'nullable|string|max:255',
            'perihal_uraian' => 'required|string',
            'status_layanan' => 'required|string|in:' . implode(',', array_keys(SimandatSmkLayanan::STATUS_LIST)),
            'catatan_verifikator' => 'nullable|string',
            'petugas_verifikator' => 'nullable|string|max:255',
            'file_permohonan' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar|max:25600',
            'file_hasil_sk' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar|max:25600',
        ]);

        $filePermohonanPath = $layanan->file_permohonan;
        $filePermohonanName = $layanan->file_nama_permohonan;
        $fileHasilSkPath = $layanan->file_hasil_sk;
        $fileHasilSkName = $layanan->file_nama_hasil_sk;
        $fileSize = $layanan->file_size;

        if ($request->hasFile('file_permohonan')) {
            if ($layanan->file_permohonan && Storage::disk('public')->exists($layanan->file_permohonan)) {
                Storage::disk('public')->delete($layanan->file_permohonan);
            }
            $file = $request->file('file_permohonan');
            $filePermohonanName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileName = 'smk_req_' . time() . '_' . Str::slug(pathinfo($filePermohonanName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePermohonanPath = $file->storeAs('smk_documents', $fileName, 'public');
        }

        if ($request->hasFile('file_hasil_sk')) {
            if ($layanan->file_hasil_sk && Storage::disk('public')->exists($layanan->file_hasil_sk)) {
                Storage::disk('public')->delete($layanan->file_hasil_sk);
            }
            $file = $request->file('file_hasil_sk');
            $fileHasilSkName = $file->getClientOriginalName();
            $fileName = 'smk_out_' . time() . '_' . Str::slug(pathinfo($fileHasilSkName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $fileHasilSkPath = $file->storeAs('smk_documents', $fileName, 'public');
        }

        $layanan->update([
            'jenis_layanan' => $request->jenis_layanan,
            'nama_pemohon' => $request->nama_pemohon,
            'nomor_identitas' => $request->nomor_identitas,
            'nama_sekolah_asal' => $request->nama_sekolah_asal,
            'npsn_sekolah' => $request->npsn_sekolah,
            'konsentrasi_keahlian' => $request->konsentrasi_keahlian,
            'kabupaten_kota' => $request->kabupaten_kota,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'tanggal_selesai' => $request->tanggal_selesai,
            'nomor_surat_keluar' => $request->nomor_surat_keluar,
            'perihal_uraian' => $request->perihal_uraian,
            'status_layanan' => $request->status_layanan,
            'file_permohonan' => $filePermohonanPath,
            'file_nama_permohonan' => $filePermohonanName,
            'file_hasil_sk' => $fileHasilSkPath,
            'file_nama_hasil_sk' => $fileHasilSkName,
            'file_size' => $fileSize,
            'catatan_verifikator' => $request->catatan_verifikator,
            'petugas_verifikator' => $request->petugas_verifikator,
        ]);

        // SYNC SK / Hasil Rekomendasi ke Repositori jika diunggah
        if ($request->hasFile('file_hasil_sk') && isset($fileHasilSkPath)) {
            \App\Models\SimandatDokumenLayanan::create([
                'kategori' => 'Surat Keluar',
                'nama_dokumen' => 'SK/Rekomendasi SMK: ' . $layanan->nomor_registrasi,
                'keterangan' => 'Surat Terbit (SK) untuk layanan SMK: ' . $layanan->nama_pemohon,
                'file_path' => $fileHasilSkPath,

                'file_type' => pathinfo($fileHasilSkName ?? '', PATHINFO_EXTENSION) ?: 'pdf',
                'file_size' => $fileSize ?? 0,

                'user_id' => auth()->id() ?? 1,
                'petugas_nama' => auth()->user()->name ?? 'Kepala Dinas',
                'status' => 'AKTIF',
            ]);

            \App\Models\SimandatDocument::create([
                'document_number' => $layanan->nomor_surat_keluar ?? $layanan->nomor_registrasi,
                'title' => 'SK/Rekomendasi SMK: ' . $layanan->nama_pemohon,
                'category' => 'Surat Keputusan (SK)',
                'division' => 'Bidang Pembinaan SMK',
                'description' => 'SK / Surat Rekomendasi resmi hasil verifikasi layanan SMK.',
                'file_path' => $fileHasilSkPath,
                'file_size' => $fileSize ?? 0,

                'status' => 'AKTIF',
                'uploaded_by' => auth()->id() ?? 1,
            ]);

            \App\Models\SimandatLaporan::create([
                'nomor_laporan' => $layanan->nomor_surat_keluar ?? $layanan->nomor_registrasi,
                'kategori_laporan' => 'Kinerja Bidang',
                'judul_laporan' => 'SK/Rekomendasi SMK: ' . $layanan->nama_pemohon,
                'tanggal_laporan' => date('Y-m-d'),
                'pembuat_laporan' => 'Kepala Dinas / Verifikator',
                'file_dokumen' => $fileHasilSkPath,
                'user_id' => auth()->id() ?? 1,
            ]);
        }

        ActivityLogger::log(
              'update_smk_layanan',
            "Memperbarui data permohonan layanan SMK [{$layanan->nomor_registrasi}] '{$layanan->nama_pemohon}'"
        );

        return redirect()->route('pendidikan.smk.layanan.show', $layanan->id)
            ->with('success', "Permohonan Layanan SMK [{$layanan->nomor_registrasi}] berhasil diperbarui.");
    }

    /**
     * Delete SMK service record.
     */
    public function destroy($id)
    {
        $layanan = SimandatSmkLayanan::findOrFail($id);
        $nomor = $layanan->nomor_registrasi;
        $pemohon = $layanan->nama_pemohon;

        $layanan->delete();

        ActivityLogger::log(
            'delete_smk_layanan',
            "Menghapus data permohonan layanan SMK [{$nomor}] a.n {$pemohon}"
        );

        return redirect()->route('pendidikan.smk.layanan.index')
            ->with('success', "Permohonan layanan SMK [{$nomor}] a.n {$pemohon} berhasil dihapus.");
    }

    /**
     * Download attached file (permohonan or hasil SK).
     */
    public function downloadFile($id, $type)
    {
        $layanan = SimandatSmkLayanan::findOrFail($id);

        if ($type === 'permohonan') {
            $path = $layanan->file_permohonan;
            $name = $layanan->file_nama_permohonan ?? 'berkas_syarat_smk.pdf';
        } elseif ($type === 'hasil_sk') {
            $path = $layanan->file_hasil_sk;
            $name = $layanan->file_nama_hasil_sk ?? 'dokumen_hasil_smk.pdf';
        } else {
            abort(404);
        }

        if (!$path || !Storage::disk('public')->exists($path)) {
            return back()->with('error', 'Berkas fisik tidak ditemukan di server.');
        }

        ActivityLogger::log(
            'download_smk_file',
            "Mengunduh berkas [{$type}] untuk permohonan SMK [{$layanan->nomor_registrasi}]"
        );

        return Storage::disk('public')->download($path, $name);
    }

    /**
     * Export SMK services to CSV.
     */
    protected function exportCsv($items)
    {
        $filename = 'layanan_smk_disdikbud_maluku_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($file, [
                'No Registrasi', 'Jenis Layanan SMK', 'Nama Pemohon', 'No Identitas (NISN/NIK/NPSN)',
                'Nama Sekolah Asal', 'NPSN', 'Konsentrasi Keahlian (Jurusan)', 'Kabupaten/Kota',
                'Tanggal Pengajuan', 'Tanggal Selesai', 'Nomor Surat/SK', 'Perihal & Deskripsi',
                'Status Layanan', 'Petugas Verifikator', 'Catatan Verifikator'
            ]);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->nomor_registrasi,
                    $item->jenis_layanan,
                    $item->nama_pemohon,
                    $item->nomor_identitas ?? '-',
                    $item->nama_sekolah_asal,
                    $item->npsn_sekolah ?? '-',
                    $item->konsentrasi_keahlian ?? '-',
                    $item->kabupaten_kota,
                    $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('Y-m-d') : '-',
                    $item->tanggal_selesai ? $item->tanggal_selesai->format('Y-m-d') : '-',
                    $item->nomor_surat_keluar ?? '-',
                    $item->perihal_uraian,
                    $item->status_layanan,
                    $item->petugas_verifikator ?? '-',
                    $item->catatan_verifikator ?? '-',
                ]);
            }
            fclose($file);
        };

        ActivityLogger::log('export_smk_csv', 'Mengekspor data Layanan SMK ke CSV');

        return Response::stream($callback, 200, $headers);
    }
}






