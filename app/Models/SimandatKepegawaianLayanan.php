<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatKepegawaianLayanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_kepegawaian_layanan';

    protected $fillable = [
        'nomor_agenda',
        'jenis_layanan',
        'nama_pemohon',
        'nip_nik_nisn',
        'pangkat_golongan',
        'jabatan_unit_kerja',
        'kabupaten_kota',
        'tanggal_permohonan',
        'tanggal_selesai',
        'nomor_sk_surat',
        'perihal_deskripsi',
        'status_layanan',
        'file_permohonan',
        'file_hasil_sk',
        'catatan_petugas',
        'petugas_nama',
        'user_id',
    ];

    protected $casts = [
        'tanggal_permohonan' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * 10 Standard Services for Sub Bagian Kepegawaian dan Umum
     */
    public const JENIS_LAYANAN_LIST = [
        'Surat / Naskah Dinas' => [
            'icon' => 'mail',
            'badge' => 'bg-slate-100 text-slate-800 border-slate-300',
            'prefix' => 'REG-ND',
            'deskripsi' => 'Pengelolaan surat masuk/keluar, naskah dinas resmi, dan lembar disposisi pimpinan.',
        ],
        'Layanan Kenaikan Pangkat' => [
            'icon' => 'trending-up',
            'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            'prefix' => 'REG-KP',
            'deskripsi' => 'Pengusulan Kenaikan Pangkat reguler dan pilihan untuk PNS, Guru & Tenaga Kependidikan.',
        ],
        'Kenaikan Gaji Berkala (KGB)' => [
            'icon' => 'dollar-sign',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'prefix' => 'REG-KGB',
            'deskripsi' => 'Proses verifikasi dan penerbitan Surat Keputusan Kenaikan Gaji Berkala (KGB) 2 tahunan.',
        ],
        'Layanan Izin Praktik Kerja Industri (PKL Siswa/Mahasiswa)' => [
            'icon' => 'briefcase',
            'badge' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            'prefix' => 'REG-PKL',
            'deskripsi' => 'Penerbitan surat rekomendasi dan izin magang/PKL bagi siswa SMK dan mahasiswa di lingkungan dinas/sekolah.',
        ],
        'Layanan Izin Cuti PNS' => [
            'icon' => 'calendar',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
            'prefix' => 'REG-CUTI',
            'deskripsi' => 'Layanan permohonan cuti tahunan, cuti melahirkan, cuti sakit, dan cuti alasan penting bagi ASN.',
        ],
        'Layanan Izin Perceraian PNS' => [
            'icon' => 'shield-alert',
            'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
            'prefix' => 'REG-CERAI',
            'deskripsi' => 'Proses mediasi, pemeriksaan berkas, dan penerbitan surat izin perkawinan/perceraian ASN sesuai regulasi.',
        ],
        'Penerbitan Izin Belajar dan Tugas Belajar' => [
            'icon' => 'book-open',
            'badge' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'prefix' => 'REG-IB-TB',
            'deskripsi' => 'Pemberian izin kuliah mandiri (Izin Belajar) dan beasiswa tugas belajar jenjang S1/S2/S3 bagi pegawai & guru.',
        ],
        'Usul Pensiun PNS' => [
            'icon' => 'user-check',
            'badge' => 'bg-purple-100 text-purple-800 border-purple-300',
            'prefix' => 'REG-PENSIUN',
            'deskripsi' => 'Pengurusan usulan pensiun Batas Usia Pensiun (BUP), pensiun dini, dan pensiun janda/duda ke BKN.',
        ],
        'Layanan Izin Penelitian' => [
            'icon' => 'file-search',
            'badge' => 'bg-teal-100 text-teal-800 border-teal-300',
            'prefix' => 'REG-RISET',
            'deskripsi' => 'Penerbitan surat izin penelitian skripsi, tesis, disertasi, atau kajian ilmiah pada satuan pendidikan Maluku.',
        ],
        'Usul Penyesuaian Gelar Akademik' => [
            'icon' => 'award',
            'badge' => 'bg-orange-100 text-orange-800 border-orange-300',
            'prefix' => 'REG-GELAR',
            'deskripsi' => 'Verifikasi ijazah dan pengusulan pencantuman gelar akademik baru pada data kepegawaian BKN.',
        ],
    ];

    /**
     * Service Statuses
     */
    public const STATUS_LIST = [
        'PENDING' => ['label' => 'Menunggu Verifikasi', 'badge' => 'bg-amber-100 text-amber-800 border-amber-300'],
        'PROSES_VERIFIKASI' => ['label' => 'Sedang Diproses', 'badge' => 'bg-blue-100 text-blue-800 border-blue-300'],
        'DISETUJUI' => ['label' => 'Disetujui & Draf SK', 'badge' => 'bg-cyan-100 text-cyan-800 border-cyan-300'],
        'SELESAI' => ['label' => 'Selesai & SK Terbit', 'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
        'DITOLAK' => ['label' => 'Ditolak / Berkas Kurang', 'badge' => 'bg-rose-100 text-rose-800 border-rose-300'],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFilePermohonanUrlAttribute()
    {
        if ($this->file_permohonan) {
            return asset('storage/' . $this->file_permohonan);
        }
        return null;
    }

    public function getFileHasilSkUrlAttribute()
    {
        if ($this->file_hasil_sk) {
            return asset('storage/' . $this->file_hasil_sk);
        }
        return null;
    }

    public function getJenisLayananConfigAttribute()
    {
        return self::JENIS_LAYANAN_LIST[$this->jenis_layanan] ?? [
            'icon' => 'file',
            'badge' => 'bg-gray-100 text-gray-800 border-gray-300',
            'prefix' => 'REG',
            'deskripsi' => 'Layanan Kepegawaian & Umum',
        ];
    }

    public function getStatusConfigAttribute()
    {
        return self::STATUS_LIST[$this->status_layanan] ?? [
            'label' => $this->status_layanan,
            'badge' => 'bg-gray-100 text-gray-800 border-gray-300',
        ];
    }
}
