<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    /** @use HasFactory<\Database\Factories\MeetingFactory> */
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['title', 'schedule', 'agenda', 'minutes_of_meeting', 'decisions', 'follow_up', 'attachment_path'];
    protected $casts = ['schedule' => 'datetime'];

}
