<?php
namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Http\Request;

class RegionAjaxController extends Controller
{
    public function getKabupaten(Request $request) {
        return Kabupaten::where('provinsi_id', $request->provinsi_id)->orderBy('name')->get();
    }
    public function getKecamatan(Request $request) {
        return Kecamatan::where('kabupaten_id', $request->kabupaten_id)->orderBy('name')->get();
    }
    public function getDesa(Request $request) {
        return Desa::where('kecamatan_id', $request->kecamatan_id)->orderBy('name')->get();
    }
}