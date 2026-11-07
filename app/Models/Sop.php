<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sop extends Model
{
    /** @use HasFactory<\Database\Factories\SopFactory> */
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['sop_number', 'title', 'category', 'document_path', 'version', 'effective_date', 'revision_history'];
    protected $casts = ['effective_date' => 'date'];

}
