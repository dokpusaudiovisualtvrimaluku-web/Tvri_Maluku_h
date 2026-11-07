<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IslandContentNetwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_submenu',
        'judul',
        'deskripsi',
        'file_video',
        'file_pdf',
        'tautan_link'
    ];
}
