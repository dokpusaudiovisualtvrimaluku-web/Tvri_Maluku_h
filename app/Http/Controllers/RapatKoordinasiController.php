<?php

namespace App\Http\Controllers;

use App\Models\RapatKoordinasi;
use Illuminate\Http\Request;

class RapatKoordinasiController extends Controller
{
    public function index()
    {
        return response()->json(RapatKoordinasi::latest()->get());
    }

    public function store(Request $request)
    {
        $data = RapatKoordinasi::create($request->all());
        return response()->json($data, 201);
    }

    public function show($id)
    {
        return response()->json(RapatKoordinasi::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $data = RapatKoordinasi::findOrFail($id);
        $data->update($request->all());
        return response()->json($data);
    }

    public function destroy($id)
    {
        RapatKoordinasi::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
