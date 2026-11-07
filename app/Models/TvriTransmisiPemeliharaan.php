<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriTransmisiPemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'tvri_transmisi_pemeliharaans';

    protected $guarded = ['id'];
}
