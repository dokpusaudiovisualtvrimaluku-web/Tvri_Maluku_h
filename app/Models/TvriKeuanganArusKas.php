<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriKeuanganArusKas extends Model
{
    protected $table = 'tvri_keuangan_arus_kas';

    protected $fillable = [
        'tahun',
        'urutan',
        'bulan',
        'penerimaan',
        'pengeluaran',
        'saldo',
    ];
}
