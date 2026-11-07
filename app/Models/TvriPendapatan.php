<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriPendapatan extends Model
{
    protected $table = "tvri_pendapatans";

    protected $fillable = ["sumber", "bulan", "tahun", "target", "realisasi"];
}
