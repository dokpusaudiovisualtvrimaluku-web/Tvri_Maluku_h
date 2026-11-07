<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriPeralatan extends Model
{
    protected $table = "tvri_peralatans";

    protected $fillable = ["nama", "kategori", "jumlah", "jumlah_baik", "lokasi"];
}
