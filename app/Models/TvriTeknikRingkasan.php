<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriTeknikRingkasan extends Model
{
    use HasFactory;

    protected $table = 'tvri_teknik_ringkasans';

    protected $guarded = ['id'];

    protected $casts = [
        'readiness_items_json' => 'array',
        'status_studio_json' => 'array',
        'inventaris_json' => 'array',
        'footer_points_json' => 'array',
    ];
}
