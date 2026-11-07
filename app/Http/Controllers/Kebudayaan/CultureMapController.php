<?php

namespace App\Http\Controllers\Kebudayaan;

use App\Http\Controllers\Controller;
use App\Models\SimandatCulture;
use Illuminate\Http\Request;

class CultureMapController extends Controller
{
    public function index(Request $request)
    {
        $selectedKab = $request->get('kabupaten', 'all');
        $selectedKategori = $request->get('kategori', 'all');

        $query = SimandatCulture::whereNotNull('latitude')->whereNotNull('longitude');

        if ($selectedKab !== 'all') {
            $query->where('kabupaten_kota', $selectedKab);
        }

        if ($selectedKategori !== 'all') {
            $query->where('kategori', $selectedKategori);
        }

        $cultures = $query->get();

        $markers = $cultures->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'kategori' => $item->kategori,
                'subkategori' => $item->subkategori,
                'kabupaten_kota' => $item->kabupaten_kota,
                'lat' => (float) $item->latitude,
                'lng' => (float) $item->longitude,
                'foto' => $item->foto_url,
                'status' => $item->status,
                'url' => route('kebudayaan.cultures.show', $item->id),
            ];
        });

        $kategoriList = SimandatCulture::KATEGORI_LIST;
        $kabupatenList = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        return view('kebudayaan.map.index', [
            'markers' => $markers,
            'kategoriList' => $kategoriList,
            'kabupatenList' => $kabupatenList,
            'kabupaten' => $selectedKab,
            'kategori' => $selectedKategori,
            'totalMarkers' => $markers->count(),
        ]);
    }
}
