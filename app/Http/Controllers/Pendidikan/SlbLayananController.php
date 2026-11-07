<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\SimandatSlbLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class SlbLayananController extends Controller
{
    public function index(Request $request)
    {
        $query = SimandatSlbLayanan::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('nama_pemohon', 'like', "%{$search}%")
                  ->orWhere('nomor_identitas', 'like', "%{$search}%")
                  ->orWhere('nama_satuan_pendidikan', 'like', "%{$search}%")
                  ->orWhere('nomor_surat_rekomendasi', 'like', "%{$search}%")
                  ->orWhere('npsn_sekolah', 'like', "%{$search}%");
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

        $layananList = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Statistics dynamic from DB
        $stats = [
            'total' => SimandatSlbLayanan::count(),
            'pendirian' => SimandatSlbLayanan::where('jenis_layanan', 'Kelembagaan & Perizinan')->count(),
            'mutasi' => SimandatSlbLayanan::where('jenis_layanan', 'Kesiswaan & Mutasi')->count(),
            'bosp' => SimandatSlbLayanan::where('jenis_layanan', 'Pemerataan & Pendanaan')->count(),
            'legalisir' => SimandatSlbLayanan::where('jenis_layanan', 'Dokumen & Legalitas')->count(),
            'selesai' => SimandatSlbLayanan::where('status_layanan', 'SELESAI')->count(),
            'proses' => SimandatSlbLayanan::whereIn('status_layanan', ['DRAFT', 'PROSES_VERIFIKASI', 'DISETUJUI'])->count(),
        ];

        $jenisLayananOptions = SimandatSlbLayanan::JENIS_LAYANAN_LIST;
        $statusOptions = SimandatSlbLayanan::STATUS_LIST;

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

        return view('pendidikan.slb.index', compact(
            'layananList',
            'stats',
            'jenisLayananOptions',
            'statusOptions',
            'kabupatenKotaList'
        ));
    }

    public function verifikasi(Request $request)
    {
        $query = SimandatSlbLayanan::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('nama_pemohon', 'like', "%{$search}%")
                  ->orWhere('nomor_identitas', 'like', "%{$search}%")
                  ->orWhere('nama_satuan_pendidikan', 'like', "%{$search}%")
                  ->orWhere('nomor_surat_rekomendasi', 'like', "%{$search}%")
                  ->orWhere('npsn_sekolah', 'like', "%{$search}%");
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

        $layananList = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Statistics dynamic from DB
        $stats = [
            'total' => SimandatSlbLayanan::count(),
            'pendirian' => SimandatSlbLayanan::where('jenis_layanan', 'Kelembagaan & Perizinan')->count(),
            'mutasi' => SimandatSlbLayanan::where('jenis_layanan', 'Kesiswaan & Mutasi')->count(),
            'bosp' => SimandatSlbLayanan::where('jenis_layanan', 'Pemerataan & Pendanaan')->count(),
            'legalisir' => SimandatSlbLayanan::where('jenis_layanan', 'Dokumen & Legalitas')->count(),
            'selesai' => SimandatSlbLayanan::where('status_layanan', 'SELESAI')->count(),
            'proses' => SimandatSlbLayanan::whereIn('status_layanan', ['DRAFT', 'PROSES_VERIFIKASI', 'DISETUJUI'])->count(),
        ];

        $jenisLayananOptions = SimandatSlbLayanan::JENIS_LAYANAN_LIST;
        $statusOptions = SimandatSlbLayanan::STATUS_LIST;

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

        return view('pendidikan.slb.verifikasi', compact(
            'layananList',
            'stats',
            'jenisLayananOptions',
            'statusOptions',
            'kabupatenKotaList'
        ));
    }


    public function create(Request $request)
    {
        $selectedJenis = $request->query('jenis', 'Rekomendasi Pendirian SLB Negeri dan SLB Swasta');
        $jenisLayananOptions = SimandatSlbLayanan::JENIS_LAYANAN_LIST;
        $statusOptions = SimandatSlbLayanan::STATUS_LIST;

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

        return view('pendidikan.slb.create', compact(
            'selectedJenis',
            'jenisLayananOptions',
            'statusOptions',
            'kabupatenKotaList'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_layanan' => 'required|in:' . implode(',', array_keys(SimandatSlbLayanan::JENIS_LAYANAN_LIST)),
            'nama_pemohon' => 'required|string|max:255',
            'nomor_identitas' => 'nullable|string|max:50',
            'nama_satuan_pendidikan' => 'required|string|max:255',
            'npsn_sekolah' => 'nullable|string|max:20',
            'kabupaten_kota' => 'required|string|max:100',
            'no_hp_whatsapp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'tanggal_pengajuan' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'nomor_surat_rekomendasi' => 'nullable|string|max:255',
            'perihal_uraian' => 'required|string',
            'status_layanan' => 'required|in:' . implode(',', array_keys(SimandatSlbLayanan::STATUS_LIST)),
            'catatan_verifikator' => 'nullable|string',
            'petugas_verifikator' => 'nullable|string|max:255',
            'file_persyaratan' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240',
            'file_hasil_rekomendasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240',
        ]);

        $validated['nomor_registrasi'] = SimandatSlbLayanan::generateNomorRegistrasi($validated['jenis_layanan']);
        $validated['user_id'] = auth()->id();

        if ($request->hasFile('file_persyaratan')) {
            $file = $request->file('file_persyaratan');
            $path = $file->store('slb_dokumen/persyaratan', 'public');
            $validated['file_persyaratan'] = $path;
            $validated['file_nama_persyaratan'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
        }

        if ($request->hasFile('file_hasil_rekomendasi')) {
            $file = $request->file('file_hasil_rekomendasi');
            $path = $file->store('slb_dokumen/hasil_rekomendasi', 'public');
            $validated['file_hasil_rekomendasi'] = $path;
            $validated['file_nama_hasil'] = $file->getClientOriginalName();
        }

        $layanan = SimandatSlbLayanan::create($validated);

        // SYNC 1: Dokumen Layanan
        if (isset($validated['file_persyaratan'])) {
            \App\Models\SimandatDokumenLayanan::create([
                'kategori' => 'Kearsipan',
                'nama_dokumen' => 'Permohonan SLB: ' . $layanan->nomor_registrasi . ' - ' . $layanan->nama_pemohon,
                'keterangan' => 'Layanan SLB (' . $layanan->jenis_layanan . '). ' . $layanan->perihal_uraian,
                'file_path' => $validated['file_persyaratan'],
                'file_type' => pathinfo($validated['file_nama_persyaratan'] ?? '', PATHINFO_EXTENSION) ?: 'pdf',
                'file_size' => $validated['file_size'] ?? 0,
                'user_id' => $validated['user_id'],
                'petugas_nama' => auth()->user()->name ?? 'Pemohon SLB',
            ]);
            
            // SYNC 2: Laporan
            \App\Models\SimandatLaporan::create([
                'nomor_laporan' => $layanan->nomor_registrasi,
                'kategori_laporan' => 'Pelayanan Publik',
                'judul_laporan' => 'Permohonan SLB: ' . $layanan->nama_pemohon,
                'tanggal_laporan' => $layanan->tanggal_pengajuan,
                'pembuat_laporan' => $layanan->nama_pemohon,
                'wilayah' => $layanan->kabupaten_kota,
                'keterangan' => $layanan->perihal_uraian,
                'status' => 'DRAFT',
                'file_dokumen' => $validated['file_persyaratan'],
                'user_id' => $validated['user_id'],
            ]);

            // SYNC 3: Pangkalan Dokumen
            \App\Models\SimandatDocument::create([
                'document_number' => $layanan->nomor_registrasi,
                'title' => 'Berkas SLB: ' . $layanan->nama_pemohon,
                'category' => 'Lainnya',
                'division' => 'Bidang Pendidikan Khusus & SLB',
                'description' => 'Berkas persyaratan permohonan layanan SLB.',
                'document_date' => $layanan->tanggal_pengajuan,
                'year' => date('Y', strtotime($layanan->tanggal_pengajuan)),
                'confidentiality' => 'PUBLIK',
                'status' => 'AKTIF',
                'current_version' => '1.0',
                'file_path' => $validated['file_persyaratan'],
                'file_name' => $validated['file_nama_persyaratan'] ?? 'berkas_slb.pdf',
                'file_type' => pathinfo($validated['file_nama_persyaratan'] ?? '', PATHINFO_EXTENSION) ?: 'pdf',
                'file_size' => $validated['file_size'] ?? 0,
                'tags' => 'slb, permohonan, ' . strtolower($layanan->kabupaten_kota),
                'uploader_id' => $validated['user_id'],
                'uploader_name' => auth()->user()->name ?? 'Sistem',
            ]);
        }

        return redirect()->route('pendidikan.slb.layanan.show', $layanan->id)
            ->with('success', 'Dokumen Permohonan Layanan Pendidikan Khusus (' . $layanan->nomor_registrasi . ') berhasil didaftarkan.');
    }

    public function show($id)
    {
        $layanan = SimandatSlbLayanan::with('user')->findOrFail($id);
        return view('pendidikan.slb.show', compact('layanan'));
    }

    public function edit($id)
    {
        $layanan = SimandatSlbLayanan::findOrFail($id);
        $jenisLayananOptions = SimandatSlbLayanan::JENIS_LAYANAN_LIST;
        $statusOptions = SimandatSlbLayanan::STATUS_LIST;

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

        return view('pendidikan.slb.edit', compact(
            'layanan',
            'jenisLayananOptions',
            'statusOptions',
            'kabupatenKotaList'
        ));
    }

    public function proses($id)
    {
        $layanan = SimandatSlbLayanan::findOrFail($id);
        $statusOptions = SimandatSlbLayanan::STATUS_LIST;
        
        return view('pendidikan.slb.proses', compact('layanan', 'statusOptions'));
    }

    public function updateVerifikasi(Request $request, $id)
    {
        $layanan = SimandatSlbLayanan::findOrFail($id);

        $validated = $request->validate([
            'status_layanan' => 'required|in:' . implode(',', array_keys(SimandatSlbLayanan::STATUS_LIST)),
            'nomor_surat_rekomendasi' => 'nullable|string|max:255',
            'tanggal_selesai' => 'nullable|date',
            'catatan_verifikator' => 'nullable|string',
            'file_hasil_rekomendasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240',
        ]);

        if ($request->hasFile('file_hasil_rekomendasi')) {
            if ($layanan->file_hasil_rekomendasi && Storage::disk('public')->exists($layanan->file_hasil_rekomendasi)) {
                Storage::disk('public')->delete($layanan->file_hasil_rekomendasi);
            }
            $file = $request->file('file_hasil_rekomendasi');
            $validated['file_hasil_rekomendasi'] = $file->store('slb_dokumen/hasil_rekomendasi', 'public');
            $validated['file_nama_hasil'] = $file->getClientOriginalName();
        }

        // Auto set verifikator as current user
        $validated['petugas_verifikator'] = auth()->user()->name ?? 'Kepala Dinas / Verifikator';

        $layanan->update($validated);

        // SYNC SK / Hasil Rekomendasi ke Repositori jika diunggah
        if (isset($validated['file_hasil_rekomendasi'])) {
            \App\Models\SimandatDokumenLayanan::create([
                'kategori' => 'Surat Keluar',
                'nama_dokumen' => 'SK/Rekomendasi SLB: ' . $layanan->nomor_registrasi,
                'keterangan' => 'Surat Terbit (SK) untuk layanan SLB: ' . $layanan->nama_pemohon,
                'file_path' => $validated['file_hasil_rekomendasi'],
                'file_type' => pathinfo($validated['file_nama_hasil'] ?? '', PATHINFO_EXTENSION) ?: 'pdf',
                'file_size' => 0, 
                'user_id' => auth()->id(),
                'petugas_nama' => auth()->user()->name ?? 'Kepala Dinas',
            ]);

            \App\Models\SimandatDocument::create([
                'document_number' => $layanan->nomor_surat_rekomendasi ?? $layanan->nomor_registrasi,
                'title' => 'SK/Rekomendasi SLB: ' . $layanan->nama_pemohon,
                'category' => 'Surat Keputusan (SK)',
                'division' => 'Bidang Pendidikan Khusus & SLB',
                'description' => 'SK / Surat Rekomendasi resmi hasil verifikasi layanan SLB.',
                'document_date' => $layanan->tanggal_selesai ?? now(),
                'year' => date('Y'),
                'confidentiality' => 'PUBLIK',
                'status' => 'AKTIF',
                'current_version' => '1.0',
                'file_path' => $validated['file_hasil_rekomendasi'],
                'file_name' => $validated['file_nama_hasil'] ?? 'sk_slb.pdf',
                'file_type' => pathinfo($validated['file_nama_hasil'] ?? '', PATHINFO_EXTENSION) ?: 'pdf',
                'file_size' => 0,
                'tags' => 'sk, slb, persetujuan',
                'uploader_id' => auth()->id(),
                'uploader_name' => auth()->user()->name ?? 'Kepala Dinas',
            ]);
        }

        return redirect()->route('pendidikan.slb.layanan.show', $layanan->id)
            ->with('success', 'Status Verifikasi Layanan SLB (' . $layanan->nomor_registrasi . ') berhasil diperbarui.');
    }

    public function update(Request $request, $id)
    {
        $layanan = SimandatSlbLayanan::findOrFail($id);

        $validated = $request->validate([
            'jenis_layanan' => 'required|in:' . implode(',', array_keys(SimandatSlbLayanan::JENIS_LAYANAN_LIST)),
            'nama_pemohon' => 'required|string|max:255',
            'nomor_identitas' => 'nullable|string|max:50',
            'nama_satuan_pendidikan' => 'required|string|max:255',
            'npsn_sekolah' => 'nullable|string|max:20',
            'kabupaten_kota' => 'required|string|max:100',
            'no_hp_whatsapp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'tanggal_pengajuan' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'nomor_surat_rekomendasi' => 'nullable|string|max:255',
            'perihal_uraian' => 'required|string',
            'status_layanan' => 'required|in:' . implode(',', array_keys(SimandatSlbLayanan::STATUS_LIST)),
            'catatan_verifikator' => 'nullable|string',
            'petugas_verifikator' => 'nullable|string|max:255',
            'file_persyaratan' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240',
            'file_hasil_rekomendasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240',
        ]);

        if ($request->hasFile('file_persyaratan')) {
            if ($layanan->file_persyaratan && Storage::disk('public')->exists($layanan->file_persyaratan)) {
                Storage::disk('public')->delete($layanan->file_persyaratan);
            }
            $file = $request->file('file_persyaratan');
            $validated['file_persyaratan'] = $file->store('slb_dokumen/persyaratan', 'public');
            $validated['file_nama_persyaratan'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
        }

        if ($request->hasFile('file_hasil_rekomendasi')) {
            if ($layanan->file_hasil_rekomendasi && Storage::disk('public')->exists($layanan->file_hasil_rekomendasi)) {
                Storage::disk('public')->delete($layanan->file_hasil_rekomendasi);
            }
            $file = $request->file('file_hasil_rekomendasi');
            $validated['file_hasil_rekomendasi'] = $file->store('slb_dokumen/hasil_rekomendasi', 'public');
            $validated['file_nama_hasil'] = $file->getClientOriginalName();
        }

        $layanan->update($validated);

        return redirect()->route('pendidikan.slb.layanan.show', $layanan->id)
            ->with('success', 'Data Layanan Pendidikan Khusus (' . $layanan->nomor_registrasi . ') berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $layanan = SimandatSlbLayanan::findOrFail($id);

        if ($layanan->file_persyaratan && Storage::disk('public')->exists($layanan->file_persyaratan)) {
            Storage::disk('public')->delete($layanan->file_persyaratan);
        }

        if ($layanan->file_hasil_rekomendasi && Storage::disk('public')->exists($layanan->file_hasil_rekomendasi)) {
            Storage::disk('public')->delete($layanan->file_hasil_rekomendasi);
        }

        $regNo = $layanan->nomor_registrasi;
        $layanan->delete();

        return redirect()->route('pendidikan.slb.layanan.index')
            ->with('success', "Data permohonan layanan SLB {$regNo} berhasil dihapus.");
    }

    public function downloadFile($id, $type)
    {
        $layanan = SimandatSlbLayanan::findOrFail($id);
        $filePath = null;
        $customName = null;

        if ($type === 'persyaratan' && $layanan->file_persyaratan) {
            $filePath = $layanan->file_persyaratan;
            $customName = $layanan->file_nama_persyaratan ?? "Berkas_Persyaratan_{$layanan->nomor_registrasi}.pdf";
        } elseif ($type === 'hasil' && $layanan->file_hasil_rekomendasi) {
            $filePath = $layanan->file_hasil_rekomendasi;
            $customName = $layanan->file_nama_hasil ?? "Surat_Rekomendasi_{$layanan->nomor_registrasi}.pdf";
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File dokumen tidak ditemukan atau belum diunggah.');
        }

        return Storage::disk('public')->download($filePath, $customName);
    }

    public function exportCsv(Request $request)
    {
        $query = SimandatSlbLayanan::query();

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
            'Content-Disposition' => 'attachment; filename="data-layanan-pendidikan-khusus-slb-' . date('Ymd-His') . '.csv"',
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
                'Nama Pemohon',
                'Nomor Identitas',
                'Satuan Pendidikan (SLB)',
                'NPSN',
                'Kabupaten/Kota',
                'No HP/WA',
                'Email',
                'Tanggal Pengajuan',
                'Tanggal Selesai',
                'No Surat / Rekomendasi',
                'Status Layanan',
                'Petugas Verifikator',
                'Catatan Verifikator',
            ]);

            foreach ($records as $index => $row) {
                fputcsv($output, [
                    $index + 1,
                    $row->nomor_registrasi,
                    $row->jenis_layanan,
                    $row->nama_pemohon,
                    $row->nomor_identitas ?? '-',
                    $row->nama_satuan_pendidikan,
                    $row->npsn_sekolah ?? '-',
                    $row->kabupaten_kota,
                    $row->no_hp_whatsapp ?? '-',
                    $row->email ?? '-',
                    $row->tanggal_pengajuan ? $row->tanggal_pengajuan->format('d/m/Y') : '-',
                    $row->tanggal_selesai ? $row->tanggal_selesai->format('d/m/Y') : '-',
                    $row->nomor_surat_rekomendasi ?? '-',
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
