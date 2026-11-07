<?php

namespace App\Http\Controllers;

use App\Models\IslandContentNetwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class IslandContentNetworkController extends Controller
{
    public function index(Request $request)
    {
        $query = IslandContentNetwork::query();
        if ($request->has('kategori')) {
            $query->where('kategori_submenu', $request->kategori);
        }
        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_submenu' => 'required|string',
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'file_video' => 'nullable|file|mimes:mp4,mkv,avi|max:51200', // max 50MB
            'file_pdf' => 'nullable|file|mimes:pdf|max:10240', // max 10MB
            'tautan_link' => 'nullable|url'
        ]);

        try {
            $data = $request->only(['kategori_submenu', 'judul', 'deskripsi', 'tautan_link']);

            if ($request->hasFile('file_video')) {
                $path = $request->file('file_video')->store('island_contents/videos', 'public');
                $data['file_video'] = '/storage/' . $path;
            }

            if ($request->hasFile('file_pdf')) {
                $path = $request->file('file_pdf')->store('island_contents/pdfs', 'public');
                $data['file_pdf'] = '/storage/' . $path;
            }

            $item = IslandContentNetwork::create($data);
            return response()->json($item, 201);
        } catch (\Exception $e) {
            Log::error('Error creating IslandContentNetwork: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to upload files'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $item = IslandContentNetwork::findOrFail($id);

        $request->validate([
            'kategori_submenu' => 'required|string',
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'file_video' => 'nullable|file|mimes:mp4,mkv,avi|max:51200', 
            'file_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'tautan_link' => 'nullable|url'
        ]);

        try {
            $data = $request->only(['kategori_submenu', 'judul', 'deskripsi', 'tautan_link']);

            if ($request->hasFile('file_video')) {
                // Delete old file
                if ($item->file_video && strpos($item->file_video, '/storage/') === 0) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $item->file_video));
                }
                $path = $request->file('file_video')->store('island_contents/videos', 'public');
                $data['file_video'] = '/storage/' . $path;
            }

            if ($request->hasFile('file_pdf')) {
                // Delete old file
                if ($item->file_pdf && strpos($item->file_pdf, '/storage/') === 0) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $item->file_pdf));
                }
                $path = $request->file('file_pdf')->store('island_contents/pdfs', 'public');
                $data['file_pdf'] = '/storage/' . $path;
            }

            $item->update($data);
            return response()->json($item);
        } catch (\Exception $e) {
            Log::error('Error updating IslandContentNetwork: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to upload files'], 500);
        }
    }

    public function destroy($id)
    {
        $item = IslandContentNetwork::findOrFail($id);
        
        if ($item->file_video && strpos($item->file_video, '/storage/') === 0) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $item->file_video));
        }
        if ($item->file_pdf && strpos($item->file_pdf, '/storage/') === 0) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $item->file_pdf));
        }

        $item->delete();
        return response()->json(null, 204);
    }
}
