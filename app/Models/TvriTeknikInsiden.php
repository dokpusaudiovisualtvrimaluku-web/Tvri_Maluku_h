<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriTeknikInsiden extends Model
{
    use HasFactory;

    protected $table = 'tvri_teknik_insidens';

    protected $guarded = ['id'];
}
