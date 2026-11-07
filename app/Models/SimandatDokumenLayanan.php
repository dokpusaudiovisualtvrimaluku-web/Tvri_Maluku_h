<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimandatDokumenLayanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori',
        'nama_dokumen',
        'keterangan',
        'file_path',
        'file_type',
        'file_size',
        'user_id',
        'petugas_nama',
    ];

    public const KATEGORI_LIST = [
        'Surat Masuk',
        'Surat Keluar',
        'Kepegawaian',
        'Cuti',
        'Kenaikan Pangkat',
        'KGB',
        'Perjalanan Dinas',
        'Kearsipan',
        'Umum',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
