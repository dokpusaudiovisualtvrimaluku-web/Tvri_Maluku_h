<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tokoh extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_pendataan' => 'date',
        'status_aktif' => 'boolean'
    ];

    public function agama(): BelongsTo { return $this->belongsTo(Agama::class); }
    public function suku(): BelongsTo { return $this->belongsTo(Suku::class); }
    public function profesi(): BelongsTo { return $this->belongsTo(Profesi::class); }
    public function pendidikan(): BelongsTo { return $this->belongsTo(Pendidikan::class); }
    public function organisasi(): BelongsTo { return $this->belongsTo(Organisasi::class); }
    public function kategori_tokoh(): BelongsTo { return $this->belongsTo(KategoriTokoh::class); }
    
    public function desa(): BelongsTo { return $this->belongsTo(Desa::class); }
    public function kecamatan(): BelongsTo { return $this->belongsTo(Kecamatan::class); }
    public function kabupaten(): BelongsTo { return $this->belongsTo(Kabupaten::class); }
    public function provinsi(): BelongsTo { return $this->belongsTo(Provinsi::class); }
    
    public function pendata(): BelongsTo { return $this->belongsTo(User::class, 'pendata_id'); }
    
    public function dokumens(): HasMany { return $this->hasMany(Dokumen::class); }
    public function verifikasis(): HasMany { return $this->hasMany(Verifikasi::class); }
}
