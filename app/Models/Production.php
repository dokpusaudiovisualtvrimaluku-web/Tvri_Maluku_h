<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    /** @use HasFactory<\Database\Factories\ProductionFactory> */
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['type', 'title', 'target_date', 'realization_date', 'status', 'percentage', 'notes'];

}
