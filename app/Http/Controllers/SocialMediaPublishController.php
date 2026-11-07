<?php

namespace App\Http\Controllers;

use App\Models\SocialMediaPublish;
use Illuminate\Http\Request;

class SocialMediaPublishController extends Controller
{
    public function index()
    {
        return response()->json(SocialMediaPublish::latest()->get());
    }

    public function store(Request $request)
    {
        $data = SocialMediaPublish::create($request->all());
        return response()->json($data, 201);
    }

    public function show($id)
    {
        return response()->json(SocialMediaPublish::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $data = SocialMediaPublish::findOrFail($id);
        $data->update($request->all());
        return response()->json($data);
    }

    public function destroy($id)
    {
        SocialMediaPublish::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
