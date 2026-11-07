<?php

namespace App\Http\Controllers;

use App\Models\KalenderProduksi;
use Illuminate\Http\Request;

class KalenderProduksiController extends Controller
{
    public function index()
    {
        return response()->json(KalenderProduksi::latest()->get());
    }

    public function store(Request $request)
    {
        $data = KalenderProduksi::create($request->all());
        return response()->json($data, 201);
    }

    public function show($id)
    {
        return response()->json(KalenderProduksi::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $data = KalenderProduksi::findOrFail($id);
        $data->update($request->all());
        return response()->json($data);
    }

    public function destroy($id)
    {
        KalenderProduksi::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
