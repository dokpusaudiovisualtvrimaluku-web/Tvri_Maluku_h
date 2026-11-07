<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriTeknikTransmitter extends Model
{
    use HasFactory;

    protected $table = 'tvri_teknik_transmitters';

    protected $guarded = ['id'];
}
