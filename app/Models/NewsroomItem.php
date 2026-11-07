<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsroomItem extends Model
{
    /** @use HasFactory<\Database\Factories\NewsroomItemFactory> */
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['title', 'reporter_id', 'editor_id', 'cameraman_id', 'duration', 'location', 'keywords', 'metadata', 'status', 'file_path'];
    protected $casts = ['metadata' => 'array'];
    public function reporter() { return $this->belongsTo(User::class, 'reporter_id'); }
    public function editor() { return $this->belongsTo(User::class, 'editor_id'); }
    public function cameraman() { return $this->belongsTo(User::class, 'cameraman_id'); }

}
