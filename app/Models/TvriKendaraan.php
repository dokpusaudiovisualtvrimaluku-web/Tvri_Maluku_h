<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriKendaraan extends Model
{
    protected $table = "tvri_kendaraans";

    protected $fillable = ["jenis", "nama", "plat_nomor", "status"];
}
