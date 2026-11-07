<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimandatCultureGallery extends Model
{
    use HasFactory;

    protected $table = 'simandat_culture_galleries';

    protected $fillable = [
        'culture_id',
        'media_type',
        'file_path',
        'caption',
        'is_primary',
    ];

    public function culture()
    {
        return $this->belongsTo(SimandatCulture::class, 'culture_id');
    }

    public function getFileUrlAttribute()
    {
        if (str_starts_with($this->file_path, 'http')) {
            return $this->file_path;
        }
        return asset('storage/' . $this->file_path);
    }
}
