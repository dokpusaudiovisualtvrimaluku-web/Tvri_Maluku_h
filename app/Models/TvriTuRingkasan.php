<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriTuRingkasan extends Model
{
    use HasFactory;

    protected $table = 'tvri_tu_ringkasans';

    protected $guarded = ['id'];

    protected $casts = [
        'kpi_json' => 'array',
        'notifikasi_json' => 'array',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            $stringDefaults = [
                'periode' => 'September 2025',
                'header_waktu' => 'Selasa, 2 September 2025 | 10:24 WIT',
                'online_status' => 'Online | Periode: September 2025',
                'slogan_header' => 'TERTIB ADMINISTRASI • CEPAT LAYANAN • AKURAT DATA • MENDUKUNG KINERJA',
                'slogan_sub' => 'Maluku Bisa!',
                'diff_surat_masuk' => '+12% dari bulan lalu',
                'diff_surat_keluar' => '+8%',
                'diff_disposisi' => '+15%',
                'diff_agenda' => '+20%',
                'diff_layanan' => '+10%',
            ];

            foreach ($stringDefaults as $field => $default) {
                $val = $model->getAttribute($field);
                if ($val === null || (is_string($val) && trim($val) === '')) {
                    $model->setAttribute($field, $default);
                }
            }

            $intFields = [
                'surat_masuk', 'surat_keluar', 'disposisi', 'agenda_kegiatan', 'layanan_adm',
                'service_completion_rate', 'target_completion_rate',
                'disposisi_selesai', 'disposisi_proses', 'disposisi_menunggu', 'disposisi_terlambat',
                'kat_kementerian', 'kat_lpp', 'kat_pemda', 'kat_vertikal', 'kat_mitra', 'kat_masyarakat', 'kat_lainnya',
                'layanan_sk', 'layanan_data', 'layanan_internal', 'layanan_eksternal', 'layanan_lainnya',
                'arsip_aktif', 'arsip_inaktif', 'arsip_digital', 'arsip_retensi',
                'kepatuhan_administrasi', 'target_kepatuhan'
            ];

            foreach ($intFields as $field) {
                $val = $model->getAttribute($field);
                if ($val === null || (is_string($val) && trim($val) === '')) {
                    $model->setAttribute($field, 0);
                }
            }
        });
    }
}
