<?php
namespace App\Http\Controllers;

use App\Models\Tokoh;
use App\Models\Provinsi;
use App\Models\KategoriTokoh;
use App\Models\Agama;
use App\Models\Suku;
use App\Models\Profesi;
use App\Models\Pendidikan;
use App\Models\Organisasi;
use App\Models\Dokumen;
use App\Models\AuditLog;
use App\Http\Requests\TokohRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TokohController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Tokoh::with(['kategori_tokoh', 'desa', 'kecamatan', 'kabupaten'])->select('tokohs.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('wilayah', function($row){
                    return ($row->desa->name ?? '') . ', ' . ($row->kecamatan->name ?? '') . '<br><span class="text-xs text-gray-500">' . ($row->kabupaten->name ?? '') . '</span>';
                })
                ->addColumn('status', function($row){
                    $badge = $row->status_verifikasi == 'Disetujui' ? 'badge-success' : ($row->status_verifikasi == 'Ditolak' ? 'badge-error' : 'badge-warning');
                    return '<span class="badge '.$badge.' badge-sm">'.$row->status_verifikasi.'</span>';
                })
                ->addColumn('action', function($row){
                    $showBtn = '<a href="'.route('tokohs.show', $row->id).'" class="btn btn-sm btn-ghost"><i class="fa-solid fa-eye"></i></a>';
                    $editBtn = '<a href="'.route('tokohs.edit', $row->id).'" class="btn btn-sm btn-info text-white"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $deleteBtn = '<button class="btn btn-sm btn-error text-white delete-btn" data-id="'.$row->id.'"><i class="fa-solid fa-trash"></i></button>';
                    return '<div class="join">'.$showBtn . $editBtn . $deleteBtn.'</div>';
                })
                ->rawColumns(['wilayah', 'status', 'action'])
                ->make(true);
        }
        return view('tokohs.index');
    }

    public function create()
    {
        $provinsis = Provinsi::orderBy('name')->get();
        $kategoris = KategoriTokoh::orderBy('name')->get();
        $agamas = Agama::orderBy('name')->get();
        $sukus = Suku::orderBy('name')->get();
        $profesis = Profesi::orderBy('name')->get();
        $pendidikans = Pendidikan::orderBy('name')->get();
        $organisasis = Organisasi::orderBy('name')->get();
        
        return view('tokohs.create', compact('provinsis', 'kategoris', 'agamas', 'sukus', 'profesis', 'pendidikans', 'organisasis'));
    }

    public function store(TokohRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['pendata_id'] = Auth::id();
            $data['tanggal_pendataan'] = now();
            $data['status_verifikasi'] = 'Draft';
            
            if($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('tokoh/foto', 'public');
            }
            
            $tokoh = Tokoh::create($data);
            
            // Upload Dokumen KTP & KK
            if($request->hasFile('dokumen_ktp')) {
                $path = $request->file('dokumen_ktp')->store('tokoh/dokumen', 'public');
                Dokumen::create(['tokoh_id' => $tokoh->id, 'jenis' => 'KTP', 'file_path' => $path]);
            }
            if($request->hasFile('dokumen_kk')) {
                $path = $request->file('dokumen_kk')->store('tokoh/dokumen', 'public');
                Dokumen::create(['tokoh_id' => $tokoh->id, 'jenis' => 'KK', 'file_path' => $path]);
            }
            
            AuditLog::create(['user_id' => Auth::id(), 'action' => 'Create Tokoh', 'model' => 'Tokoh', 'model_id' => $tokoh->id, 'ip_address' => $request->ip()]);
            
            DB::commit();
            return redirect()->route('tokohs.index')->with('success', 'Data Tokoh berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Tokoh $tokoh)
    {
        $tokoh->load(['provinsi', 'kabupaten', 'kecamatan', 'desa', 'agama', 'suku', 'profesi', 'pendidikan', 'organisasi', 'kategori_tokoh', 'dokumens']);
        return view('tokohs.show', compact('tokoh'));
    }

    public function edit(Tokoh $tokoh)
    {
        $provinsis = Provinsi::orderBy('name')->get();
        $kategoris = KategoriTokoh::orderBy('name')->get();
        $agamas = Agama::orderBy('name')->get();
        $sukus = Suku::orderBy('name')->get();
        $profesis = Profesi::orderBy('name')->get();
        $pendidikans = Pendidikan::orderBy('name')->get();
        $organisasis = Organisasi::orderBy('name')->get();
        
        return view('tokohs.edit', compact('tokoh', 'provinsis', 'kategoris', 'agamas', 'sukus', 'profesis', 'pendidikans', 'organisasis'));
    }

    public function update(TokohRequest $request, Tokoh $tokoh)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            
            if($request->hasFile('foto')) {
                if($tokoh->foto) Storage::disk('public')->delete($tokoh->foto);
                $data['foto'] = $request->file('foto')->store('tokoh/foto', 'public');
            }
            
            $tokoh->update($data);
            
            if($request->hasFile('dokumen_ktp')) {
                $oldKtp = $tokoh->dokumens()->where('jenis', 'KTP')->first();
                if($oldKtp) {
                    Storage::disk('public')->delete($oldKtp->file_path);
                    $oldKtp->delete();
                }
                $path = $request->file('dokumen_ktp')->store('tokoh/dokumen', 'public');
                Dokumen::create(['tokoh_id' => $tokoh->id, 'jenis' => 'KTP', 'file_path' => $path]);
            }
            
            if($request->hasFile('dokumen_kk')) {
                $oldKk = $tokoh->dokumens()->where('jenis', 'KK')->first();
                if($oldKk) {
                    Storage::disk('public')->delete($oldKk->file_path);
                    $oldKk->delete();
                }
                $path = $request->file('dokumen_kk')->store('tokoh/dokumen', 'public');
                Dokumen::create(['tokoh_id' => $tokoh->id, 'jenis' => 'KK', 'file_path' => $path]);
            }
            
            AuditLog::create(['user_id' => Auth::id(), 'action' => 'Update Tokoh', 'model' => 'Tokoh', 'model_id' => $tokoh->id, 'ip_address' => $request->ip()]);
            
            DB::commit();
            return redirect()->route('tokohs.index')->with('success', 'Data Tokoh berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Tokoh $tokoh)
    {
        if($tokoh->foto) Storage::disk('public')->delete($tokoh->foto);
        foreach($tokoh->dokumens as $doc) {
            Storage::disk('public')->delete($doc->file_path);
        }
        
        AuditLog::create(['user_id' => Auth::id(), 'action' => 'Delete Tokoh', 'model' => 'Tokoh', 'model_id' => $tokoh->id, 'ip_address' => request()->ip()]);
        $tokoh->delete();
        
        return response()->json(['success' => 'Data Tokoh berhasil dihapus.']);
    }
}