<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use Illuminate\Http\Request;

class PenugasanController extends Controller
{
    public function index()
    {
        return response()->json(Penugasan::latest()->get());
    }

    public function store(Request $request)
    {
        $data = Penugasan::create($request->all());
        return response()->json($data, 201);
    }

    public function show($id)
    {
        return response()->json(Penugasan::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $data = Penugasan::findOrFail($id);
        $data->update($request->all());
        return response()->json($data);
    }

    public function destroy($id)
    {
        Penugasan::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
