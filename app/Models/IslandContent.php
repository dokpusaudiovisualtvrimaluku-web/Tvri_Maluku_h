<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IslandContent extends Model
{
    /** @use HasFactory<\Database\Factories\IslandContentFactory> */
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['partner_id', 'title', 'category', 'status', 'file_path', 'notes'];
    public function partner() { return $this->belongsTo(Partner::class); }

}
