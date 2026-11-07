<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatStudent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_students';

    protected $fillable = [
        'nisn',
        'nama',
        'nik',
        'jenis_kelamin',
        'school_id',
        'jenjang',
        'tingkat_kelas',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_ibu_kandung',
        'kabupaten_kota',
        'tahun_ajaran',
        'status',
        'status_validasi',
    ];

    public function school()
    {
        return $this->belongsTo(SimandatSchool::class, 'school_id');
    }
}
