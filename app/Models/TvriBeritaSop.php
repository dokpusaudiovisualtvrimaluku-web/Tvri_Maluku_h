<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriBeritaSop extends Model
{
    use HasFactory;

    protected $table = 'tvri_berita_sops';

    protected $guarded = ['id'];
}
