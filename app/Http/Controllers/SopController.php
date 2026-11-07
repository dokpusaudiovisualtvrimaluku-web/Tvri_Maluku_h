<?php

namespace App\Http\Controllers;

use App\Models\Sop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SopController extends Controller
{
    public function index()
    {
        return response()->json(Sop::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'sop_number' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf|max:10000',
            'version' => 'nullable|string',
            'effective_date' => 'nullable|date',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('sops', $filename, 'public');
        }

        $sop = Sop::create([
            'sop_number' => $request->sop_number,
            'title' => $request->title,
            'category' => $request->category,
            'document_path' => $filePath,
            'version' => $request->version ?? '1.0',
            'effective_date' => $request->effective_date ?? date('Y-m-d'),
        ]);

        return response()->json(['message' => 'SOP created successfully', 'data' => $sop]);
    }

    public function show(Sop $sop)
    {
        return response()->json($sop);
    }

    public function update(Request $request, Sop $sop)
    {
        $request->validate([
            'sop_number' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'version' => 'nullable|string',
            'effective_date' => 'nullable|date',
        ]);

        $sop->update([
            'sop_number' => $request->sop_number,
            'title' => $request->title,
            'category' => $request->category,
            'version' => $request->version ?? $sop->version,
            'effective_date' => $request->effective_date ?? $sop->effective_date,
        ]);

        return response()->json(['message' => 'SOP updated successfully', 'data' => $sop]);
    }

    public function destroy(Sop $sop)
    {
        $sop->delete();
        return response()->json(['message' => 'SOP deleted successfully']);
    }
}
