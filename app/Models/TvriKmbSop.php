<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriKmbSop extends Model
{
    use HasFactory;

    protected $table = 'tvri_kmb_sops';

    protected $guarded = ['id'];
}
