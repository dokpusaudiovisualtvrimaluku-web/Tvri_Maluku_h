<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisabilitasDewasa extends Model
{
    protected $table = 'disabilitas_dewasa';

    protected $fillable = ['disabilitas', 'pria', 'wanita', 'sort_order'];

    protected $casts = ['pria' => 'int', 'wanita' => 'int'];
}
