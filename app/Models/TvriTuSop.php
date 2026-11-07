<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriTuSop extends Model
{
    use HasFactory;

    protected $table = 'tvri_tu_sops';

    protected $guarded = ['id'];
}
