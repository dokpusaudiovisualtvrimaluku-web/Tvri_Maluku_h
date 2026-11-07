<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriAgendaKegiatan extends Model
{
    protected $table = "tvri_agenda_kegiatans";

    protected $fillable = ["tanggal", "waktu", "kegiatan", "lokasi", "pic", "jenis", "status"];

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
