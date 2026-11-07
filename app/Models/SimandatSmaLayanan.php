<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatSmaLayanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_sma_layanan';

    protected $fillable = [
        'nomor_registrasi',
        'jenis_layanan',
        'nama_pemohon',
        'nomor_identitas',
        'nama_sekolah_asal',
        'npsn_sekolah',
        'kabupaten_kota',
        'tanggal_pengajuan',
        'tanggal_selesai',
        'nomor_surat_keluar',
        'perihal_uraian',
        'status_layanan',
        'file_permohonan',
        'file_nama_permohonan',
        'file_hasil_sk',
        'file_nama_hasil_sk',
        'file_size',
        'catatan_verifikator',
        'petugas_verifikator',
        'user_id',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * 11 Jenis Layanan & Dokumen Resmi Bidang Pembinaan SMA
     */
    public const JENIS_LAYANAN_LIST = [
        'Rekomendasi Mutasi (Perpindahan) Siswa SMA' => [
            'icon' => 'arrow-right-left',
            'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            'prefix' => 'SMA-MUT',
            'seksi' => 'Seksi Peserta Didik & Pembangunan Karakter',
            'deskripsi' => 'Penerbitan surat rekomendasi perpindahan peserta didik SMA antarsekolah, antarkabupaten/kota di Maluku, dan antarprovinsi.',
        ],
        'Surat Keterangan Pengganti Ijazah STTB / NEM / SKHUN / SKYBS yang Hilang' => [
            'icon' => 'file-warning',
            'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
            'prefix' => 'SMA-IJZ-HLG',
            'seksi' => 'Seksi Kurikulum & Penilaian',
            'deskripsi' => 'Penerbitan Surat Keterangan Pengganti Ijazah/STTB/NEM/SKHUN/SKYBS yang hilang atau rusak bagi alumni SMA se-Maluku.',
        ],
        'Nomor Pokok Sekolah Nasional (NPSN)' => [
            'icon' => 'hash',
            'badge' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            'prefix' => 'SMA-NPSN',
            'seksi' => 'Seksi Kelembagaan & Sarpras',
            'deskripsi' => 'Fasilitasi pengusulan nomor pokok satuan pendidikan baru (NPSN) jenjang SMA melalui verifikasi sistem Pusdatin Kemendikbudristek.',
        ],
        'Rekomendasi dan Izin Pendirian Satuan Pendidikan SMA' => [
            'icon' => 'building',
            'badge' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'prefix' => 'SMA-IZN-DIRI',
            'seksi' => 'Seksi Kelembagaan & Sarpras',
            'deskripsi' => 'Verifikasi berkas studi kelayakan, kelengkapan sarpras, dan penerbitan surat rekomendasi izin pendirian SMA baru (Negeri / Swasta).',
        ],
        'Verifikasi Dan Supervisi Kurikulum Satuan Pendidikan (KSP)' => [
            'icon' => 'book-open-check',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'prefix' => 'SMA-KSP',
            'seksi' => 'Seksi Kurikulum & Penilaian',
            'deskripsi' => 'Validasi, bimbingan teknis supervisi dan pengesahan dokumen Kurikulum Satuan Pendidikan (KSP / KTSP) SMA Provinsi Maluku.',
        ],
        'Legalisir STTB / Ijazah dan NEM / SKHUN / SKYBS' => [
            'icon' => 'stamp',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
            'prefix' => 'SMA-LEG',
            'seksi' => 'Seksi Kurikulum & Penilaian',
            'deskripsi' => 'Layanan legalisasi dan pengesahan keabsahan salinan ijazah, STTB, NEM, SKHUN, dan SKYBS bagi alumni SMA di Provinsi Maluku.',
        ],
        'Rekomendasi Izin Operasional SMA' => [
            'icon' => 'shield-check',
            'badge' => 'bg-teal-100 text-teal-800 border-teal-300',
            'prefix' => 'SMA-IZN-OPS',
            'seksi' => 'Seksi Kelembagaan & Sarpras',
            'deskripsi' => 'Pemeriksaan standar sarpras, kualifikasi GTK, serta penerbitan Surat Keputusan Rekomendasi Izin Operasional SMA.',
        ],
        'Bantuan Operasional Satuan Pendidikan (BOSP) SMA' => [
            'icon' => 'circle-dollar-sign',
            'badge' => 'bg-purple-100 text-purple-800 border-purple-300',
            'prefix' => 'SMA-BOSP',
            'seksi' => 'Tim Manajemen BOSP Bidang SMA',
            'deskripsi' => 'Verifikasi cut-off rekening sekolah, pengusulan salur dana BOSP Reguler/Kinerja SMA, dan fasilitasi pelaporan ARKAS.',
        ],
        'Sistem Penerimaan Murid Baru (SPMB) SMA' => [
            'icon' => 'user-plus',
            'badge' => 'bg-sky-100 text-sky-800 border-sky-300',
            'prefix' => 'SMA-SPMB',
            'seksi' => 'Seksi Peserta Didik & Pembangunan Karakter',
            'deskripsi' => 'Pengelolaan jalur afirmasi, zonasi kepulauan, prestasi, dan perpindahan tugas orang tua dalam PPDB/SPMB SMA se-Maluku.',
        ],
        'Fasilitasi Data Pokok Pendidikan (DAPODIK) SMA' => [
            'icon' => 'database',
            'badge' => 'bg-slate-100 text-slate-800 border-slate-300',
            'prefix' => 'SMA-DAPODIK',
            'seksi' => 'Tim Teknis Dapodik Bidang SMA',
            'deskripsi' => 'Fasilitasi sinkronisasi data kelembagaan, residu NISN, rombel belajar, penugasan PTK, dan validasi data Dapodik SMA.',
        ],
        'Surat Keterangan Kesalahan Penulisan Ijazah / STTB' => [
            'icon' => 'file-edit',
            'badge' => 'bg-orange-100 text-orange-800 border-orange-300',
            'prefix' => 'SMA-IJZ-RKS',
            'seksi' => 'Seksi Kurikulum & Penilaian',
            'deskripsi' => 'Penerbitan Surat Keterangan Ralat / Pembetulan Kesalahan Penulisan Blanko Ijazah/STTB sesuai akta kelahiran dan data Dukcapil.',
        ],
    ];

    public const STATUS_LIST = [
        'DRAFT' => 'bg-slate-100 text-slate-800 border-slate-300',
        'PROSES_VERIFIKASI' => 'bg-amber-100 text-amber-800 border-amber-300',
        'DISETUJUI' => 'bg-blue-100 text-blue-800 border-blue-300',
        'SELESAI' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
        'DITOLAK' => 'bg-rose-100 text-rose-800 border-rose-300',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getJenisLayananOptions()
    {
        $items = \App\Models\SmaJenisLayanan::all();
        $result = [];
        foreach ($items as $item) {
            $result[$item->nama_layanan] = [
                'seksi' => $item->seksi,
                'prefix' => $item->prefix,
                'deskripsi' => $item->deskripsi,
                'icon' => $item->icon,
                'badge' => $item->badge,
            ];
        }
        return $result;
    }

    public function getLayananMetaAttribute()
    {
        $options = self::getJenisLayananOptions();
        return $options[$this->jenis_layanan] ?? [
            'icon' => 'file-text',
            'badge' => 'bg-gray-100 text-gray-800 border-gray-300',
            'prefix' => 'SMA-GEN',
            'seksi' => 'Bidang Pembinaan SMA',
            'deskripsi' => 'Layanan Dokumen Bidang SMA',
        ];
    }

    public function getFilePermohonanUrlAttribute()
    {
        return $this->file_permohonan ? asset('storage/' . $this->file_permohonan) : null;
    }

    public function getFileHasilSkUrlAttribute()
    {
        return $this->file_hasil_sk ? asset('storage/' . $this->file_hasil_sk) : null;
    }
}
