<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriKegiatanUsaha extends Model
{
    protected $table = "tvri_kegiatan_usahas";

    protected $fillable = ["tanggal", "kegiatan", "jenis", "estimasi_nilai", "status"];

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
