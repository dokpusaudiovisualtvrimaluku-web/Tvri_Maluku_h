<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalDistribution extends Model
{
    /** @use HasFactory<\Database\Factories\InternalDistributionFactory> */
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['item_type', 'item_id', 'from_unit', 'to_unit', 'status', 'notes'];

}
