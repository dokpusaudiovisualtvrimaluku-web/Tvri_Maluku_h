<?php

namespace App\Http\Controllers;

use App\Models\SimandatLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SimandatLaporanController extends Controller
{
    private function getKategoriOptions()
    {
        if (auth()->check() && auth()->user()->username === 'kabid.sma') {
            return array_keys(\App\Models\SimandatSmaLayanan::getJenisLayananOptions());
        }
        return ['Laporan Bulanan', 'Laporan Tahunan', 'Laporan Kegiatan', 'Laporan Keuangan', 'Lainnya'];
    }

    public function index(Request $request)
    {
        $query = SimandatLaporan::with('user');

        if (auth()->check()) {
            $user = auth()->user();
            $isTU = $user->username === 'admin.tu';
            $isKadis = $user->hasRole('KEPALA_DINAS') || str_contains(strtolower($user->name), 'kepala dinas');
            $isKabidGTK = $user->username === 'kabid.ketenagaan';
            
            // Allow TU, Kadis, Kabid GTK to see all Laporan. Others only see their own.
            if (!$isTU && !$isKadis && !$isKabidGTK) {
                $query->where('user_id', $user->id);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_laporan', 'like', "%{$search}%")
                  ->orWhere('judul_laporan', 'like', "%{$search}%")
                  ->orWhere('pembuat_laporan', 'like', "%{$search}%")
                  ->orWhere('wilayah', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_laporan')) {
            $query->where('kategori_laporan', $request->kategori_laporan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $laporanList = $query->latest()->paginate(10)->withQueryString();

        $kategoriOptions = $this->getKategoriOptions();
        $statusOptions = ['Draft', 'Terkirim', 'Selesai', 'Revisi'];

        return view('admin-tu.laporan', compact('laporanList', 'kategoriOptions', 'statusOptions'));
    }

    public function create()
    {
        $kategoriOptions = $this->getKategoriOptions();
        $statusOptions = ['Draft', 'Terkirim', 'Selesai', 'Revisi'];
        return view('admin-tu.laporan_create', compact('kategoriOptions', 'statusOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'kategori_laporan' => 'required|string|max:100',
            'tanggal_laporan' => 'required|date',
            'pembuat_laporan' => 'nullable|string|max:255',
            'wilayah' => 'nullable|string|max:255',
            'status' => 'required|string|max:50',
        ]);

        $nomor_laporan = 'LAP-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $laporan = new SimandatLaporan($request->all());
        $laporan->nomor_laporan = $nomor_laporan;
        $laporan->user_id = Auth::id();
        $laporan->save();

        return redirect()->route('admin-tu.laporan')->with('success', 'Data laporan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $laporan = SimandatLaporan::findOrFail($id);
        return view('admin-tu.laporan_show', compact('laporan'));
    }

    public function edit($id)
    {
        $laporan = SimandatLaporan::findOrFail($id);
        $kategoriOptions = $this->getKategoriOptions();
        $statusOptions = ['Draft', 'Terkirim', 'Selesai', 'Revisi'];
        return view('admin-tu.laporan_edit', compact('laporan', 'kategoriOptions', 'statusOptions'));
    }

    public function update(Request $request, $id)
    {
        $laporan = SimandatLaporan::findOrFail($id);
        $laporan->update($request->all());
        return redirect()->route('admin-tu.laporan')->with('success', 'Data laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        SimandatLaporan::findOrFail($id)->delete();
        return redirect()->route('admin-tu.laporan')->with('success', 'Data laporan berhasil dihapus.');
    }
}
