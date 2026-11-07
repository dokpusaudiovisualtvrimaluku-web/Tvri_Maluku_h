<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriPromoRingkasan extends Model
{
    use HasFactory;

    protected $table = 'tvri_promo_ringkasans';

    protected $guarded = ['id'];

    protected $casts = [
        'distribusi_media_json' => 'array',
        'capaian_kampanye_json' => 'array',
        'top_kampanye_json' => 'array',
        'platform_digital_json' => 'array',
        'status_produksi_json' => 'array',
        'jadwal_tayang_json' => 'array',
        'agenda_promo_json' => 'array',
    ];
}
