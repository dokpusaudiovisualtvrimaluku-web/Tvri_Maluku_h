<?php

namespace App\Http\Controllers;

use App\Models\DistribusiKonten;
use Illuminate\Http\Request;

class DistribusiKontenController extends Controller
{
    public function index()
    {
        return response()->json(DistribusiKonten::latest()->get());
    }

    public function store(Request $request)
    {
        $data = DistribusiKonten::create($request->all());
        return response()->json($data, 201);
    }

    public function show($id)
    {
        return response()->json(DistribusiKonten::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $data = DistribusiKonten::findOrFail($id);
        $data->update($request->all());
        return response()->json($data);
    }

    public function destroy($id)
    {
        DistribusiKonten::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
