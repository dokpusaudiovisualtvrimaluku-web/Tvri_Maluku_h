<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriPermintaanLayanan extends Model
{
    protected $table = "tvri_permintaan_layanans";

    protected $fillable = ["nomor", "jenis", "pemohon", "unit", "status", "tanggal"];

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
