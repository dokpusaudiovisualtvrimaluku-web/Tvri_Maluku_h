<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriSuratKeluar extends Model
{
    protected $table = "tvri_surat_keluars";

    protected $fillable = [
        "tanggal",
        "nomor",
        "tujuan",
        "perihal",
        "status",
        "file_url",
        "file_path",
    ];

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
