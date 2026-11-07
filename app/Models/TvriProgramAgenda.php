<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriProgramAgenda extends Model
{
    protected $table = 'tvri_program_agendas';

    protected $fillable = [
        'tanggal',
        'kegiatan',
        'tipe',
        'urutan',
    ];
}
