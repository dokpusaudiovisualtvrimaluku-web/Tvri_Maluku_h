<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriBeritaProduksi extends Model
{
    protected $table = 'tvri_berita_produksis';

    protected $fillable = [
        'kode',
        'judul',
        'program',
        'kategori',
        'reporter',
        'kamerawan',
        'editor',
        'durasi',
        'tanggal_produksi',
        'target_tayang',
        'status',
        'prioritas',
        'catatan',
        'video_url',
        'naskah_file',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_produksi' => 'date',
            'target_tayang' => 'date',
        ];
    }
}
