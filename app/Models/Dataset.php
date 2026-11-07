<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    protected $table = 'datasets';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id', 'slug', 'title', 'organization_id', 'urusan_id', 'date',
        'views', 'downloads', 'description', 'tags', 'columns', 'rows', 'chart_config',
    ];

    protected $casts = [
        'id' => 'int',
        'urusan_id' => 'int',
        'views' => 'int',
        'downloads' => 'int',
        'tags' => 'array',
        'columns' => 'array',
        'rows' => 'array',
        'chart_config' => 'array',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function urusan()
    {
        return $this->belongsTo(Urusan::class, 'urusan_id');
    }
}
