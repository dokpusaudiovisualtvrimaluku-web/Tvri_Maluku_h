<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriProgramHighlight extends Model
{
    protected $table = 'tvri_program_highlights';

    protected $fillable = [
        'judul',
        'tagline',
        'icon',
        'urutan',
    ];
}
