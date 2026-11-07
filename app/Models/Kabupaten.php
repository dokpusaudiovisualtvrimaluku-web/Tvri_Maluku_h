<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kabupaten extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kecamatans(): HasMany
    {
        return $this->hasMany(Kecamatan::class);
    }
    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class);
    }
}
