<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatSlbLayanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_slb_layanan';

    protected $fillable = [
        'nomor_registrasi',
        'jenis_layanan',
        'nama_pemohon',
        'nomor_identitas',
        'nama_satuan_pendidikan',
        'npsn_sekolah',
        'kabupaten_kota',
        'no_hp_whatsapp',
        'email',
        'tanggal_pengajuan',
        'tanggal_selesai',
        'nomor_surat_rekomendasi',
        'perihal_uraian',
        'status_layanan',
        'file_persyaratan',
        'file_nama_persyaratan',
        'file_hasil_rekomendasi',
        'file_nama_hasil',
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
     * 4 Layanan Pokok Bidang Pembinaan Pendidikan Khusus (SLB / PKLK)
     */
    public const JENIS_LAYANAN_LIST = [
        'Kelembagaan & Perizinan' => [
            'icon' => 'building-library',
            'badge' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'prefix' => 'SLB-IZN-DIRI',
            'seksi' => 'Seksi Kelembagaan & Sarpras Pendidikan Khusus',
            'deskripsi' => 'Penerbitan surat rekomendasi teknis dan verifikasi kelayakan pendirian Sekolah Luar Biasa (SLB) Negeri maupun Swasta di wilayah Provinsi Maluku.',
        ],
        'Kesiswaan & Mutasi' => [
            'icon' => 'user-group',
            'badge' => 'bg-sky-100 text-sky-800 border-sky-300',
            'prefix' => 'SLB-MUTASI',
            'seksi' => 'Seksi Peserta Didik & Pembangunan Karakter Pendidikan Khusus',
            'deskripsi' => 'Pelayanan rekomendasi resmi perpindahan (mutasi) siswa berkebutuhan khusus antar-SLB, antar-kabupaten/kota dalam provinsi, maupun antar-provinsi.',
        ],
        'Pemerataan & Pendanaan' => [
            'icon' => 'currency-dollar',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'prefix' => 'SLB-BOSP',
            'seksi' => 'Tim Manajemen BOSP Pendidikan Khusus',
            'deskripsi' => 'Verifikasi dokumen perencanaan, rekomendasi penyaluran, sinkronisasi cut-off Dapodik, serta rekonsiliasi laporan pertanggungjawaban BOSP SLB.',
        ],
        'Dokumen & Legalitas' => [
            'icon' => 'document-check',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
            'prefix' => 'SLB-IJZ-LEGAL',
            'seksi' => 'Seksi Kurikulum & Penilaian Pendidikan Khusus',
            'deskripsi' => 'Pelayanan legalisasi dokumen resmi kelulusan (Ijazah, STTB, SKL, SKYBS) bagi alumni SLB se-Provinsi Maluku serta penerbitan Surat Keterangan Pengganti Ijazah Rusak/Hilang.',
        ],
    ];

    public const STATUS_LIST = [
        'DRAFT' => [
            'label' => 'Draft / Pengajuan Awal',
            'badge' => 'bg-slate-100 text-slate-800 border-slate-300',
            'bg' => 'bg-slate-100',
            'text' => 'text-slate-800',
            'border' => 'border-slate-300',
        ],
        'PROSES_VERIFIKASI' => [
            'label' => 'Proses Verifikasi',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
            'bg' => 'bg-amber-100',
            'text' => 'text-amber-800',
            'border' => 'border-amber-300',
        ],
        'DISETUJUI' => [
            'label' => 'Disetujui',
            'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-800',
            'border' => 'border-blue-300',
        ],
        'SELESAI' => [
            'label' => 'Selesai Terbit',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'bg' => 'bg-emerald-100',
            'text' => 'text-emerald-800',
            'border' => 'border-emerald-300',
        ],
        'DITOLAK' => [
            'label' => 'Ditolak / Perlu Perbaikan',
            'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
            'bg' => 'bg-rose-100',
            'text' => 'text-rose-800',
            'border' => 'border-rose-300',
        ],
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function generateNomorRegistrasi($jenisLayanan): string
    {
        $prefix = self::JENIS_LAYANAN_LIST[$jenisLayanan]['prefix'] ?? 'SLB-DOC';
        $year = date('Y');
        $random = strtoupper(substr(uniqid(), -4));
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('%s-%s-%04d-%s', $prefix, $year, $count, $random);
    }

    public function getLayananMetaAttribute()
    {
        return self::JENIS_LAYANAN_LIST[$this->jenis_layanan] ?? [
            'icon' => 'document-text',
            'badge' => 'bg-gray-100 text-gray-800 border-gray-300',
            'prefix' => 'SLB-GEN',
            'seksi' => 'Bidang Pembinaan Pendidikan Khusus',
            'deskripsi' => 'Layanan Dokumen Pendidikan Khusus',
        ];
    }

    public function getStatusMetaAttribute()
    {
        return self::STATUS_LIST[$this->status_layanan] ?? [
            'label' => $this->status_layanan,
            'badge' => 'bg-slate-100 text-slate-800 border-slate-300',
            'bg' => 'bg-slate-100',
            'text' => 'text-slate-800',
            'border' => 'border-slate-300',
        ];
    }

    public function getFilePersyaratanUrlAttribute()
    {
        return $this->file_persyaratan ? asset('storage/' . $this->file_persyaratan) : null;
    }

    public function getFileHasilRekomendasiUrlAttribute()
    {
        return $this->file_hasil_rekomendasi ? asset('storage/' . $this->file_hasil_rekomendasi) : null;
    }
}
