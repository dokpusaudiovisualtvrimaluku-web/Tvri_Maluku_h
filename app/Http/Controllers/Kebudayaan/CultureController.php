<?php

namespace App\Http\Controllers\Kebudayaan;

use App\Http\Controllers\Controller;
use App\Models\SimandatCulture;
use App\Models\SimandatCultureGallery;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CultureController extends Controller
{
    public function index(Request $request)
    {
        $query = SimandatCulture::query()->withCount('galleries');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('subkategori', 'like', "%{$s}%")
                  ->orWhere('deskripsi', 'like', "%{$s}%")
                  ->orWhere('kata_kunci', 'like', "%{$s}%");
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('kabupaten_kota') && $request->kabupaten_kota !== 'all') {
            $query->where('kabupaten_kota', $request->kabupaten_kota);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('status_validasi') && $request->status_validasi !== 'all') {
            $query->where('status_validasi', $request->status_validasi);
        }

        if ($request->get('export') === 'csv') {
            return $this->exportCsv($query->get());
        }

        $cultures = $query->latest()->paginate(15)->withQueryString();

        $kategoriList = SimandatCulture::KATEGORI_LIST;
        $kabupatenList = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        return view('kebudayaan.cultures.index', compact('cultures', 'kategoriList', 'kabupatenList'));
    }

    public function create()
    {
        $kategoriList = SimandatCulture::KATEGORI_LIST;
        $kabupatenList = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        return view('kebudayaan.cultures.create', compact('kategoriList', 'kabupatenList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'subkategori' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'desa_kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'tahun' => 'required|string|max:10',
            'status' => 'required|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto' => 'nullable|image|max:4096',
            'video_url' => 'nullable|url|max:255',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:10240',
            'dokumen_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'link_eksternal' => 'nullable|url|max:255',
            'kata_kunci' => 'nullable|string|max:255',
            'status_validasi' => 'required|in:DRAFT,SUBMITTED,VERIFIED,VALIDATED,REJECTED',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('kebudayaan/foto', 'public');
        }

        if ($request->hasFile('audio_file')) {
            $validated['audio_url'] = $request->file('audio_file')->store('kebudayaan/audio', 'public');
        }

        if ($request->hasFile('dokumen_file')) {
            $validated['dokumen_pendukung'] = $request->file('dokumen_file')->store('kebudayaan/dokumen', 'public');
        }

        $culture = SimandatCulture::create($validated);

        // Add to gallery if photo uploaded
        if (!empty($validated['foto'])) {
            SimandatCultureGallery::create([
                'culture_id' => $culture->id,
                'media_type' => 'photo',
                'file_path' => $validated['foto'],
                'caption' => 'Foto Utama: ' . $culture->nama,
                'is_primary' => true,
            ]);
        }

        ActivityLogger::log('CREATE_CULTURE', "Menambahkan data objek kebudayaan [{$culture->nama} - Kategori: {$culture->kategori}]");

        return redirect()->route('kebudayaan.cultures.show', $culture->id)->with('success', 'Data Objek Kebudayaan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $culture = SimandatCulture::with(['galleries', 'archives'])->findOrFail($id);
        return view('kebudayaan.cultures.show', compact('culture'));
    }

    public function edit($id)
    {
        $culture = SimandatCulture::findOrFail($id);
        $kategoriList = SimandatCulture::KATEGORI_LIST;
        $kabupatenList = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        return view('kebudayaan.cultures.edit', compact('culture', 'kategoriList', 'kabupatenList'));
    }

    public function update(Request $request, $id)
    {
        $culture = SimandatCulture::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'subkategori' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'desa_kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'tahun' => 'required|string|max:10',
            'status' => 'required|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto' => 'nullable|image|max:4096',
            'video_url' => 'nullable|url|max:255',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:10240',
            'dokumen_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'link_eksternal' => 'nullable|url|max:255',
            'kata_kunci' => 'nullable|string|max:255',
            'status_validasi' => 'required|in:DRAFT,SUBMITTED,VERIFIED,VALIDATED,REJECTED',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('kebudayaan/foto', 'public');
        }

        if ($request->hasFile('audio_file')) {
            $validated['audio_url'] = $request->file('audio_file')->store('kebudayaan/audio', 'public');
        }

        if ($request->hasFile('dokumen_file')) {
            $validated['dokumen_pendukung'] = $request->file('dokumen_file')->store('kebudayaan/dokumen', 'public');
        }

        $culture->update($validated);

        ActivityLogger::log('UPDATE_CULTURE', "Memperbarui data objek kebudayaan [{$culture->nama}]");

        return redirect()->route('kebudayaan.cultures.show', $culture->id)->with('success', 'Data Objek Kebudayaan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $culture = SimandatCulture::findOrFail($id);
        $name = $culture->nama;
        $culture->delete();

        ActivityLogger::log('DELETE_CULTURE', "Menghapus data objek kebudayaan [{$name}]");

        return redirect()->route('kebudayaan.cultures.index')->with('success', "Objek Kebudayaan {$name} berhasil dihapus.");
    }

    public function updateValidationStatus(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:DRAFT,SUBMITTED,VERIFIED,VALIDATED,REJECTED'
        ]);

        $culture = SimandatCulture::findOrFail($id);
        $culture->update(['status_validasi' => $request->status_validasi]);

        ActivityLogger::log('VALIDATE_CULTURE', "Mengubah status validasi kebudayaan [{$culture->nama}] menjadi {$request->status_validasi}");

        return back()->with('success', "Status validasi {$culture->nama} berhasil diubah ke {$request->status_validasi}.");
    }

    private function exportCsv($data)
    {
        $filename = 'data_kebudayaan_maluku_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nama Objek Budaya', 'Kategori', 'Subkategori', 'Kabupaten/Kota', 'Kecamatan', 'Desa/Kelurahan', 'Tahun Penetapan', 'Status', 'Status Validasi', 'Latitude', 'Longitude']);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->nama,
                    $row->kategori,
                    $row->subkategori,
                    $row->kabupaten_kota,
                    $row->kecamatan,
                    $row->desa_kelurahan,
                    $row->tahun,
                    $row->status,
                    $row->status_validasi,
                    $row->latitude,
                    $row->longitude,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
