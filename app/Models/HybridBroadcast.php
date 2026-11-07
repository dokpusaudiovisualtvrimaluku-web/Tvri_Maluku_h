<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HybridBroadcast extends Model
{
    /** @use HasFactory<\Database\Factories\HybridBroadcastFactory> */
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['platform', 'title', 'thumbnail_path', 'caption', 'hashtags', 'publish_schedule', 'status', 'views', 'likes', 'shares', 'comments'];
    protected $casts = ['publish_schedule' => 'datetime'];

}
