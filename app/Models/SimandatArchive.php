<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatArchive extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_archives';

    protected $fillable = [
        'code',
        'title',
        'module_id',
        'category',
        'description',
        'budget',
        'pic',
        'file_name',
        'file_path',
        'format',
        'file_size',
        'day',
        'date',
        'time',
        'uploader',
        'status',
        'is_protected',
    ];

    protected $casts = [
        'is_protected' => 'boolean',
        'budget' => 'integer',
    ];
}
