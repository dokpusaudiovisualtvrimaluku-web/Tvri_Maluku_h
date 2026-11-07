<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriKeuanganSop extends Model
{
    use HasFactory;

    protected $table = 'tvri_keuangan_sops';

    protected $guarded = ['id'];
}
