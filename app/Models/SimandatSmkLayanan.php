<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatSmkLayanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_smk_layanan';

    protected $fillable = [
        'nomor_registrasi',
        'jenis_layanan',
        'nama_pemohon',
        'nomor_identitas',
        'nama_sekolah_asal',
        'npsn_sekolah',
        'konsentrasi_keahlian',
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
     * Layanan & Dokumen Resmi Bidang Pembinaan SMK
     */
    public const JENIS_LAYANAN_LIST = [
        'Kesiswaan & Mutasi' => [
            'icon' => 'arrow-right-left',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'prefix' => 'SMK-KSW',
            'seksi' => 'Seksi Peserta Didik SMK',
            'deskripsi' => 'Layanan terkait kesiswaan, mutasi/pindah sekolah, dan pembinaan karakter siswa.',
        ],
        'Pemerataan & Pendanaan' => [
            'icon' => 'circle-dollar-sign',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
            'prefix' => 'SMK-DANA',
            'seksi' => 'Subbag Perencanaan & Pendanaan',
            'deskripsi' => 'Layanan bantuan operasional, sarana prasarana, dan pendanaan sekolah.',
        ],
        'Dokumen & Legalitas' => [
            'icon' => 'file-signature',
            'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
            'prefix' => 'SMK-DOK',
            'seksi' => 'Seksi Kurikulum & Penilaian',
            'deskripsi' => 'Legalisir ijazah, surat keterangan pengganti ijazah, dan dokumen resmi.',
        ],
        'Kurikulum & Vokasi' => [
            'icon' => 'book-open',
            'badge' => 'bg-purple-100 text-purple-800 border-purple-300',
            'prefix' => 'SMK-KUR',
            'seksi' => 'Seksi Kurikulum SMK',
            'deskripsi' => 'Pengembangan kurikulum, sinkronisasi kompetensi dengan industri (DUDI).',
        ],
        'Kelembagaan & Perizinan' => [
            'icon' => 'building',
            'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            'prefix' => 'SMK-LEM',
            'seksi' => 'Seksi Kelembagaan & Sarpras SMK',
            'deskripsi' => 'Rekomendasi pendirian SMK baru, penambahan program keahlian, izin operasional.',
        ],
    ];

    public const STATUS_LIST = [
        'DRAFT' => 'bg-slate-100 text-slate-800 border-slate-300',
        'PROSES_VERIFIKASI' => 'bg-amber-100 text-amber-800 border-amber-300',
        'DISETUJUI' => 'bg-blue-100 text-blue-800 border-blue-300',
        'SELESAI' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
        'DITOLAK' => 'bg-rose-100 text-rose-800 border-rose-300',
    ];

    public const KONSENTRASI_KEAHLIAN_POPULER = [
        'Teknik Komputer dan Jaringan (TKJ)',
        'Rekayasa Perangkat Lunak (RPL)',
        'Nautika Kapal Penangkap Ikan (NKPI)',
        'Teknika Kapal Penangkap Ikan (TKPI)',
        'Agribisnis Perikanan Air Laut & Payau',
        'Agribisnis Pengolahan Hasil Perikanan (APHP)',
        'Teknik Kendaraan Ringan Otomotif (TKRO)',
        'Teknik Bisnis Sepeda Motor (TBSM)',
        'Teknik Pemesinan & Pengelasan',
        'Teknik Konstruksi & Properti (Bangunan)',
        'Teknik Instalasi Tenaga Listrik (TITL)',
        'Perhotelan & Akomodasi Pariwisata',
        'Kuliner & Tata Boga',
        'Tata Busana & Desain Fesyen',
        'Akuntansi & Keuangan Lembaga (AKL)',
        'Otomatisasi & Tata Kelola Perkantoran (OTKP)',
        'Bisnis Digital & Pemasaran',
        'Keperawatan & Farmasi Klinis',
        'Usaha Layanan Wisata & Ekowisata Kepulauan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getLayananMetaAttribute()
    {
        return self::JENIS_LAYANAN_LIST[$this->jenis_layanan] ?? [
            'icon' => 'file-text',
            'badge' => 'bg-gray-100 text-gray-800 border-gray-300',
            'prefix' => 'SMK-GEN',
            'seksi' => 'Bidang Pembinaan SMK',
            'deskripsi' => 'Layanan Dokumen Bidang SMK',
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

