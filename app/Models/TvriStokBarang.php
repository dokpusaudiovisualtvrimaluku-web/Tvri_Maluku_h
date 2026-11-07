<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriStokBarang extends Model
{
    protected $table = "tvri_stok_barangs";

    protected $fillable = ["kategori", "nama", "jumlah", "satuan", "status_stok"];
}
