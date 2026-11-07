<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriStasiunPemancar extends Model
{
    protected $table = "tvri_stasiun_pemancars";

    protected $fillable = ["nama", "lokasi", "frekuensi", "kanal", "daya_kw", "coverage", "status", "kualitas", "latitude", "longitude"];
}
