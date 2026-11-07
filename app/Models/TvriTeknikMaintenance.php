<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriTeknikMaintenance extends Model
{
    use HasFactory;

    protected $table = 'tvri_teknik_maintenances';

    protected $guarded = ['id'];
}
