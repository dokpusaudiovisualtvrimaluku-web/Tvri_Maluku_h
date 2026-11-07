<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarqueeAnnouncement extends Model
{
    protected $table = 'marquee_announcements';

    protected $fillable = ['announcement', 'sort_order'];
}
