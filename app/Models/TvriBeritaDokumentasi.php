<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriBeritaDokumentasi extends Model
{
    protected $table = 'tvri_berita_dokumentasis';

    protected $fillable = [
        'kode',
        'tanggal_liputan',
        'judul_dokumentasi',
        'lokasi',
        'crew',
        'jenis_media',
        'format_file',
        'ukuran_file',
        'link_media',
        'status_arsip',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_liputan' => 'date',
        ];
    }
}
