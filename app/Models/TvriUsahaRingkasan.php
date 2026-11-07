<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriUsahaRingkasan extends Model
{
    use HasFactory;

    protected $table = 'tvri_usaha_ringkasans';

    protected $guarded = ['id'];

    protected $casts = [
        'sudah_berjalan_json' => 'array',
        'dalam_proses_json' => 'array',
        'rencana_selanjutnya_json' => 'array',
    ];
}
