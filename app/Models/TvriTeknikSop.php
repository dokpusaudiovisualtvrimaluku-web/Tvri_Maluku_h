<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriTeknikSop extends Model
{
    use HasFactory;

    protected $table = 'tvri_teknik_sops';

    protected $guarded = ['id'];
}
