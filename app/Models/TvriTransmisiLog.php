<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriTransmisiLog extends Model
{
    use HasFactory;

    protected $table = 'tvri_transmisi_logs';

    protected $guarded = ['id'];
}
