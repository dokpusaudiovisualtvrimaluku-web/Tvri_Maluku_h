<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriBeritaRealtimeFeed extends Model
{
    protected $table = 'tvri_berita_realtime_feeds';

    protected $fillable = [
        'waktu',
        'kegiatan',
        'urutan',
        'status',
    ];
}
