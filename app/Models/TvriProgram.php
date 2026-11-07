<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriProgram extends Model
{
    protected $table = "tvri_programs";

    protected $fillable = ["judul", "kategori", "tahap", "target_tayang", "status", "pic", "audience", "rating"];

    protected function casts(): array
    {
        return [
        "tanggal" => "date",
        "tanggal_rencana" => "date",
        "tanggal_tayang" => "date",
        "jatuh_tempo" => "date",
        "target_tayang" => "date",
        "rating" => "decimal:1",
        "daya_kw" => "decimal:2",
        ];
    }
}
