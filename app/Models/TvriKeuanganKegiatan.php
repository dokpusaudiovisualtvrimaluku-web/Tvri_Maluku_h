<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriKeuanganKegiatan extends Model
{
    protected $table = 'tvri_keuangan_kegiatans';

    protected $fillable = [
        'tahun',
        'no_urut',
        'kegiatan',
        'target',
        'realisasi',
        'status',
        'badge',
    ];
}
