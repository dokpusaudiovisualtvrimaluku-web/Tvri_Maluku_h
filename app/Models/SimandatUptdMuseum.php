<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatUptdMuseum extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_uptd_museum';

    protected $fillable = [
        'no_inventaris',
        'nama_koleksi',
        'kategori',
        'asal',
        'bahan',
        'kondisi',
        'tahun_perolehan',
        'lokasi_simpan',
        'deskripsi',
    ];
}
