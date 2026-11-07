<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriKampanye extends Model
{
    protected $table = "tvri_kampanyes";

    protected $fillable = ["nama", "media", "target", "realisasi", "reach", "engagement", "status"];
}
