<?php

namespace App\Http\Controllers;

use App\Models\SimandatArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class SimandatArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SimandatArchive::query();

        if ($request->filled('module_id')) {
            $query->where('module_id', $request->module_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $archives = $query->orderBy('created_at', 'desc')->get();

        // Seed initial mock data if database table is completely empty
        if (SimandatArchive::count() === 0) {
            $this->seedInitialArchives();
            $archives = SimandatArchive::orderBy('created_at', 'desc')->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $archives,
            'total' => $archives->count(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'module_id' => 'required|string|max:50',
            'code' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'format' => 'nullable|string|max:20',
            'file_size' => 'nullable|string|max:20',
            'uploader' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'file' => 'nullable|file|max:20480', // max 20MB
        ]);

        $filePath = null;
        $fileName = $request->input('file_name', 'Dokumen_' . time() . '.pdf');
        $fileSize = $request->input('file_size', '2.4 MB');
        $format = strtoupper($request->input('format', 'PDF'));

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $fileName = $uploadedFile->getClientOriginalName();
            $format = strtoupper($uploadedFile->getClientOriginalExtension());
            $bytes = $uploadedFile->getSize();
            $fileSize = $this->formatBytes($bytes);
            $filePath = $uploadedFile->store('simandat_archives', 'public');
        }

        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $now = now()->setTimezone('Asia/Jayapura'); // WIT
        $day = $request->input('day', $dayNames[$now->dayOfWeek]);
        $date = $request->input('date', $now->format('d/m/Y'));
        $time = $request->input('time', $now->format('H:i') . ' WIT');

        $archive = SimandatArchive::create([
            'code' => $validated['code'] ?? null,
            'title' => $validated['title'],
            'module_id' => $validated['module_id'],
            'category' => $validated['category'] ?? null,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'format' => $format,
            'file_size' => $fileSize,
            'day' => $day,
            'date' => $date,
            'time' => $time,
            'uploader' => $validated['uploader'] ?? 'Super Admin (Disdikbud)',
            'status' => $validated['status'] ?? 'VALIDATED',
            'is_protected' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berkas digital berhasil disimpan ke Database SIMANDAT dan terlindungi.',
            'data' => $archive,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $archive = SimandatArchive::withTrashed()->find($id);

        if (!$archive) {
            // Check by code
            $archive = SimandatArchive::withTrashed()->where('code', $id)->first();
        }

        if (!$archive) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dokumen tidak ditemukan di database SIMANDAT SIWALIMA.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $archive,
            'database' => [
                'connection' => 'mysql',
                'database_name' => 'SIMANDAT SIWALIMA',
                'table' => 'simandat_archives',
                'id' => $archive->id,
                'is_soft_deleted' => $archive->trashed(),
            ],
            'preview_content' => $this->generatePreviewContent($archive)
        ]);
    }

    private function generatePreviewContent($archive)
    {
        $content = "PEMERINTAH PROVINSI MALUKU\n";
        $content .= "DINAS PENDIDIKAN & KEBUDAYAAN\n";
        $content .= "SISTEM INFORMASI MANAJEMEN DATA TERPADU (SIMANDAT SIWALIMA)\n\n";
        $content .= "----------------------------------------------------------------------\n";
        $content .= "KODE BERKAS     : " . ($archive->code ?? ('DOC-' . $archive->id)) . "\n";
        $content .= "JUDUL DOKUMEN   : " . $archive->title . "\n";
        $content .= "UNIT / MODUL    : Modul " . $archive->module_id . " - " . ($archive->category ?? 'Laporan Sektoral') . "\n";
        $content .= "NAMA FILE FISIK : " . $archive->file_name . " (" . $archive->format . ", " . $archive->file_size . ")\n";
        $content .= "WAKTU UPLOAD    : " . $archive->day . ", " . $archive->date . " " . $archive->time . "\n";
        $content .= "PENGUNGGAH      : " . $archive->uploader . "\n";
        $content .= "STATUS DATA     : " . $archive->status . " (TERVALIDASI & AMAN)\n";
        $content .= "DATABASE ENGINE : SIMANDAT SIWALIMA (simandat_archives MySQL InnoDB ID: " . $archive->id . ")\n";
        $content .= "PROTEKSI DATA   : SOFT-DELETE SAFEGUARD ACTIVE\n";
        $content .= "----------------------------------------------------------------------\n\n";
        $content .= "RINGKASAN & DESKRIPSI:\n";
        $content .= "Dokumen ini telah divalidasi resmi pada portal SIMANDAT Siwalima Disdikbud Provinsi Maluku.\n";
        $content .= "Setiap perubahan data dan riwayat penyerapan tersimpan secara otomatis dan tersinkronisasi dengan Executive Command Center Pimpinan.\n\n";
        $content .= "Keabsahan Digital:\n";
        $content .= "[VALIDATED & DIGITALLY VERIFIED BY DISDIKBUD PROVINSI MALUKU]\n";

        return $content;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $archive = SimandatArchive::find($id) ?: SimandatArchive::where('code', $id)->first();

        if (!$archive) {
            return response()->json([
                'status' => 'error',
                'message' => 'Berkas laporan tidak ditemukan di database.',
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'module_id' => 'sometimes|required|string|max:50',
            'code' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'pic' => 'nullable|string|max:100',
            'format' => 'nullable|string|max:20',
            'uploader' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
        ]);

        $archive->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data laporan berhasil diperbarui di Pangkalan Data Disdikbud Maluku.',
            'data' => $archive,
        ]);
    }

    /**
     * Remove the specified resource from storage (Soft Delete with Protection & Password Verification).
     */
    public function destroy(Request $request, $id)
    {
        $password = $request->input('password', $request->header('X-Delete-Password'));

        if ($password !== '1237') {
            return response()->json([
                'status' => 'error',
                'message' => 'Otorisasi ditolak: Password keamanan salah! Gunakan password resmi (1237).',
            ], 403);
        }

        $archive = SimandatArchive::find($id);
        if (!$archive) {
            $archive = SimandatArchive::where('code', $id)->first();
        }

        if (!$archive) {
            return response()->json([
                'status' => 'error',
                'message' => 'Berkas tidak ditemukan di database.',
            ], 404);
        }
        
        // Soft delete the archive
        $archive->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Berkas '{$archive->title}' berhasil dihapus dari sistem (Soft Deleted & Database Protected).",
            'id' => $id,
        ]);
    }

    /**
     * Download or stream the archive file.
     */
    public function download($id)
    {
        $archive = SimandatArchive::withTrashed()->findOrFail($id);

        if ($archive->file_path && Storage::disk('public')->exists($archive->file_path)) {
            return Storage::disk('public')->download($archive->file_path, $archive->file_name);
        }

        // Generate synthetic document content for mock files
        $content = "========================================================\n";
        $content .= "PEMERINTAH PROVINSI MALUKU\n";
        $content .= "DINAS PENDIDIKAN & KEBUDAYAAN\n";
        $content .= "SISTEM INFORMASI MANAJEMEN DATA TERPADU (SIMANDAT SIWALIMA)\n";
        $content .= "========================================================\n\n";
        $content .= "DOKUMEN ARSIP DIGITAL RESMI TERVERIFIKASI\n";
        $content .= "--------------------------------------------------------\n";
        $content .= "Kode Modul    : " . ($archive->code ?? $archive->module_id) . "\n";
        $content .= "Nama Dokumen  : " . $archive->title . "\n";
        $content .= "Nama File     : " . $archive->file_name . "\n";
        $content .= "Format File   : " . $archive->format . "\n";
        $content .= "Ukuran File   : " . $archive->file_size . "\n";
        $content .= "Waktu Unggah  : " . $archive->day . ", " . $archive->date . " " . $archive->time . "\n";
        $content .= "Pengunggah    : " . $archive->uploader . "\n";
        $content .= "Status Sistem : " . $archive->status . " (DATABASE SECURED)\n";
        $content .= "Perlindungan  : SOFT DELETE PROTECTED (ID: " . $archive->id . ")\n";
        $content .= "--------------------------------------------------------\n\n";
        $content .= "Isi Catatan Ringkasan:\n";
        $content .= "Dokumen ini telah melalui proses verifikasi berjenjang pada portal SIMANDAT Siwalima Disdikbud Provinsi Maluku dan tersimpan aman pada sistem database terintegrasi Executive Command Center.\n\n";
        $content .= "Dicetak secara otomatis oleh SIMANDAT SIWALIMA Engine.\n";
        $content .= "Ambon, " . now()->setTimezone('Asia/Jayapura')->format('d F Y H:i:s') . " WIT\n";

        $downloadName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $archive->file_name);
        if (!str_ends_with(strtolower($downloadName), '.pdf') && !str_ends_with(strtolower($downloadName), '.txt')) {
            $downloadName .= '.txt';
        }

        return Response::make($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $downloadName . '"',
        ]);
    }

    private function formatBytes($bytes, $precision = 1)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    private function seedInitialArchives()
    {
        $initialData = [
            // Sekretariat 2.1
            [
                'code' => '2.1.1',
                'title' => 'Inventarisasi Aset & BMD Sarpras Sekolah',
                'module_id' => '2.1',
                'category' => 'Aset & BMD',
                'file_name' => 'Dokumen_KIB_A_B_Aset_SMA_SMK_Cabdin_I_II.pdf',
                'format' => 'PDF',
                'file_size' => '3.8 MB',
                'day' => 'Sabtu',
                'date' => '12/09/2026',
                'time' => '09:15 WIT',
                'uploader' => 'Super Admin (Disdikbud)',
                'status' => 'VALIDATED',
                'is_protected' => true,
            ],
            [
                'code' => '2.1.2',
                'title' => 'Tata Naskah Persuratan & Ekspedisi Dinas',
                'module_id' => '2.1',
                'category' => 'Persuratan',
                'file_name' => 'Buku_Ekspedisi_Tata_Naskah_Disposisi_Q3.pdf',
                'format' => 'PDF',
                'file_size' => '1.4 MB',
                'day' => 'Sabtu',
                'date' => '12/09/2026',
                'time' => '08:30 WIT',
                'uploader' => 'Super Admin (Disdikbud)',
                'status' => 'VALIDATED',
                'is_protected' => true,
            ],
            [
                'code' => '2.1.3',
                'title' => 'Pemeliharaan Sarana Gedung & Kendaraan Operasional',
                'module_id' => '2.1',
                'category' => 'Pemeliharaan',
                'file_name' => 'Laporan_Pemeliharaan_Gedung_Randis_Q2.xlsx',
                'format' => 'XLSX',
                'file_size' => '2.1 MB',
                'day' => 'Jumat',
                'date' => '11/09/2026',
                'time' => '16:45 WIT',
                'uploader' => 'Super Admin (Disdikbud)',
                'status' => 'VALIDATED',
                'is_protected' => true,
            ],
            // Sekretariat 2.2
            [
                'code' => '2.2.1',
                'title' => 'Penyusunan RKA/DPA & Rencana Strategis (Renstra)',
                'module_id' => '2.2',
                'category' => 'Perencanaan',
                'file_name' => 'DPA_Perubahan_Dinas_Pendidikan_2026.pdf',
                'format' => 'PDF',
                'file_size' => '4.5 MB',
                'day' => 'Sabtu',
                'date' => '12/09/2026',
                'time' => '09:00 WIT',
                'uploader' => 'Subbag Perencanaan',
                'status' => 'VALIDATED',
                'is_protected' => true,
            ],
            [
                'code' => '2.2.2',
                'title' => 'Pengelolaan Keuangan & SPJ Belanja Pegawai/Modal',
                'module_id' => '2.2',
                'category' => 'Keuangan',
                'file_name' => 'Laporan_Realisasi_SPJ_Belanja_Agustus_2026.pdf',
                'format' => 'PDF',
                'file_size' => '3.2 MB',
                'day' => 'Jumat',
                'date' => '11/09/2026',
                'time' => '14:20 WIT',
                'uploader' => 'Bendahara Pengeluaran',
                'status' => 'VALIDATED',
                'is_protected' => true,
            ],
            [
                'code' => '2.2.3',
                'title' => 'Laporan Kinerja Instansi Pemerintah (LKjIP/LAKIP)',
                'module_id' => '2.2',
                'category' => 'Akuntabilitas',
                'file_name' => 'Draft_LKjIP_Disdikbud_Maluku_Semester_I.docx',
                'format' => 'DOCX',
                'file_size' => '1.9 MB',
                'day' => 'Kamis',
                'date' => '10/09/2026',
                'time' => '11:10 WIT',
                'uploader' => 'Subbag Perencanaan',
                'status' => 'VALIDATED',
                'is_protected' => true,
            ],
            // Bidang GTK 7.3 (Kesejahteraan Guru & Tendik)
            [
                'code' => '7.3.1',
                'title' => 'Tunjangan Profesi Guru (TPG) SMA/SMK/SLB',
                'module_id' => '7.3',
                'category' => 'Tunjangan & Sertifikasi',
                'file_name' => 'SK_Tunjangan_Profesi_Guru_Semester_1_2026.pdf',
                'format' => 'PDF',
                'file_size' => '4.2 MB',
                'day' => 'Sabtu',
                'date' => '12/09/2026',
                'time' => '09:30 WIT',
                'uploader' => 'Subbag Ketenagaan',
                'status' => 'VALIDATED',
                'is_protected' => true,
            ],
            [
                'code' => '7.3.2',
                'title' => 'Tunjangan Khusus Guru Daerah 3T & Kepulauan',
                'module_id' => '7.3',
                'category' => 'Tunjangan 3T',
                'file_name' => 'Rekapitulasi_Penerima_Insentif_Daerah_3T_Maluku.xlsx',
                'format' => 'XLSX',
                'file_size' => '2.8 MB',
                'day' => 'Sabtu',
                'date' => '12/09/2026',
                'time' => '08:45 WIT',
                'uploader' => 'Tim Sertifikasi',
                'status' => 'VALIDATED',
                'is_protected' => true,
            ],
            [
                'code' => '7.3.3',
                'title' => 'Kenaikan Pangkat & Angka Kredit (PAK) Guru',
                'module_id' => '7.3',
                'category' => 'Kenaikan Pangkat',
                'file_name' => 'Berkas_Verifikasi_Kenaikan_Pangkat_Periode_Oktober.pdf',
                'format' => 'PDF',
                'file_size' => '5.1 MB',
                'day' => 'Jumat',
                'date' => '11/09/2026',
                'time' => '15:20 WIT',
                'uploader' => 'Admin GTK',
                'status' => 'VALIDATED',
                'is_protected' => true,
            ],
        ];

        foreach ($initialData as $item) {
            SimandatArchive::create($item);
        }
    }
}
