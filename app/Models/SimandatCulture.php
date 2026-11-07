<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatCulture extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_cultures';

    protected $fillable = [
        'nama',
        'kategori',
        'subkategori',
        'deskripsi',
        'lokasi',
        'desa_kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'tahun',
        'status',
        'latitude',
        'longitude',
        'foto',
        'video_url',
        'audio_url',
        'dokumen_pendukung',
        'link_eksternal',
        'kata_kunci',
        'status_validasi',
    ];

    /**
     * The 16 standard Maluku cultural categories
     */
    public const KATEGORI_LIST = [
        'Cagar Budaya',
        'Warisan Budaya',
        'Seni Tradisional',
        'Tarian Tradisional',
        'Musik Tradisional',
        'Bahasa Daerah',
        'Adat Istiadat',
        'Permainan Tradisional',
        'Kuliner Tradisional',
        'Kerajinan',
        'Tokoh Budaya',
        'Sanggar Seni',
        'Komunitas Budaya',
        'Museum',
        'Situs Budaya',
        'Festival/Event Budaya',
    ];

    /**
     * Relationship: Galeri Multimedia
     */
    public function galleries()
    {
        return $this->hasMany(SimandatCultureGallery::class, 'culture_id');
    }

    /**
     * Relationship: Arsip & Dokumen Digital (Tahap 5 Ready)
     */
    public function archives()
    {
        return $this->hasMany(SimandatArchive::class, 'module_id', 'id');
    }

    /**
     * Relationship: DMS Dokumen Digital
     */
    public function documents()
    {
        return $this->hasMany(SimandatDocument::class, 'culture_id');
    }

    /**
     * Accessor: Foto URL
     */
    public function getFotoUrlAttribute()
    {
        if (!empty($this->foto)) {
            if (str_starts_with($this->foto, 'http')) {
                return $this->foto;
            }
            return asset('storage/' . $this->foto);
        }
        return 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=800&q=80';
    }
}
