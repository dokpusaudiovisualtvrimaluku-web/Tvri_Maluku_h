<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriBeritaAgendaLiputan extends Model
{
    protected $table = 'tvri_berita_agenda_liputans';

    protected $fillable = [
        'kode',
        'tanggal',
        'waktu',
        'mulai',
        'selesai',
        'durasi_label',
        'agenda',
        'jenis',
        'kategori',
        'lokasi',
        'pic',
        'kendaraan',
        'reporter',
        'kamerawan',
        'driver',
        'koordinator',
        'status',
        'prioritas',
        'catatan',
        'link',
        'urutan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
