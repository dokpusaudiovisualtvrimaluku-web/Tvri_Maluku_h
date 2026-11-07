<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriSarpras extends Model
{
    protected $table = "tvri_sarpras";

    protected $fillable = ["nama", "kategori", "jumlah_unit", "kondisi_baik", "lokasi"];
}
