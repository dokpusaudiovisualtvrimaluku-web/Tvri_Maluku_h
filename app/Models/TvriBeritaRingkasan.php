<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriBeritaRingkasan extends Model
{
    protected $table = 'tvri_berita_ringkasans';

    protected $fillable = [
        'periode',
        'rencana_liputan',
        'liputan_dilakukan',
        'berita_diproduksi',
        'berita_tayang',
        'views_digital',
        'performance_index',
        'kecepatan_publikasi_menit',
        'dist_tv',
        'dist_website',
        'dist_youtube',
        'dist_instagram',
        'dist_tiktok',
        'kualitas_fakta',
        'kualitas_etik',
        'kualitas_akurasi',
        'kualitas_visual',
        'kualitas_sop',
    ];

    protected $attributes = [
        'rencana_liputan' => 120,
        'liputan_dilakukan' => 102,
        'berita_diproduksi' => 95,
        'berita_tayang' => 92,
        'views_digital' => 1200000,
        'performance_index' => 92,
        'kecepatan_publikasi_menit' => 45,
        'dist_tv' => 92,
        'dist_website' => 95,
        'dist_youtube' => 82,
        'dist_instagram' => 60,
        'dist_tiktok' => 48,
        'kualitas_fakta' => 98,
        'kualitas_etik' => 96,
        'kualitas_akurasi' => 97,
        'kualitas_visual' => 94,
        'kualitas_sop' => 95,
    ];
}
