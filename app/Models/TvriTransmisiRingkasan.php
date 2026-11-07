<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriTransmisiRingkasan extends Model
{
    use HasFactory;

    protected $table = 'tvri_transmisi_ringkasans';

    protected $guarded = ['id'];

    protected $casts = [
        'platform_siaran_json' => 'array',
        'kualitas_sinyal_json' => 'array',
        'parameter_teknis_json' => 'array',
        'infrastruktur_json' => 'array',
        'kualitas_rata' => 'float',
    ];
}
