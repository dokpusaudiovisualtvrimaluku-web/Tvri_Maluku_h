<?php

namespace App\Http\Controllers;

use App\Models\SopPerencanaan;
use Illuminate\Http\Request;

class SopPerencanaanController extends Controller
{
    public function index()
    {
        return response()->json(SopPerencanaan::latest()->get());
    }

    public function store(Request $request)
    {
        $data = SopPerencanaan::create($request->all());
        return response()->json($data, 201);
    }

    public function show($id)
    {
        return response()->json(SopPerencanaan::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $data = SopPerencanaan::findOrFail($id);
        $data->update($request->all());
        return response()->json($data);
    }

    public function destroy($id)
    {
        SopPerencanaan::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
