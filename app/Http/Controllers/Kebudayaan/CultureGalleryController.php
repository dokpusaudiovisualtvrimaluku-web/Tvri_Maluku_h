<?php

namespace App\Http\Controllers\Kebudayaan;

use App\Http\Controllers\Controller;
use App\Models\SimandatCulture;
use App\Models\SimandatCultureGallery;
use Illuminate\Http\Request;

class CultureGalleryController extends Controller
{
    public function index(Request $request)
    {
        $selectedMediaType = $request->get('type', 'all');
        $selectedKategori = $request->get('kategori', 'all');

        $query = SimandatCulture::query();

        if ($selectedKategori !== 'all') {
            $query->where('kategori', $selectedKategori);
        }

        if ($selectedMediaType === 'photo') {
            $query->whereNotNull('foto');
        } elseif ($selectedMediaType === 'video') {
            $query->whereNotNull('video_url');
        } elseif ($selectedMediaType === 'audio') {
            $query->whereNotNull('audio_url');
        } elseif ($selectedMediaType === 'pdf') {
            $query->whereNotNull('dokumen_pendukung');
        }

        $cultures = $query->with('galleries')->latest()->paginate(12)->withQueryString();

        $kategoriList = SimandatCulture::KATEGORI_LIST;

        $stats = [
            'total_foto' => SimandatCulture::whereNotNull('foto')->count(),
            'total_video' => SimandatCulture::whereNotNull('video_url')->count(),
            'total_audio' => SimandatCulture::whereNotNull('audio_url')->count(),
            'total_dokumen' => SimandatCulture::whereNotNull('dokumen_pendukung')->count(),
        ];

        return view('kebudayaan.gallery.index', [
            'cultures' => $cultures,
            'kategoriList' => $kategoriList,
            'selectedType' => $selectedMediaType,
            'selectedKategori' => $selectedKategori,
            'stats' => $stats,
        ]);
    }
}
