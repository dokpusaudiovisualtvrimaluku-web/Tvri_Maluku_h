<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriBeritaCatatan extends Model
{
    protected $table = 'tvri_berita_catatans';

    protected $fillable = [
        'tipe',
        'pesan',
        'urutan',
    ];
}
