<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriProgramSop extends Model
{
    protected $table = 'tvri_program_sops';

    protected $fillable = [
        'kode',
        'judul',
        'kategori',
        'suara_siaran',
        'revisi',
        'status',
        'file_url',
        'keterangan',
        'urutan',
    ];
}
