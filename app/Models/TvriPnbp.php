<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriPnbp extends Model
{
    protected $table = "tvri_pnbps";

    protected $fillable = ["jenis", "target", "realisasi", "tahun"];
}
