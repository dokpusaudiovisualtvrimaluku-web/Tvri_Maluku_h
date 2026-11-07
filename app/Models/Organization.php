<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organizations';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'name', 'description', 'phone', 'address',
        'website', 'email', 'logo', 'dataset_count', 'sort_order',
    ];
}
