<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriBeritaMonitoring extends Model
{
    protected $table = 'tvri_berita_monitorings';

    protected $fillable = [
        'kode',
        'tanggal',
        'program_siaran',
        'jam_tayang',
        'presenter',
        'produser',
        'rundown_fokus',
        'jumlah_berita',
        'status_siaran',
        'kualitas_av',
        'evaluasi_catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }
}
