<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\SimandatGtkLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class GtkLayananController extends Controller
{
    public function index(Request $request)
    {
        $query = SimandatGtkLayanan::with('user');

        if (auth()->check()) {
            $user = auth()->user();
            $isKabidGTK = $user->username === 'kabid.ketenagaan';
            $isKadis = $user->hasRole('KEPALA_DINAS') || str_contains(strtolower($user->name), 'kepala dinas');
            $isKabidKebudayaan = $user->username === 'kabid.kebudayaan';
            
            if (!$isKabidGTK && !$isKadis && !$isKabidKebudayaan) {
                $query->where('user_id', $user->id);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap_guru', 'like', "%{$search}%")
                  ->orWhere('nuptk', 'like', "%{$search}%")
                  ->orWhere('nip_nik', 'like', "%{$search}%")
                  ->orWhere('tempat_tugas_sekolah', 'like', "%{$search}%")
                  ->orWhere('nomor_sk_rekomendasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_layanan')) {
            $query->where('jenis_layanan', $request->jenis_layanan);
        }

        if ($request->filled('status_layanan')) {
            $query->where('status_layanan', $request->status_layanan);
        }

        if ($request->filled('kabupaten_kota')) {
            $query->where('kabupaten_kota', $request->kabupaten_kota);
        }

        if ($request->filled('status_kepegawaian')) {
            $query->where('status_kepegawaian', $request->status_kepegawaian);
        }

        $layananList = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Stats dynamically from DB
        $stats = [
            'total' => SimandatGtkLayanan::count(),
            'sertifikasi' => SimandatGtkLayanan::where('jenis_layanan', 'Sertifikasi Pendidik')->count(),
            'nuptk' => SimandatGtkLayanan::where('jenis_layanan', 'NUPTK')->count(),
            'tunjangan' => SimandatGtkLayanan::where('jenis_layanan', 'Tunjangan Pendidik')->count(),
            'simpkb' => SimandatGtkLayanan::where('jenis_layanan', 'SIMPKB / PKB')->count(),
            'selesai' => SimandatGtkLayanan::where('status_layanan', 'SELESAI')->count(),
            'proses' => SimandatGtkLayanan::whereIn('status_layanan', ['DRAFT', 'PROSES_VERIFIKASI', 'DISETUJUI'])->count(),
        ];

        $jenisLayananOptions = SimandatGtkLayanan::JENIS_LAYANAN_LIST;
        $statusOptions = SimandatGtkLayanan::STATUS_LIST;
        $statusKepegawaianOptions = SimandatGtkLayanan::STATUS_KEPEGAWAIAN_LIST;

        $kabupatenKotaList = [
            'Kota Ambon',
            'Maluku Tengah',
            'Seram Bagian Barat',
            'Seram Bagian Timur',
            'Buru',
            'Buru Selatan',
            'Kepulauan Aru',
            'Maluku Tenggara',
            'Kepulauan Tanimbar',
            'Maluku Barat Daya',
            'Kota Tual',
        ];

        return view('pendidikan.gtk.index', compact(
            'layananList',
            'stats',
            'jenisLayananOptions',
            'statusOptions',
            'statusKepegawaianOptions',
            'kabupatenKotaList'
        ));
    }

    public function create(Request $request)
    {
        $selectedJenis = $request->query('jenis', 'Sertifikasi Pendidik');
        $jenisLayananOptions = SimandatGtkLayanan::JENIS_LAYANAN_LIST;
        $statusOptions = SimandatGtkLayanan::STATUS_LIST;
        $statusKepegawaianOptions = SimandatGtkLayanan::STATUS_KEPEGAWAIAN_LIST;

        $kabupatenKotaList = [
            'Kota Ambon',
            'Maluku Tengah',
            'Seram Bagian Barat',
            'Seram Bagian Timur',
            'Buru',
            'Buru Selatan',
            'Kepulauan Aru',
            'Maluku Tenggara',
            'Kepulauan Tanimbar',
            'Maluku Barat Daya',
            'Kota Tual',
        ];

        $jenjangList = ['PAUD/TK', 'SD', 'SMP', 'SMA', 'SMK', 'SLB'];

        return view('pendidikan.gtk.create', compact(
            'selectedJenis',
            'jenisLayananOptions',
            'statusOptions',
            'statusKepegawaianOptions',
            'kabupatenKotaList',
            'jenjangList'
        ));
    }

    public function store(Request $request)
    {


        $validated = $request->validate([
            'jenis_layanan' => 'required|in:' . implode(',', array_keys(SimandatGtkLayanan::JENIS_LAYANAN_LIST)),
            'nama_lengkap_guru' => 'required|string|max:255',
            'nuptk' => 'nullable|string|max:30',
            'nip_nik' => 'nullable|string|max:30',
            'tempat_tugas_sekolah' => 'required|string|max:255',
            'npsn_sekolah' => 'nullable|string|max:20',
            'jenjang_sekolah' => 'required|string|max:50',
            'status_kepegawaian' => 'required|in:' . implode(',', array_keys(SimandatGtkLayanan::STATUS_KEPEGAWAIAN_LIST)),
            'kabupaten_kota' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'nomor_sk_rekomendasi' => 'nullable|string|max:255',
            'perihal_uraian' => 'required|string',
            'status_layanan' => 'required|in:' . implode(',', array_keys(SimandatGtkLayanan::STATUS_LIST)),
            'catatan_verifikator' => 'nullable|string',
            'petugas_verifikator' => 'nullable|string|max:255',
            'file_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240',
            'file_hasil_sk' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240',
        ]);

        $validated['nomor_registrasi'] = SimandatGtkLayanan::generateNomorRegistrasi($validated['jenis_layanan']);
        $validated['user_id'] = auth()->id();

        if ($request->hasFile('file_permohonan')) {
            $file = $request->file('file_permohonan');
            $path = $file->store('gtk_dokumen/permohonan', 'public');
            $validated['file_permohonan'] = $path;
            $validated['file_nama_permohonan'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
        }

        if ($request->hasFile('file_hasil_sk')) {
            $file = $request->file('file_hasil_sk');
            $path = $file->store('gtk_dokumen/hasil_sk', 'public');
            $validated['file_hasil_sk'] = $path;
            $validated['file_nama_hasil_sk'] = $file->getClientOriginalName();
        }

                $layanan = SimandatGtkLayanan::create($validated);

        // SYNC 1: Dokumen Layanan (Dasbor Arsip)
        if (isset($validated['file_permohonan'])) {
            \App\Models\SimandatDokumenLayanan::create([
                'kategori' => 'Kearsipan',
                'nama_dokumen' => 'Permohonan Kebudayaan: ' . $layanan->nomor_registrasi . ' - ' . $layanan->nama_lengkap_guru,
                'keterangan' => 'Layanan Kebudayaan (' . $layanan->jenis_layanan . '). ' . $layanan->perihal_uraian,
                'file_path' => $validated['file_permohonan'],
                'file_type' => pathinfo($validated['file_nama_permohonan'] ?? '', PATHINFO_EXTENSION) ?: 'pdf',
                'file_size' => $validated['file_size'] ?? 0,
                'user_id' => $layanan->user_id,
                'petugas_nama' => auth()->user()->name ?? 'Pemohon Kebudayaan',
                'status' => 'AKTIF',
            ]);
            
            // SYNC 2: Laporan
            \App\Models\SimandatLaporan::create([
                'nomor_laporan' => $layanan->nomor_registrasi,
                'kategori_laporan' => 'Pelayanan Publik',
                'judul_laporan' => 'Permohonan Kebudayaan: ' . $layanan->nama_lengkap_guru,
                'tanggal_laporan' => $layanan->tanggal_pengajuan,
                'pembuat_laporan' => $layanan->nama_lengkap_guru,
                'file_dokumen' => $validated['file_permohonan'],
                'user_id' => $layanan->user_id,
            ]);

            // SYNC 3: Pangkalan Dokumen
            \App\Models\SimandatDocument::create([
                'document_number' => $layanan->nomor_registrasi,
                'title' => 'Berkas Kebudayaan: ' . $layanan->nama_lengkap_guru,
                'category' => 'Lainnya',
                'division' => 'Bidang Kebudayaan',
                'description' => 'Berkas persyaratan permohonan layanan kebudayaan.',
                'file_path' => $validated['file_permohonan'],
                'file_size' => $validated['file_size'] ?? 0,
                'status' => 'AKTIF',
                'uploaded_by' => $layanan->user_id,
            ]);
        }

        return redirect()->route('pendidikan.gtk.layanan.show', $layanan->id)
            ->with('success', 'Dokumen Permohonan Layanan GTK (' . $layanan->nomor_registrasi . ') berhasil didaftarkan.');
    }

    public function show($id)
    {
        $layanan = SimandatGtkLayanan::with('user')->findOrFail($id);
        return view('pendidikan.gtk.show', compact('layanan'));
    }

    public function edit($id)
    {
        $layanan = SimandatGtkLayanan::findOrFail($id);
        $jenisLayananOptions = SimandatGtkLayanan::JENIS_LAYANAN_LIST;
        $statusOptions = SimandatGtkLayanan::STATUS_LIST;
        $statusKepegawaianOptions = SimandatGtkLayanan::STATUS_KEPEGAWAIAN_LIST;

        $kabupatenKotaList = [
            'Kota Ambon',
            'Maluku Tengah',
            'Seram Bagian Barat',
            'Seram Bagian Timur',
            'Buru',
            'Buru Selatan',
            'Kepulauan Aru',
            'Maluku Tenggara',
            'Kepulauan Tanimbar',
            'Maluku Barat Daya',
            'Kota Tual',
        ];

        $jenjangList = ['PAUD/TK', 'SD', 'SMP', 'SMA', 'SMK', 'SLB'];

        return view('pendidikan.gtk.edit', compact(
            'layanan',
            'jenisLayananOptions',
            'statusOptions',
            'statusKepegawaianOptions',
            'kabupatenKotaList',
            'jenjangList'
        ));
    }

    public function update(Request $request, $id)
    {
        $layanan = SimandatGtkLayanan::findOrFail($id);

        $validated = $request->validate([
            'jenis_layanan' => 'required|in:' . implode(',', array_keys(SimandatGtkLayanan::JENIS_LAYANAN_LIST)),
            'nama_lengkap_guru' => 'required|string|max:255',
            'nuptk' => 'nullable|string|max:30',
            'nip_nik' => 'nullable|string|max:30',
            'tempat_tugas_sekolah' => 'required|string|max:255',
            'npsn_sekolah' => 'nullable|string|max:20',
            'jenjang_sekolah' => 'required|string|max:50',
            'status_kepegawaian' => 'required|in:' . implode(',', array_keys(SimandatGtkLayanan::STATUS_KEPEGAWAIAN_LIST)),
            'kabupaten_kota' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'nomor_sk_rekomendasi' => 'nullable|string|max:255',
            'perihal_uraian' => 'required|string',
            'status_layanan' => 'required|in:' . implode(',', array_keys(SimandatGtkLayanan::STATUS_LIST)),
            'catatan_verifikator' => 'nullable|string',
            'petugas_verifikator' => 'nullable|string|max:255',
            'file_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240',
            'file_hasil_sk' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240',
        ]);

        if ($request->hasFile('file_permohonan')) {
            if ($layanan->file_permohonan && Storage::disk('public')->exists($layanan->file_permohonan)) {
                Storage::disk('public')->delete($layanan->file_permohonan);
            }
            $file = $request->file('file_permohonan');
            $validated['file_permohonan'] = $file->store('gtk_dokumen/permohonan', 'public');
            $validated['file_nama_permohonan'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
        }

        if ($request->hasFile('file_hasil_sk')) {
            if ($layanan->file_hasil_sk && Storage::disk('public')->exists($layanan->file_hasil_sk)) {
                Storage::disk('public')->delete($layanan->file_hasil_sk);
            }
            $file = $request->file('file_hasil_sk');
            $validated['file_hasil_sk'] = $file->store('gtk_dokumen/hasil_sk', 'public');
            $validated['file_nama_hasil_sk'] = $file->getClientOriginalName();
        }

                $layanan->update($validated);

        // SYNC SK / Hasil Rekomendasi ke Repositori & Kearsipan jika diunggah
        if ($request->hasFile('file_hasil_sk') && isset($validated['file_hasil_sk'])) {
            \App\Models\SimandatDokumenLayanan::create([
                'kategori' => 'Surat Keluar',
                'nama_dokumen' => 'SK/Rekomendasi Kebudayaan: ' . $layanan->nomor_registrasi,
                'keterangan' => 'Surat Terbit (SK) untuk layanan Kebudayaan (' . $layanan->jenis_layanan . '): ' . $layanan->nama_lengkap_guru,
                'file_path' => $validated['file_hasil_sk'],
                'file_type' => pathinfo($validated['file_nama_hasil_sk'] ?? '', PATHINFO_EXTENSION) ?: 'pdf',
                'file_size' => $validated['file_size'] ?? 0,
                'user_id' => auth()->id(),
                'petugas_nama' => auth()->user()->name ?? 'Kepala Dinas',
                'status' => 'AKTIF',
            ]);

            \App\Models\SimandatDocument::create([
                'document_number' => $layanan->nomor_sk_rekomendasi ?? $layanan->nomor_registrasi,
                'title' => 'SK/Rekomendasi Kebudayaan: ' . $layanan->nama_lengkap_guru,
                'category' => 'Surat Keputusan (SK)',
                'division' => 'Bidang Kebudayaan',
                'description' => 'SK / Surat Rekomendasi resmi hasil verifikasi layanan kebudayaan.',
                'file_path' => $validated['file_hasil_sk'],
                'file_size' => $validated['file_size'] ?? 0,
                'status' => 'AKTIF',
                'uploaded_by' => auth()->id(),
            ]);
            
            \App\Models\SimandatLaporan::create([
                'nomor_laporan' => $layanan->nomor_sk_rekomendasi ?? $layanan->nomor_registrasi,
                'kategori_laporan' => 'Kinerja Bidang',
                'judul_laporan' => 'SK/Rekomendasi Kebudayaan: ' . $layanan->nama_lengkap_guru,
                'tanggal_laporan' => date('Y-m-d'),
                'pembuat_laporan' => 'Kepala Dinas / Verifikator',
                'file_dokumen' => $validated['file_hasil_sk'],
                'user_id' => auth()->id(),
            ]);
        }

        return redirect()->route('pendidikan.gtk.layanan.show', $layanan->id)
            ->with('success', 'Data Layanan GTK (' . $layanan->nomor_registrasi . ') berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $layanan = SimandatGtkLayanan::findOrFail($id);

        if ($layanan->file_permohonan && Storage::disk('public')->exists($layanan->file_permohonan)) {
            Storage::disk('public')->delete($layanan->file_permohonan);
        }

        if ($layanan->file_hasil_sk && Storage::disk('public')->exists($layanan->file_hasil_sk)) {
            Storage::disk('public')->delete($layanan->file_hasil_sk);
        }

        $regNo = $layanan->nomor_registrasi;
        $layanan->delete();

        return redirect()->route('pendidikan.gtk.layanan.index')
            ->with('success', "Data layanan GTK {$regNo} berhasil dihapus.");
    }

    public function downloadFile($id, $type)
    {
        $layanan = SimandatGtkLayanan::findOrFail($id);
        $filePath = null;
        $customName = null;

        if ($type === 'permohonan' && $layanan->file_permohonan) {
            $filePath = $layanan->file_permohonan;
            $customName = $layanan->file_nama_permohonan ?? "Berkas_Permohonan_{$layanan->nomor_registrasi}.pdf";
        } elseif ($type === 'sk' && $layanan->file_hasil_sk) {
            $filePath = $layanan->file_hasil_sk;
            $customName = $layanan->file_nama_hasil_sk ?? "Dokumen_SK_Hasil_{$layanan->nomor_registrasi}.pdf";
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File dokumen tidak ditemukan atau belum diunggah.');
        }

        return Storage::disk('public')->download($filePath, $customName);
    }

    public function exportCsv(Request $request)
    {
        $query = SimandatGtkLayanan::query();

        if ($request->filled('jenis_layanan')) {
            $query->where('jenis_layanan', $request->jenis_layanan);
        }

        if ($request->filled('status_layanan')) {
            $query->where('status_layanan', $request->status_layanan);
        }

        if ($request->filled('kabupaten_kota')) {
            $query->where('kabupaten_kota', $request->kabupaten_kota);
        }

        $records = $query->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data-layanan-gtk-' . date('Ymd-His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($records) {
            $output = fopen('php://output', 'w');
            fputs($output, "\xEF\xBB\xBF"); // UTF-8 BOM

            fputcsv($output, [
                'No',
                'No Registrasi',
                'Jenis Layanan',
                'Nama Lengkap Guru',
                'NUPTK',
                'NIP/NIK',
                'Sekolah / Satker',
                'NPSN',
                'Jenjang',
                'Status Kepegawaian',
                'Kabupaten/Kota',
                'Tanggal Pengajuan',
                'Tanggal Selesai',
                'No SK / Rekomendasi',
                'Status Layanan',
                'Petugas Verifikator',
                'Catatan Verifikator',
            ]);

            foreach ($records as $index => $row) {
                fputcsv($output, [
                    $index + 1,
                    $row->nomor_registrasi,
                    $row->jenis_layanan,
                    $row->nama_lengkap_guru,
                    $row->nuptk ?? '-',
                    $row->nip_nik ?? '-',
                    $row->tempat_tugas_sekolah,
                    $row->npsn_sekolah ?? '-',
                    $row->jenjang_sekolah ?? '-',
                    $row->status_kepegawaian,
                    $row->kabupaten_kota,
                    $row->tanggal_pengajuan ? $row->tanggal_pengajuan->format('d/m/Y') : '-',
                    $row->tanggal_selesai ? $row->tanggal_selesai->format('d/m/Y') : '-',
                    $row->nomor_sk_rekomendasi ?? '-',
                    $row->status_layanan,
                    $row->petugas_verifikator ?? '-',
                    $row->catatan_verifikator ?? '-',
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}
