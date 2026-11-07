<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatSchool extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_schools';

    protected $fillable = [
        'npsn',
        'nama',
        'nama_sekolah',
        'jenjang',
        'status_sekolah',
        'cabang',
        'alamat',
        'desa_kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'kepala_sekolah',
        'jumlah_guru',
        'jumlah_siswa',
        'akreditasi',
        'siswa',
        'latitude',
        'longitude',
        'tahun_data',
        'status_validasi',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($school) {
            if (!empty($school->nama_sekolah) && empty($school->nama)) {
                $school->nama = $school->nama_sekolah;
            } elseif (!empty($school->nama) && empty($school->nama_sekolah)) {
                $school->nama_sekolah = $school->nama;
            }
            if (empty($school->cabang)) {
                $school->cabang = $school->kabupaten_kota ?? 'Maluku';
            }
        });
    }

    public function getNamaSekolahAttribute($value)
    {
        return $value ?: $this->nama;
    }

    public function getJumlahPesertaDidikAttribute($value)
    {
        return $value ?: $this->jumlah_siswa ?: $this->siswa;
    }

    /**
     * Relationship: Guru & Tenaga Kependidikan
     */
    public function teachers()
    {
        return $this->hasMany(SimandatTeacher::class, 'school_id');
    }

    /**
     * Relationship: Peserta Didik
     */
    public function students()
    {
        return $this->hasMany(SimandatStudent::class, 'school_id');
    }

    /**
     * Relationship: Sarana Prasarana
     */
    public function sarpras()
    {
        return $this->hasMany(SimandatSarpras::class, 'school_id');
    }

    /**
     * Relationship: Arsip & Dokumen
     */
    public function archives()
    {
        return $this->hasMany(SimandatArchive::class, 'module_id', 'npsn');
    }

    /**
     * Relationship: DMS Dokumen Digital
     */
    public function documents()
    {
        return $this->hasMany(SimandatDocument::class, 'school_id');
    }
}

