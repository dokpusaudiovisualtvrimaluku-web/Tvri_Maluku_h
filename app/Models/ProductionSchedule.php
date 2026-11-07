<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\ProductionScheduleFactory> */
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['title', 'category', 'start_date', 'end_date', 'unit_kerja', 'status'];
    protected $casts = ['start_date' => 'datetime', 'end_date' => 'datetime'];

}
