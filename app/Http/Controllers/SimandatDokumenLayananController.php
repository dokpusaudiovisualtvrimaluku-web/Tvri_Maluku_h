<?php

namespace App\Http\Controllers;

use App\Models\SimandatDokumenLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SimandatDokumenLayananController extends Controller
{
    private function getCategories()
    {
        if (auth()->check() && auth()->user()->username === 'kabid.sma') {
            return array_keys(\App\Models\SimandatSmaLayanan::getJenisLayananOptions());
        }
        return SimandatDokumenLayanan::KATEGORI_LIST;
    }

    public function index(Request $request)
    {
        $query = SimandatDokumenLayanan::query();

        if (auth()->check()) {
            $user = auth()->user();
            $isTU = $user->username === 'admin.tu';
            $isKadis = $user->hasRole('KEPALA_DINAS') || str_contains(strtolower($user->name), 'kepala dinas');
            $isKabidGTK = $user->username === 'kabid.ketenagaan';
            
            // Allow TU, Kadis, Kabid GTK to see all. Others only see their own.
            if (!$isTU && !$isKadis && !$isKabidGTK) {
                $query->where('user_id', $user->id);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_dokumen', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('petugas_nama', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        $categories = $this->getCategories();
        
        // Metrics
        $totalDokumen = SimandatDokumenLayanan::count();
        $totalKepegawaian = SimandatDokumenLayanan::where('kategori', 'Kepegawaian')->count();
        $totalSurat = SimandatDokumenLayanan::whereIn('kategori', ['Surat Masuk', 'Surat Keluar'])->count();

        return view('admin-tu.dokumen-layanan.index', compact(
            'documents', 'categories', 'totalDokumen', 'totalKepegawaian', 'totalSurat'
        ));
    }

    public function create()
    {
        $categories = $this->getCategories();
        return view('admin-tu.dokumen-layanan.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'nama_dokumen' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('dokumen_layanan', 'public');
        
        SimandatDokumenLayanan::create([
            'kategori' => $request->kategori,
            'nama_dokumen' => $request->nama_dokumen,
            'keterangan' => $request->keterangan,
            'file_path' => $filePath,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'user_id' => Auth::id(),
            'petugas_nama' => Auth::user()->name ?? 'Administrator',
        ]);

        return redirect()->route('admin-tu.dokumen-layanan.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function download($id)
    {
        $document = SimandatDokumenLayanan::findOrFail($id);
        
        if (Storage::disk('public')->exists($document->file_path)) {
            $fileName = \Str::slug($document->nama_dokumen) . '.' . $document->file_type;
            return Storage::disk('public')->download($document->file_path, $fileName);
        }

        return redirect()->back()->with('error', 'File tidak ditemukan di server.');
    }

    public function destroy($id)
    {
        $document = SimandatDokumenLayanan::findOrFail($id);
        
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        $document->delete();

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');
    }
}

