<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriTransmisiSop extends Model
{
    use HasFactory;

    protected $table = 'tvri_transmisi_sops';

    protected $guarded = ['id'];
}
