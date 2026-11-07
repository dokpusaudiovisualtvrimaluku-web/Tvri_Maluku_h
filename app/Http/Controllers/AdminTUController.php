<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminTUController extends Controller
{
    public function permohonan()
    {
        return view('admin-tu.permohonan');
    }

    public function layanan()
    {
        return view('admin-tu.layanan');
    }

    public function jenisLayanan()
    {
        return view('admin-tu.jenis-layanan');
    }

    public function storeJenisLayananSma(\Illuminate\Http\Request $request)
    {
        if (auth()->user()->username !== 'kabid.sma') {
            abort(403);
        }

        $request->validate([
            'nama_layanan' => 'required|string|max:255|unique:sma_jenis_layanans,nama_layanan',
            'seksi' => 'nullable|string|max:255',
            'prefix' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        \App\Models\SmaJenisLayanan::create([
            'nama_layanan' => $request->nama_layanan,
            'seksi' => $request->seksi ?? 'Bidang Pembinaan SMA',
            'prefix' => $request->prefix ?? 'SMA-NEW',
            'deskripsi' => $request->deskripsi ?? 'Layanan Dokumen Bidang SMA',
            'icon' => 'file-text',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
        ]);

        return back()->with('success', 'Jenis Layanan Baru Berhasil Ditambahkan!');
    }

    public function dokumenLayanan()
    {
        return view('admin-tu.dokumen-layanan');
    }

    public function laporan()
    {
        return view('admin-tu.laporan');
    }

            public function arsip(Request $request)
    {
        $query = \App\Models\SimandatDokumenLayanan::query();

        if (auth()->check()) {
            $user = auth()->user();
            $isAdmin = $user->username === 'admin' || $user->hasRole('ADMIN');
            $isTU = $user->username === 'admin.tu';
            $isKadis = $user->hasRole('KEPALA_DINAS') || str_contains(strtolower($user->name), 'kepala dinas');
            $isKabidSMA = $user->username === 'kabid.sma';
            $isKabidKebudayaan = $user->username === 'kabid.kebudayaan';
            
            // Allow Admin, TU and Kadis to see all.
            if (!$isAdmin && !$isTU && !$isKadis) {
                if ($isKabidSMA) {
                    $query->where(function($q) use ($user) {
                        $q->where('user_id', $user->id)
                          ->orWhere('nama_dokumen', 'like', '%SMA%')
                          ->orWhere('keterangan', 'like', '%SMA%');
                    });
                } elseif ($isKabidKebudayaan) {
                    $query->where(function($q) use ($user) {
                        $q->where('user_id', $user->id)
                          ->orWhere('nama_dokumen', 'like', '%Kebudayaan%')
                          ->orWhere('keterangan', 'like', '%Kebudayaan%');
                    });
                } else {
                    $query->where('user_id', $user->id);
                }
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_dokumen', 'like', "%" . $search . "%")
                  ->orWhere('keterangan', 'like', "%" . $search . "%")
                  ->orWhere('petugas_nama', 'like', "%" . $search . "%");
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        $documents = $query->latest()->paginate(15);
        $categories = \App\Models\SimandatDokumenLayanan::KATEGORI_LIST;
        
        // Stats
        $totalDokumen = $query->count();
        $totalKepegawaian = (clone $query)->where('kategori', 'Kepegawaian')->count();
        $totalSurat = (clone $query)->whereIn('kategori', ['Surat Masuk', 'Surat Keluar'])->count();

        return view('admin-tu.arsip', compact(
            'documents', 'categories', 'totalDokumen', 'totalKepegawaian', 'totalSurat'
        ));
    }
}