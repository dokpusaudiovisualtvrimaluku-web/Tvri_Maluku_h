<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisabilitasAnak extends Model
{
    protected $table = 'disabilitas_anak';

    protected $fillable = [
        'name', 'color', 'usia_0_4', 'usia_5_9', 'usia_10_14', 'usia_15_17', 'sort_order',
    ];

    protected $casts = [
        'usia_0_4' => 'int',
        'usia_5_9' => 'int',
        'usia_10_14' => 'int',
        'usia_15_17' => 'int',
    ];
}
