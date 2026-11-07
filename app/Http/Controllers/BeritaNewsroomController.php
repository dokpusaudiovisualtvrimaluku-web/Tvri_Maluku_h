<?php

namespace App\Http\Controllers;

use App\Models\BeritaNewsroom;
use Illuminate\Http\Request;

class BeritaNewsroomController extends Controller
{
    public function index()
    {
        return response()->json(BeritaNewsroom::latest()->get());
    }

    public function store(Request $request)
    {
        $data = BeritaNewsroom::create($request->all());
        return response()->json($data, 201);
    }

    public function show($id)
    {
        return response()->json(BeritaNewsroom::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $data = BeritaNewsroom::findOrFail($id);
        $data->update($request->all());
        return response()->json($data);
    }

    public function destroy($id)
    {
        BeritaNewsroom::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
