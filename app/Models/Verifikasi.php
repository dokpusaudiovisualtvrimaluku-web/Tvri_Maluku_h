<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Verifikasi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tokoh(): BelongsTo
    {
        return $this->belongsTo(Tokoh::class);
    }
    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }
}
