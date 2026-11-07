<?php
namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Desa::with('kecamatan');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $parentVal = $row->kecamatan_id ?? '';
                    $editBtn = '<button class="btn btn-sm btn-info text-white edit-btn" data-id="'.$row->id.'" data-name="'.$row->name.'" data-parent="'.$parentVal.'"><i class="fa-solid fa-pen-to-square"></i></button>';
                    $deleteBtn = '<button class="btn btn-sm btn-error text-white delete-btn" data-id="'.$row->id.'"><i class="fa-solid fa-trash"></i></button>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $parents = Kecamatan::orderBy('name')->get();
        return view('master.desas.index', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255', 'kecamatan_id' => 'required|exists:kecamatans,id']);
        Desa::create($validated);
        return response()->json(['success' => 'Data berhasil ditambahkan.']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate(['name' => 'required|string|max:255', 'kecamatan_id' => 'required|exists:kecamatans,id']);
        $data = Desa::findOrFail($id);
        $data->update($validated);
        return response()->json(['success' => 'Data berhasil diubah.']);
    }

    public function destroy($id)
    {
        Desa::findOrFail($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }
}
