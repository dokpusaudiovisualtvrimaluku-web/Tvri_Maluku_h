<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriUsahaSop extends Model
{
    use HasFactory;

    protected $table = 'tvri_usaha_sops';

    protected $guarded = ['id'];
}
