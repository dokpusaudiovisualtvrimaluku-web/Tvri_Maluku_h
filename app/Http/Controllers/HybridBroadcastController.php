<?php

namespace App\Http\Controllers;

use App\Models\HybridBroadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HybridBroadcastController extends Controller
{
    public function index()
    {
        return response()->json(HybridBroadcast::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'platform' => 'required|string',
            'caption' => 'nullable|string',
            'hashtag' => 'nullable|string',
            'schedule_time' => 'nullable|date',
            'file' => 'required|file|mimes:mp4,mov,mkv,jpg,png|max:200000', // max 200MB
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('hybrid_broadcasts', $filename, 'public');
        }

        $hybrid = HybridBroadcast::create([
            'platform' => $request->platform,
            'caption' => $request->caption,
            'hashtag' => $request->hashtag,
            'schedule_time' => $request->schedule_time,
            'status' => 'Pending',
            'file_path' => $filePath,
        ]);

        return response()->json(['message' => 'Hybrid Broadcast uploaded successfully', 'data' => $hybrid]);
    }

    public function show(HybridBroadcast $hybridBroadcast)
    {
        return response()->json($hybridBroadcast);
    }

    public function update(Request $request, HybridBroadcast $hybridBroadcast)
    {
        $request->validate([
            'caption' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $hybridBroadcast->update([
            'caption' => $request->caption,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Hybrid Broadcast updated successfully', 'data' => $hybridBroadcast]);
    }

    public function destroy(HybridBroadcast $hybridBroadcast)
    {
        $hybridBroadcast->delete();
        return response()->json(['message' => 'Hybrid Broadcast deleted successfully']);
    }
}
