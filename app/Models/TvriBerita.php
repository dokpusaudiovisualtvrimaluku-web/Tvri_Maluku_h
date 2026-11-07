<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriBerita extends Model
{
    protected $table = "tvri_beritas";

    protected $fillable = ["judul", "kategori", "jenis", "lokasi", "status", "reporter", "tanggal_rencana", "tanggal_tayang", "views", "durasi", "video_file"];

    public function getVideoUrlAttribute(): ?string
    {
        if (!$this->video_file) {
            return null;
        }
        if (str_starts_with($this->video_file, 'http://') || str_starts_with($this->video_file, 'https://')) {
            return $this->video_file;
        }
        return '/storage/' . ltrim($this->video_file, '/');
    }

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
