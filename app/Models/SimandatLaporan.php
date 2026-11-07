<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatLaporan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_laporans';

    protected $fillable = [
        'nomor_laporan',
        'kategori_laporan',
        'judul_laporan',
        'tanggal_laporan',
        'pembuat_laporan',
        'wilayah',
        'keterangan',
        'status',
        'file_dokumen',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
