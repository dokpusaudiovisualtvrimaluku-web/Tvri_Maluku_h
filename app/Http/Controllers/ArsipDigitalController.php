<?php

namespace App\Http\Controllers;

use App\Models\ArsipDigital;
use Illuminate\Http\Request;

class ArsipDigitalController extends Controller
{
    public function index()
    {
        return response()->json(ArsipDigital::latest()->get());
    }

    public function store(Request $request)
    {
        $data = ArsipDigital::create($request->all());
        return response()->json($data, 201);
    }

    public function show($id)
    {
        return response()->json(ArsipDigital::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $data = ArsipDigital::findOrFail($id);
        $data->update($request->all());
        return response()->json($data);
    }

    public function destroy($id)
    {
        ArsipDigital::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
