<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmaJenisLayanan extends Model
{
    protected $fillable = [
        'nama_layanan',
        'seksi',
        'prefix',
        'deskripsi',
        'icon',
        'badge'
    ];
}
