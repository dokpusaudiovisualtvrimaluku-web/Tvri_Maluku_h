<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    public function index()
    {
        return response()->json(Mitra::latest()->get());
    }

    public function store(Request $request)
    {
        $data = Mitra::create($request->all());
        return response()->json($data, 201);
    }

    public function show($id)
    {
        return response()->json(Mitra::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $data = Mitra::findOrFail($id);
        $data->update($request->all());
        return response()->json($data);
    }

    public function destroy($id)
    {
        Mitra::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
