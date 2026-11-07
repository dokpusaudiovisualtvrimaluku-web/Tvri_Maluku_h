<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatTeacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_teachers';

    protected $fillable = [
        'nip',
        'nuptk',
        'nama',
        'jenis_kelamin',
        'jabatan',
        'mata_pelajaran',
        'pangkat_golongan',
        'status_kepegawaian',
        'school_id',
        'pendidikan_terakhir',
        'sertifikasi',
        'kabupaten_kota',
        'tahun_data',
        'status_validasi',
    ];

    public function school()
    {
        return $this->belongsTo(SimandatSchool::class, 'school_id');
    }
}
