<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriAset extends Model
{
    protected $table = "tvri_asets";

    protected $fillable = ["kode", "nama", "kategori", "kondisi", "jumlah", "lokasi", "tahun", "nilai"];
}
