<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalArchive extends Model
{
    /** @use HasFactory<\Database\Factories\DigitalArchiveFactory> */
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['title', 'type', 'category', 'region', 'year', 'file_path', 'tags', 'version', 'uploaded_by'];
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }

}
