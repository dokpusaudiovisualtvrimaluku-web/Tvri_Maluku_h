<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    /** @use HasFactory<\Database\Factories\AssignmentFactory> */
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['assignment_number', 'pic_id', 'location', 'agenda', 'target', 'deadline', 'status', 'progress'];
    public function pic() { return $this->belongsTo(User::class, 'pic_id'); }

}
