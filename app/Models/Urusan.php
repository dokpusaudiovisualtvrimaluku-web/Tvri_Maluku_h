<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Urusan extends Model
{
    protected $table = 'urusans';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = ['id', 'name', 'sort_order'];
}
