<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvriProgramRingkasan extends Model
{
    protected $table = 'tvri_program_ringkasans';

    protected $fillable = [
        'periode',
        'total_direncanakan',
        'diff_total',
        'comp_total',
        'dalam_produksi',
        'diff_produksi',
        'selesai_produksi',
        'diff_selesai',
        'tayang',
        'diff_tayang',
        'terlambat',
        'diff_terlambat',
        'schedule_compliance',
        'target_compliance',
        'tahap_perencanaan_count',
        'tahap_perencanaan_pct',
        'tahap_pra_produksi_count',
        'tahap_pra_produksi_pct',
        'tahap_produksi_count',
        'tahap_produksi_pct',
        'tahap_pasca_produksi_count',
        'tahap_pasca_produksi_pct',
        'tahap_qc_count',
        'tahap_qc_pct',
        'tahap_tayang_count',
        'tahap_tayang_pct',
        'total_audience',
        'diff_audience',
        'rating_rata',
        'diff_rating',
        'share',
        'diff_share',
        'durasi_tonton',
        'diff_durasi',
        'header_waktu',
        'live_status',
        'slogan_header',
        'slogan_sub',
        'footer_slogan',
        'footer_nilai',
    ];
}
