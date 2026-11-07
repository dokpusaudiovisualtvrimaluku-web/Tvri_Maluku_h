<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriPiutang extends Model
{
    protected $table = "tvri_piutangs";

    protected $fillable = ["klien", "nilai", "jatuh_tempo", "status"];

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
