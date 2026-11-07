<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriPromoSop extends Model
{
    use HasFactory;

    protected $table = 'tvri_promo_sops';

    protected $guarded = ['id'];
}
