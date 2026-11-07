<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatUptdBtkp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_uptd_btkp';

    protected $fillable = [
        'kode',
        'nama_program',
        'penanggung_jawab',
        'sasaran',
        'platform',
        'anggaran',
        'status',
        'deskripsi',
    ];

    protected $casts = [
        'anggaran' => 'integer',
    ];
}
