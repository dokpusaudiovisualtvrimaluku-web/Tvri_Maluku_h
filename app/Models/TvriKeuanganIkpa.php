<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriKeuanganIkpa extends Model
{
    protected $table = 'tvri_keuangan_ikpas';

    protected $fillable = [
        'tahun',
        'urutan',
        'indikator',
        'nilai',
        'bobot',
        'skor',
    ];
}
