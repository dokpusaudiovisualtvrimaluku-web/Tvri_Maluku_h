<?php

namespace App\Http\Controllers;

use App\Models\DigitalArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DigitalArchiveController extends Controller
{
    public function index(Request $request)
    {
        $query = DigitalArchive::with('uploader')->latest();
        
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }
        
        if ($request->has('search') && $request->search !== '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:Kabupaten,Program,Tahunan',
            'category' => 'required|string',
            'region' => 'nullable|string',
            'year' => 'nullable|integer',
            'file' => 'required|file|mimes:pdf,mp4,mov,mkv,jpg,jpeg,png,docx,xlsx|max:50000', // 50MB max
            'tags' => 'nullable|string'
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Store in storage/app/public/archives
            $filePath = $file->storeAs('archives', $filename, 'public');
        }

        $archive = DigitalArchive::create([
            'title' => $request->title,
            'type' => $request->type,
            'category' => $request->category,
            'region' => $request->region,
            'year' => $request->year ?? date('Y'),
            'file_path' => $filePath,
            'tags' => $request->tags,
            'version' => 1,
            'uploaded_by' => Auth::id() ?? 1,
        ]);

        return response()->json(['message' => 'Archive uploaded successfully', 'data' => $archive]);
    }

    public function show(DigitalArchive $digitalArchive)
    {
        return response()->json($digitalArchive->load('uploader'));
    }

    public function update(Request $request, DigitalArchive $digitalArchive)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'tags' => 'nullable|string'
        ]);

        // Note: For simplicity, update only handles metadata, not file replacement.
        // A full implementation would handle file replacement and increment 'version'.
        
        $digitalArchive->update([
            'title' => $request->title,
            'category' => $request->category,
            'tags' => $request->tags,
        ]);

        return response()->json(['message' => 'Archive updated successfully', 'data' => $digitalArchive]);
    }

    public function destroy(DigitalArchive $digitalArchive)
    {
        // Soft delete the record. We keep the file in storage for history, 
        // or we could delete it if required by policy:
        // if ($digitalArchive->file_path) {
        //     Storage::disk('public')->delete($digitalArchive->file_path);
        // }
        
        $digitalArchive->delete();

        return response()->json(['message' => 'Archive deleted successfully']);
    }
}
