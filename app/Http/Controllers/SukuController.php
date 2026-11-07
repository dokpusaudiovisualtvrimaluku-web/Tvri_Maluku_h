<?php
namespace App\Http\Controllers;

use App\Models\Suku;
use App\Http\Requests\MasterDataRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SukuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Suku::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '<button class="btn btn-sm btn-info text-white edit-btn" data-id="'.$row->id.'" data-name="'.$row->name.'"><i class="fa-solid fa-pen-to-square"></i></button>';
                    $deleteBtn = '<button class="btn btn-sm btn-error text-white delete-btn" data-id="'.$row->id.'"><i class="fa-solid fa-trash"></i></button>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master.sukus.index');
    }

    public function store(MasterDataRequest $request)
    {
        Suku::create($request->validated());
        return response()->json(['success' => 'Data berhasil ditambahkan.']);
    }

    public function update(MasterDataRequest $request, $id)
    {
        $data = Suku::findOrFail($id);
        $data->update($request->validated());
        return response()->json(['success' => 'Data berhasil diubah.']);
    }

    public function destroy($id)
    {
        Suku::findOrFail($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }
}