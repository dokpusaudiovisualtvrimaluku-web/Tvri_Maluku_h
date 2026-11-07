<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = ['payload' => 'array'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
