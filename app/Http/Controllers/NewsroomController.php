<?php

namespace App\Http\Controllers;

use App\Models\NewsroomItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class NewsroomController extends Controller
{
    public function index()
    {
        return response()->json(NewsroomItem::with(['reporter', 'editor', 'cameraman'])->latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'nullable|string',
            'location' => 'nullable|string',
            'keywords' => 'nullable|string',
            'status' => 'required|string|in:Draft,Proses Editing,Siap Tayang,Sudah Tayang',
            'file' => 'required|file|mimes:mp4,mov,mkv,mp3,wav,jpg,jpeg,png,doc,docx|max:100000', // max 100MB
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('newsrooms', $filename, 'public');
        }

        $newsroom = NewsroomItem::create([
            'title' => $request->title,
            'reporter_id' => Auth::id() ?? 1,
            'duration' => $request->duration,
            'location' => $request->location,
            'keywords' => $request->keywords,
            'status' => $request->status,
            'file_path' => $filePath,
        ]);

        return response()->json(['message' => 'Newsroom item created successfully', 'data' => $newsroom->load('reporter')]);
    }

    public function show(NewsroomItem $newsroom)
    {
        return response()->json($newsroom->load(['reporter', 'editor', 'cameraman']));
    }

    public function update(Request $request, NewsroomItem $newsroom)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|string',
            'duration' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $newsroom->update([
            'title' => $request->title,
            'status' => $request->status,
            'duration' => $request->duration,
            'location' => $request->location,
        ]);

        return response()->json(['message' => 'Newsroom item updated successfully', 'data' => $newsroom]);
    }

    public function destroy(NewsroomItem $newsroom)
    {
        $newsroom->delete();
        return response()->json(['message' => 'Newsroom item deleted successfully']);
    }
}
