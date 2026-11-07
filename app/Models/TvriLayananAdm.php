<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriLayananAdm extends Model
{
    protected $table = "tvri_layanan_adms";

    protected $fillable = ["jenis", "jumlah", "keterangan"];
}
