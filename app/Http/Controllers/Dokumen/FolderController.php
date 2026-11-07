<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use App\Models\SimandatDocumentFolder;
use App\Models\SimandatDocument;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    /**
     * Display the hierarchical folder explorer.
     */
    public function index(Request $request)
    {
        $currentFolderId = $request->get('folder_id');
        $currentFolder = null;
        $breadcrumbs = [];

        if ($currentFolderId) {
            $currentFolder = SimandatDocumentFolder::with(['children.documents', 'documents'])->findOrFail($currentFolderId);
            
            // Build breadcrumb trail
            $temp = $currentFolder;
            while ($temp) {
                array_unshift($breadcrumbs, $temp);
                $temp = $temp->parent;
            }

            $subfolders = $currentFolder->children;
            $documents = $currentFolder->documents()->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        } else {
            // Root level
            $order = [
                'SOP',
                'Tata Usaha',
                'Berita',
                'Umum',
                'Program',
                'KBM',
                'promo',
                'Pengembangan Usaha',
                'Teknik',
                'Teknik Transmisi',
                'Keuangan',
            ];

            if (SimandatDocumentFolder::count() === 0) {
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'TvriDokumenFolderSeeder']);
            }

            $subfolders = SimandatDocumentFolder::whereNull('parent_id')
                ->with(['children', 'documents'])
                ->get()
                ->sortBy(function ($f) use ($order) {
                    $idx = array_search($f->name, $order);
                    return $idx !== false ? $idx : 999;
                })
                ->values();
            
            $documents = SimandatDocument::whereNull('folder_id')
                ->orderBy('created_at', 'desc')
                ->paginate(12)
                ->withQueryString();
        }

        $orderList = $order ?? [
            'SOP', 'Tata Usaha', 'Berita', 'Umum', 'Program', 'KBM', 'promo', 'Pengembangan Usaha', 'Teknik', 'Teknik Transmisi', 'Keuangan'
        ];

        $allFolders = SimandatDocumentFolder::all()->sortBy(function ($f) use ($orderList) {
            $idx = array_search($f->name, $orderList);
            return $idx !== false ? $idx : 999;
        })->values();

        return view('dokumen.folders.index', compact('currentFolder', 'breadcrumbs', 'subfolders', 'documents', 'allFolders'));
    }

    /**
     * Create a new folder.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'parent_id' => 'nullable|exists:simandat_document_folders,id',
            'code' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $folder = SimandatDocumentFolder::create([
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'icon' => $validated['icon'] ?? 'folder',
            'color' => $validated['color'] ?? 'blue',
            'description' => $validated['description'] ?? null,
            'created_by' => $user ? $user->id : null,
        ]);

        // Audit Trail
        ActivityLogger::log(
            'FOLDER_CREATE',
            "Membuat folder arsip baru: '{$folder->name}'"
        );

        return redirect()->back()->with('success', "Folder '{$folder->name}' berhasil dibuat.");
    }

    /**
     * Update folder metadata.
     */
    public function update(Request $request, $id)
    {
        $folder = SimandatDocumentFolder::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $folder->update($validated);

        // Audit Trail
        ActivityLogger::log(
            'FOLDER_UPDATE',
            "Memperbarui folder arsip: '{$folder->name}'"
        );

        return redirect()->back()->with('success', "Folder '{$folder->name}' berhasil diperbarui.");
    }

    /**
     * Delete folder.
     */
    public function destroy($id)
    {
        $folder = SimandatDocumentFolder::findOrFail($id);
        $name = $folder->name;

        // Move documents in this folder to root before deleting
        SimandatDocument::where('folder_id', $folder->id)->update(['folder_id' => null]);

        // Audit Trail
        ActivityLogger::log(
            'FOLDER_DELETE',
            "Menghapus folder arsip: '{$name}'"
        );

        $folder->delete();

        return redirect()->route('dokumen.folders.index')->with('success', "Folder '{$name}' berhasil dihapus.");
    }
}
