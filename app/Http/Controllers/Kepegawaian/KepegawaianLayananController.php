<?php

namespace App\Http\Controllers\Kepegawaian;

use App\Http\Controllers\Controller;
use App\Models\SimandatKepegawaianLayanan;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class KepegawaianLayananController extends Controller
{
    /**
     * Display listing of Kepegawaian & Umum services and applications.
     */
    public function index(Request $request)
    {
        $query = SimandatKepegawaianLayanan::query();

        if (auth()->check()) {
            $user = auth()->user();
            $isTU = $user->username === 'admin.tu';
            $isKadis = $user->hasRole('KEPALA_DINAS') || str_contains(strtolower($user->name), 'kepala dinas');
            
            // Isolate data: only TU and Kadis can see all. Others only see their own.
            if (!$isTU && !$isKadis) {
                $query->where('user_id', $user->id);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_agenda', 'like', "%{$search}%")
                  ->orWhere('nama_pemohon', 'like', "%{$search}%")
                  ->orWhere('nip_nik_nisn', 'like', "%{$search}%")
                  ->orWhere('nomor_sk_surat', 'like', "%{$search}%")
                  ->orWhere('jabatan_unit_kerja', 'like', "%{$search}%")
                  ->orWhere('perihal_deskripsi', 'like', "%{$search}%");
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
            $query->whereYear('tanggal_permohonan', $request->tahun);
        }

        // Export to CSV if requested
        if ($request->get('export') === 'csv') {
            return $this->exportCsv($query->get());
        }

        $layananList = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // 10 Services counts for quick tab filtering
        $serviceCounts = [];
        foreach (array_keys(SimandatKepegawaianLayanan::JENIS_LAYANAN_LIST) as $serviceName) {
            $serviceCounts[$serviceName] = SimandatKepegawaianLayanan::where('jenis_layanan', $serviceName)->count();
        }

        // Summary Metric Cards
        $totalLayanan = SimandatKepegawaianLayanan::count();
        $totalPending = SimandatKepegawaianLayanan::where('status_layanan', 'PENDING')->count();
        $totalProses = SimandatKepegawaianLayanan::where('status_layanan', 'PROSES_VERIFIKASI')->count();
        $totalSelesai = SimandatKepegawaianLayanan::where('status_layanan', 'SELESAI')->count();

        $allJenisLayanan = SimandatKepegawaianLayanan::JENIS_LAYANAN_LIST;
        $statusOptions = SimandatKepegawaianLayanan::STATUS_LIST;

        $kabupatenOptions = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        return view('kepegawaian.index', compact(
            'layananList', 'serviceCounts', 'totalLayanan', 'totalPending', 'totalProses', 'totalSelesai',
            'allJenisLayanan', 'statusOptions', 'kabupatenOptions'
        ));
    }

    /**
     * Show form to create new service request.
     */
    public function create(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $isTU = $user->username === 'admin.tu';
            $isSekretaris = str_contains(strtolower($user->name), 'sekretaris') || str_contains(strtolower($user->username), 'sekretaris');
            
            if (!$isTU && !$isSekretaris) {
                if ($user->username === 'kabid.sma') {
                    return redirect()->route('pendidikan.sma.layanan.create');
                }
                return redirect()->route('pendidikan.gtk.layanan.create');
            }
        }

        $selectedService = $request->get('jenis', 'Surat / Naskah Dinas');
        $allJenisLayanan = SimandatKepegawaianLayanan::JENIS_LAYANAN_LIST;
        $kabupatenOptions = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        return view('kepegawaian.create', compact('selectedService', 'allJenisLayanan', 'kabupatenOptions'));
    }

    /**
     * Store new service request with audit logging.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_layanan' => 'required|string',
            'nama_pemohon' => 'required|string|max:255',
            'nip_nik_nisn' => 'nullable|string|max:50',
            'pangkat_golongan' => 'nullable|string|max:100',
            'jabatan_unit_kerja' => 'required|string|max:255',
            'kabupaten_kota' => 'required|string|max:100',
            'tanggal_permohonan' => 'required|date',
            'perihal_deskripsi' => 'nullable|string',
            'file_permohonan' => 'nullable|file|max:25600', // max 25MB
        ]);

        $filePath = null;
        if ($request->hasFile('file_permohonan')) {
            $filePath = $request->file('file_permohonan')->store('kepegawaian/permohonan', 'public');
        }

        // Generate unique agenda number
        $config = SimandatKepegawaianLayanan::JENIS_LAYANAN_LIST[$validated['jenis_layanan']] ?? ['prefix' => 'REG'];
        $prefix = $config['prefix'];
        $year = date('Y');
        $countThisYear = SimandatKepegawaianLayanan::whereYear('created_at', $year)->count() + 1;
        $nomorAgenda = sprintf('%s/%s/%04d', $prefix, $year, $countThisYear);

        $user = Auth::user();
        $petugasNama = $user ? $user->name : 'Petugas Kepegawaian & Umum';

        $layanan = SimandatKepegawaianLayanan::create([
            'nomor_agenda' => $nomorAgenda,
            'jenis_layanan' => $validated['jenis_layanan'],
            'nama_pemohon' => $validated['nama_pemohon'],
            'nip_nik_nisn' => $validated['nip_nik_nisn'] ?? null,
            'pangkat_golongan' => $validated['pangkat_golongan'] ?? null,
            'jabatan_unit_kerja' => $validated['jabatan_unit_kerja'],
            'kabupaten_kota' => $validated['kabupaten_kota'],
            'tanggal_permohonan' => $validated['tanggal_permohonan'],
            'perihal_deskripsi' => $validated['perihal_deskripsi'] ?? null,
            'status_layanan' => 'PENDING',
            'file_permohonan' => $filePath,
            'petugas_nama' => $petugasNama,
            'user_id' => $user ? $user->id : null,
        ]);

        // Audit Logging
        ActivityLogger::log(
            'KEPEGAWAIAN_CREATE',
            "Mendaftarkan permohonan '{$layanan->jenis_layanan}' an. {$layanan->nama_pemohon} (Agenda: {$layanan->nomor_agenda})"
        );

        return redirect()->route('kepegawaian.layanan.show', $layanan->id)
            ->with('success', "Permohonan {$layanan->jenis_layanan} berhasil didaftarkan dengan Nomor Agenda: {$layanan->nomor_agenda}");
    }

    /**
     * Show detail of service application.
     */
    public function show($id)
    {
        $layanan = SimandatKepegawaianLayanan::with('user')->findOrFail($id);
        return view('kepegawaian.show', compact('layanan'));
    }

    /**
     * Show form to edit / process service.
     */
    public function edit($id)
    {
        $layanan = SimandatKepegawaianLayanan::findOrFail($id);
        $allJenisLayanan = SimandatKepegawaianLayanan::JENIS_LAYANAN_LIST;
        $statusOptions = SimandatKepegawaianLayanan::STATUS_LIST;
        $kabupatenOptions = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        return view('kepegawaian.edit', compact('layanan', 'allJenisLayanan', 'statusOptions', 'kabupatenOptions'));
    }

    /**
     * Update service request, validation status, and upload result document / SK.
     */
    public function update(Request $request, $id)
    {
        $layanan = SimandatKepegawaianLayanan::findOrFail($id);

        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'nip_nik_nisn' => 'nullable|string|max:50',
            'pangkat_golongan' => 'nullable|string|max:100',
            'jabatan_unit_kerja' => 'required|string|max:255',
            'kabupaten_kota' => 'required|string|max:100',
            'status_layanan' => 'required|in:PENDING,PROSES_VERIFIKASI,DISETUJUI,DITOLAK,SELESAI',
            'nomor_sk_surat' => 'nullable|string|max:150',
            'tanggal_selesai' => 'nullable|date',
            'catatan_petugas' => 'nullable|string',
            'file_hasil_sk' => 'nullable|file|max:25600',
        ]);

        if ($request->hasFile('file_hasil_sk')) {
            $validated['file_hasil_sk'] = $request->file('file_hasil_sk')->store('kepegawaian/sk_hasil', 'public');
        }

        if ($validated['status_layanan'] === 'SELESAI' && empty($validated['tanggal_selesai'])) {
            $validated['tanggal_selesai'] = now()->toDateString();
        }

        $user = Auth::user();
        if ($user) {
            $validated['petugas_nama'] = $user->name;
        }

        $layanan->update($validated);

        // Audit Logging
        ActivityLogger::log(
            'KEPEGAWAIAN_UPDATE',
            "Memperbarui status layanan '{$layanan->jenis_layanan}' an. {$layanan->nama_pemohon} menjadi [{$layanan->status_layanan}]"
        );

        return redirect()->route('kepegawaian.layanan.show', $layanan->id)
            ->with('success', "Status permohonan {$layanan->jenis_layanan} berhasil diperbarui menjadi [{$layanan->status_layanan}].");
    }

    /**
     * Download uploaded requirement or output SK file.
     */
    public function downloadFile($id, $type)
    {
        $layanan = SimandatKepegawaianLayanan::findOrFail($id);
        $filePath = ($type === 'sk') ? $layanan->file_hasil_sk : $layanan->file_permohonan;

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            $downloadName = ($type === 'sk')
                ? ('SK_' . Str::slug($layanan->jenis_layanan) . '_' . Str::slug($layanan->nama_pemohon) . '.pdf')
                : ('Berkas_Syarat_' . Str::slug($layanan->nama_pemohon) . '.' . pathinfo($filePath, PATHINFO_EXTENSION));

            return Storage::disk('public')->download($filePath, $downloadName);
        }

        // Fallback synthetic receipt
        $content = "======================================================================\n";
        $content .= "PEMERINTAH PROVINSI MALUKU - DINAS PENDIDIKAN DAN KEBUDAYAAN\n";
        $content .= "SUB BAGIAN KEPEGAWAIAN DAN UMUM\n";
        $content .= "======================================================================\n\n";
        $content .= "TANDA BUKTI LAYANAN KEPEGAWAIAN & PERSURATAN RESMI\n";
        $content .= "----------------------------------------------------------------------\n";
        $content .= "NOMOR AGENDA    : " . $layanan->nomor_agenda . "\n";
        $content .= "JENIS LAYANAN   : " . $layanan->jenis_layanan . "\n";
        $content .= "NAMA PEMOHON    : " . $layanan->nama_pemohon . "\n";
        $content .= "NIP/NIK/NISN    : " . ($layanan->nip_nik_nisn ?: '-') . "\n";
        $content .= "PANGKAT/GOL     : " . ($layanan->pangkat_golongan ?: '-') . "\n";
        $content .= "UNIT KERJA      : " . $layanan->jabatan_unit_kerja . "\n";
        $content .= "KABUPATEN/KOTA  : " . $layanan->kabupaten_kota . "\n";
        $content .= "TGL PERMOHONAN  : " . $layanan->tanggal_permohonan->format('d/m/Y') . "\n";
        $content .= "STATUS LAYANAN  : " . $layanan->status_layanan . "\n";
        $content .= "NOMOR SK/SURAT  : " . ($layanan->nomor_sk_surat ?: 'Dalam Proses Penerbitan') . "\n";
        $content .= "PETUGAS PROSES  : " . $layanan->petugas_nama . "\n";
        $content .= "----------------------------------------------------------------------\n\n";
        $content .= "CATATAN PETUGAS :\n";
        $content .= ($layanan->catatan_petugas ?: 'Berkas permohonan telah tercatat secara resmi dalam sistem pangkalan data Disdikbud Maluku.') . "\n\n";
        $content .= "Diterbitkan pada: " . now()->setTimezone('Asia/Jayapura')->format('d F Y H:i:s') . " WIT\n";

        return Response::make($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"Tanda_Bukti_{$layanan->nomor_agenda}.txt\"",
        ]);
    }

    /**
     * Soft delete application with audit trail.
     */
    public function destroy($id)
    {
        $layanan = SimandatKepegawaianLayanan::findOrFail($id);

        ActivityLogger::log(
            'KEPEGAWAIAN_DELETE',
            "Menghapus data permohonan '{$layanan->jenis_layanan}' an. {$layanan->nama_pemohon} (Agenda: {$layanan->nomor_agenda})"
        );

        $layanan->delete();

        return redirect()->route('kepegawaian.layanan.index')
            ->with('success', "Data permohonan berhasil dihapus.");
    }

    /**
     * Export applications to CSV.
     */
    private function exportCsv($data)
    {
        $filename = 'simandat_kepegawaian_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $output = fopen('php://output', 'w');
            fputs($output, "\xEF\xBB\xBF");
            fputcsv($output, [
                'ID', 'Nomor Agenda', 'Jenis Layanan', 'Nama Pemohon', 'NIP/NIK/NISN',
                'Pangkat Golongan', 'Unit Kerja / Sekolah', 'Kabupaten/Kota',
                'Tanggal Permohonan', 'Status', 'Nomor SK / Surat', 'Tanggal Selesai', 'Petugas'
            ]);

            foreach ($data as $item) {
                fputcsv($output, [
                    $item->id,
                    $item->nomor_agenda,
                    $item->jenis_layanan,
                    $item->nama_pemohon,
                    $item->nip_nik_nisn,
                    $item->pangkat_golongan,
                    $item->jabatan_unit_kerja,
                    $item->kabupaten_kota,
                    $item->tanggal_permohonan ? $item->tanggal_permohonan->format('d/m/Y') : '-',
                    $item->status_layanan,
                    $item->nomor_sk_surat ?: '-',
                    $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y') : '-',
                    $item->petugas_nama,
                ]);
            }
            fclose($output);
        };

        return Response::stream($callback, 200, $headers);
    }
}
