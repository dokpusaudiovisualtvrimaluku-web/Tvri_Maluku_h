<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriMitra extends Model
{
    protected $table = "tvri_mitras";

    protected $fillable = ["nama", "jenis_kerja_sama", "nilai_kontrak", "status", "lokasi"];
}
