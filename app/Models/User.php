<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'avatar',
        'status',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function getRoleNameAttribute(): string
    {
        if ($this->username === 'tu' || str_contains(strtolower($this->name ?? ''), 'tata usaha')) {
            return '';
        }
        $role = $this->roles->first();
        if ($role) {
            if (strtoupper(str_replace(' ', '_', $role->name)) === 'KEPALA_DINAS') {
                return '';
            }
            return $role->name;
        }
        return 'VIEWER';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(public_path('storage/' . $this->avatar))) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=003B70&color=FFFFFF&bold=true';
    }

    public function isActive(): bool
    {
        return strtoupper($this->status) === 'ACTIVE';
    }

    public function kabupaten(): BelongsTo { return $this->belongsTo(Kabupaten::class); }
    public function kecamatan(): BelongsTo { return $this->belongsTo(Kecamatan::class); }
    public function desa(): BelongsTo { return $this->belongsTo(Desa::class); }
    public function tokohs_pendata(): HasMany { return $this->hasMany(Tokoh::class, 'pendata_id'); }
}
