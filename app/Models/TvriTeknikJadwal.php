<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriTeknikJadwal extends Model
{
    use HasFactory;

    protected $table = 'tvri_teknik_jadwals';

    protected $guarded = ['id'];
}
