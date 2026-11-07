<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriGangguan extends Model
{
    protected $table = "tvri_gangguans";

    protected $fillable = ["tanggal", "site", "jenis", "status", "keterangan"];

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
