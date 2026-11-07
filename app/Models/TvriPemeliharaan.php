<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriPemeliharaan extends Model
{
    protected $table = "tvri_pemeliharaans";

    protected $fillable = ["bidang", "tanggal", "objek", "jenis", "pic", "status"];

    protected function casts(): array
    {
        return [
        "tanggal" => "date",
        "tanggal_rencana" => "date",
        "tanggal_tayang" => "date",
        "jatuh_tempo" => "date",
        "target_tayang" => "date",
        ];
    }
}
