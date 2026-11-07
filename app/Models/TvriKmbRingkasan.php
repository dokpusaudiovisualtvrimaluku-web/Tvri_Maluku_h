<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriKmbRingkasan extends Model
{
    use HasFactory;

    protected $table = 'tvri_kmb_ringkasans';

    protected $guarded = ['id'];

    protected $casts = [
        'kalender_json' => 'array',
        'notifikasi_json' => 'array',
        'footer_points_json' => 'array',
    ];
}
