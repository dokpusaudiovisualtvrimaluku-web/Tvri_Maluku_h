<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatGtkLayanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_gtk_layanan';

    protected $fillable = [
        'nomor_registrasi',
        'jenis_layanan',
        'nama_lengkap_guru',
        'nuptk',
        'nip_nik',
        'tempat_tugas_sekolah',
        'npsn_sekolah',
        'jenjang_sekolah',
        'status_kepegawaian',
        'kabupaten_kota',
        'tanggal_pengajuan',
        'tanggal_selesai',
        'nomor_sk_rekomendasi',
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
     * 4 Standar Layanan Bidang Guru & Tenaga Kependidikan (GTK)
     */
    public const JENIS_LAYANAN_LIST = [
        'Cagar Budaya & Permuseuman' => [
            'icon' => 'building-library',
            'badge' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'prefix' => 'KBD-CGB',
            'seksi' => 'Seksi Cagar Budaya & Permuseuman',
            'deskripsi' => 'Pengusulan penetapan, pemugaran, pelindungan cagar budaya, serta sertifikasi dan standarisasi museum di Provinsi Maluku.',
        ],
        'Warisan Budaya Takbenda' => [
            'icon' => 'sparkles',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'prefix' => 'KBD-WBTB',
            'seksi' => 'Seksi Warisan Budaya Takbenda & Tradisi',
            'deskripsi' => 'Inventarisasi, pencatatan, dan usulan penetapan Warisan Budaya Takbenda (WBTb) Indonesia dari Provinsi Maluku.',
        ],
        'Kesenian & Event' => [
            'icon' => 'ticket',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
            'prefix' => 'KBD-EVN',
            'seksi' => 'Seksi Pengembangan Kesenian & Perfilman',
            'deskripsi' => 'Fasilitasi pertunjukan seni budaya, perizinan dan rekomendasi festival budaya daerah, serta pembinaan sanggar.',
        ],
        'Komunitas & Lembaga Adat' => [
            'icon' => 'users-round',
            'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
            'prefix' => 'KBD-KOM',
            'seksi' => 'Seksi Kelembagaan & Tenaga Kebudayaan',
            'deskripsi' => 'Pendaftaran, legalisasi, dan fasilitasi bantuan operasional bagi komunitas budaya, sanggar, dan lembaga adat di Maluku.',
        ],
        'Sertifikasi Pendidik' => [
            'icon' => 'award',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
            'prefix' => 'GTK-SERGUR',
            'seksi' => 'Seksi Pembinaan Pendidik & Tenaga Kependidikan',
            'deskripsi' => 'Pengusulan dan verifikasi berkas Pendidikan Profesi Guru (PPG), penerbitan Nomor Registrasi Guru (NRG), dan validasi keabsahan Sertifikat Pendidik.',
        ],
        'NUPTK' => [
            'icon' => 'id-card',
            'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            'prefix' => 'GTK-NUPTK',
            'seksi' => 'Tim Teknis VervalPTK Bidang GTK',
            'deskripsi' => 'Pengajuan penerbitan NUPTK baru, perbaikan data identitas GTK, verval berkas akademik, dan approval sistem VervalPTK Kemendikbudristek.',
        ],
        'Tunjangan Pendidik' => [
            'icon' => 'wallet',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'prefix' => 'GTK-TUNJ',
            'seksi' => 'Seksi Kesejahteraan & Penghargaan GTK',
            'deskripsi' => 'Verifikasi e-Validasi InfoGTK, penerbitan SKTP (Tunjangan Profesi Guru), Tunjangan Khusus Guru Daerah 3T/Kepulauan, dan Tambahan Penghasilan (Tamsil).',
        ],
        'SIMPKB / PKB' => [
            'icon' => 'users-round',
            'badge' => 'bg-purple-100 text-purple-800 border-purple-300',
            'prefix' => 'GTK-SIMPKB',
            'seksi' => 'Seksi Pengembangan Profesi & Karir GTK',
            'deskripsi' => 'Pengelolaan akun SIMPKB, verifikasi Komunitas Belajar (MGMP/KKG/MKKS), fasilitasi Program Guru Penggerak (PGP), dan Pengembangan Keprofesian Berkelanjutan (PKB).',
        ],
    ];

    public const STATUS_LIST = [
        'DRAFT' => [
            'label' => 'Draft / Pengajuan Awal',
            'badge' => 'bg-slate-100 text-slate-800 border-slate-300',
            'bg' => 'bg-slate-100',
            'text' => 'text-slate-800',
            'border' => 'border-slate-300'
        ],
        'PROSES_VERIFIKASI' => [
            'label' => 'Proses Verifikasi',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
            'bg' => 'bg-amber-100',
            'text' => 'text-amber-800',
            'border' => 'border-amber-300'
        ],
        'DISETUJUI' => [
            'label' => 'Disetujui',
            'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-800',
            'border' => 'border-blue-300'
        ],
        'SELESAI' => [
            'label' => 'Selesai Terbit',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'bg' => 'bg-emerald-100',
            'text' => 'text-emerald-800',
            'border' => 'border-emerald-300'
        ],
        'DITOLAK' => [
            'label' => 'Ditolak / Perlu Perbaikan',
            'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
            'bg' => 'bg-rose-100',
            'text' => 'text-rose-800',
            'border' => 'border-rose-300'
        ],
    ];

    public const STATUS_KEPEGAWAIAN_LIST = [
        'PNS' => 'Pegawai Negeri Sipil (PNS)',
        'PPPK' => 'Pegawai Pemerintah dengan Perjanjian Kerja (PPPK)',
        'Guru Non-ASN / GTT' => 'Guru Non-ASN / Guru Tidak Tetap (GTT)',
        'Tenaga Kependidikan / PTT' => 'Tenaga Kependidikan / PTT',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function generateNomorRegistrasi($jenisLayanan): string
    {
        $prefix = self::JENIS_LAYANAN_LIST[$jenisLayanan]['prefix'] ?? 'GTK-DOC';
        $year = date('Y');
        $random = strtoupper(substr(uniqid(), -4));
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('%s-%s-%04d-%s', $prefix, $year, $count, $random);
    }

    public function getLayananMetaAttribute()
    {
        return self::JENIS_LAYANAN_LIST[$this->jenis_layanan] ?? [
            'icon' => 'file-text',
            'badge' => 'bg-gray-100 text-gray-800 border-gray-300',
            'prefix' => 'GTK-GEN',
            'seksi' => 'Bidang Guru & Tenaga Kependidikan',
            'deskripsi' => 'Layanan Dokumen GTK',
        ];
    }

    public function getStatusMetaAttribute()
    {
        return self::STATUS_LIST[$this->status_layanan] ?? [
            'label' => $this->status_layanan,
            'badge' => 'bg-slate-100 text-slate-800 border-slate-300',
            'bg' => 'bg-slate-100',
            'text' => 'text-slate-800',
            'border' => 'border-slate-300'
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
