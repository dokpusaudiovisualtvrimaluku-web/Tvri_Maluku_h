<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriAnggaran extends Model
{
    protected $table = "tvri_anggarans";

    protected $fillable = ["kode", "uraian", "pagu", "realisasi", "tahun"];
}
