<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriKeuanganRingkasan extends Model
{
    protected $table = 'tvri_keuangan_ringkasans';

    protected $fillable = [
        'tahun',
        'pagu_anggaran',
        'realisasi_anggaran',
        'target_pnbp',
        'realisasi_pnbp',
        'piutang_tagihan',
        'ikpa_skor',
        'ikpa_predikat',
        'aset_lancar',
        'aset_tetap',
        'aset_lainnya',
        'kewajiban_lancar',
        'kewajiban_panjang',
        'ekuitas',
    ];
}
