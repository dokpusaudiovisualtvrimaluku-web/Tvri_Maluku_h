<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriInsidenTeknik extends Model
{
    protected $table = "tvri_insiden_tekniks";

    protected $fillable = ["tanggal", "lokasi", "deskripsi", "status"];

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
