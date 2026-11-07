<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatUptdTamanBudaya extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_uptd_taman_budaya';

    protected $fillable = [
        'kode',
        'nama_kegiatan',
        'jenis',
        'lokasi',
        'sanggar',
        'peserta',
        'status',
        'deskripsi',
    ];

    protected $casts = [
        'peserta' => 'integer',
    ];
}
