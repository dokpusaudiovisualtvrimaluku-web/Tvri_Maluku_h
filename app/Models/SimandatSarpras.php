<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatSarpras extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_sarpras';

    protected $fillable = [
        'school_id',
        'nama_sarpras',
        'jenis_sarpras',
        'jumlah',
        'kondisi_baik',
        'rusak_ringan',
        'rusak_berat',
        'keterangan',
        'kabupaten_kota',
        'tahun_data',
        'status_validasi',
    ];

    public function school()
    {
        return $this->belongsTo(SimandatSchool::class, 'school_id');
    }
}
