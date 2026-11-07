<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriMateriPromo extends Model
{
    protected $table = "tvri_materi_promos";

    protected $fillable = ["nama", "jenis", "durasi_detik", "status"];
}
