<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatSekretariat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_sekretariat_data';

    protected $fillable = [
        'nomor_agenda',
        'kategori_fungsi',
        'sub_kegiatan',
        'judul_kegiatan',
        'penanggung_jawab',
        'bidang_unit_terkait',
        'tanggal_kegiatan',
        'tanggal_target_selesai',
        'tahun_anggaran',
        'alokasi_anggaran',
        'realisasi_anggaran',
        'persentase_capaian',
        'status_pelaksanaan',
        'tingkat_prioritas',
        'deskripsi_uraian',
        'hasil_keluaran_output',
        'catatan_arahan_sekretaris',
        'file_dokumen',
        'file_nama_asli',
        'file_size',
        'user_id',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'tanggal_target_selesai' => 'date',
        'alokasi_anggaran' => 'decimal:2',
        'realisasi_anggaran' => 'decimal:2',
        'persentase_capaian' => 'integer',
        'tahun_anggaran' => 'integer',
    ];

    /**
     * 8 Fungsi Sekretaris Dinas & Sub-kegiatan Rinci
     */
    public const FUNGSI_LIST = [
        'koordinasi_administrasi' => [
            'label' => 'Koordinasi Administrasi',
            'icon' => 'network',
            'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            'color' => '#005B9F',
            'prefix' => 'SEK-ADM',
            'sub_kegiatan' => [
                'Mengkoordinasikan pelaksanaan administrasi dinas',
                'Mengkoordinasikan kegiatan antarbidang dan unit kerja',
                'Menyiapkan bahan koordinasi dengan perangkat daerah lainnya',
            ],
            'deskripsi' => 'Pengkoordinasian pelaksanaan tata administrasi, sinergi lintas bidang, serta sinkronisasi dengan instansi pemerintah lainnya.',
        ],
        'perencanaan_program' => [
            'label' => 'Perencanaan dan Program',
            'icon' => 'calendar-check',
            'badge' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            'color' => '#0891B2',
            'prefix' => 'SEK-REN',
            'sub_kegiatan' => [
                'Mengkoordinasikan penyusunan rencana kerja dinas',
                'Menyiapkan program dan kegiatan tahunan',
                'Mengkoordinasikan penyusunan Renstra, Renja, dan dokumen perencanaan lainnya',
                'Memantau pelaksanaan program dan kegiatan',
            ],
            'deskripsi' => 'Penyusunan dokumen perencanaan strategis (Renstra, Renja), sinkronisasi program kerja tahunan, serta pemantauan implementasi.',
        ],
        'keuangan' => [
            'label' => 'Keuangan & Anggaran',
            'icon' => 'banknote',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'color' => '#059669',
            'prefix' => 'SEK-KEU',
            'sub_kegiatan' => [
                'Mengkoordinasikan pengelolaan administrasi keuangan',
                'Mengkoordinasikan penyusunan dokumen anggaran',
                'Melakukan pemantauan realisasi anggaran',
                'Menyiapkan bahan laporan pertanggungjawaban keuangan',
            ],
            'deskripsi' => 'Pengelolaan pembukuan keuangan dinas, penyusunan RKA/DPA APBD, pengawasan serapan anggaran, dan penyusunan LPJ keuangan.',
        ],
        'kepegawaian' => [
            'label' => 'Kepegawaian Internal',
            'icon' => 'users-round',
            'badge' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'color' => '#4F46E5',
            'prefix' => 'SEK-PEG',
            'sub_kegiatan' => [
                'Mengelola administrasi kepegawaian',
                'Menyiapkan kebutuhan dan pengembangan kompetensi pegawai',
                'Mengkoordinasikan disiplin, kinerja dan administrasi ASN',
                'Menyiapkan data kepegawaian untuk kebutuhan pimpinan',
            ],
            'deskripsi' => 'Penataan formasi pegawai, pengembangan kompetensi SDM aparatur, pengawasan disiplin, penilaian kinerja, serta penyediaan data kepegawaian pimpinan.',
        ],
        'administrasi_umum' => [
            'label' => 'Administrasi Umum & Rumah Tangga',
            'icon' => 'building-2',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
            'color' => '#D97706',
            'prefix' => 'SEK-UMM',
            'sub_kegiatan' => [
                'Mengelola surat-menyurat dan tata naskah dinas',
                'Mengelola arsip dan dokumentasi',
                'Mengelola perlengkapan dan rumah tangga kantor',
                'Mengkoordinasikan pelayanan administrasi internal dinas',
            ],
            'deskripsi' => 'Penyelenggaraan tata naskah dinas, kearsipan dinas, pengelolaan sarana prasarana operasional kantor, dan pemenuhan rumah tangga dinas.',
        ],
        'pelaporan_evaluasi' => [
            'label' => 'Pelaporan dan Evaluasi (Monev)',
            'icon' => 'file-chart-column',
            'badge' => 'bg-purple-100 text-purple-800 border-purple-300',
            'color' => '#7C3AED',
            'prefix' => 'SEK-MONEV',
            'sub_kegiatan' => [
                'Mengkoordinasikan penyusunan laporan pelaksanaan kegiatan',
                'Melakukan monitoring dan evaluasi program',
                'Menyiapkan bahan laporan kinerja dinas kepada Kepala Dinas dan pimpinan daerah',
            ],
            'deskripsi' => 'Pelaksanaan evaluasi capaian kinerja berkala, monitoring program, serta penyusunan laporan akuntabilitas (LAKIP, LPPD, LKPJ).',
        ],
        'pengelolaan_bmd' => [
            'label' => 'Pengelolaan Barang Milik Daerah (BMD)',
            'icon' => 'boxes',
            'badge' => 'bg-teal-100 text-teal-800 border-teal-300',
            'color' => '#0D9488',
            'prefix' => 'SEK-BMD',
            'sub_kegiatan' => [
                'Mengkoordinasikan administrasi dan inventarisasi barang milik daerah',
                'Memantau penggunaan, pemeliharaan dan pelaporan aset dinas',
            ],
            'deskripsi' => 'Inventarisasi fisik dan legalitas aset/BMD, pencatatan KIB, monitoring pemeliharaan kendaraan dinas dan gedung, serta pelaporan e-BMD.',
        ],
        'pelayanan_internal' => [
            'label' => 'Pelayanan Internal Dinas',
            'icon' => 'sparkles',
            'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
            'color' => '#E11D48',
            'prefix' => 'SEK-INT',
            'sub_kegiatan' => [
                'Mengkoordinasikan pelayanan administrasi kepada seluruh bidang dalam dinas',
                'Memastikan kebutuhan administrasi, kepegawaian, keuangan dan perlengkapan unit kerja terpenuhi',
            ],
            'deskripsi' => 'Fasilitasi dukungan operasional administrasi, logistik, kepegawaian, dan keuangan bagi seluruh bidang serta UPTD di lingkungan dinas.',
        ],
    ];

    public const STATUS_LIST = [
        'Direncanakan' => 'bg-slate-100 text-slate-800 border-slate-300',
        'Sedang Berjalan' => 'bg-blue-100 text-blue-800 border-blue-300',
        'Menunggu Telaah' => 'bg-amber-100 text-amber-800 border-amber-300',
        'Selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
        'Perlu Evaluasi' => 'bg-purple-100 text-purple-800 border-purple-300',
        'Tertunda' => 'bg-rose-100 text-rose-800 border-rose-300',
    ];

    public const PRIORITAS_LIST = [
        'Tinggi' => 'bg-rose-50 text-rose-700 border-rose-200',
        'Sedang' => 'bg-amber-50 text-amber-700 border-amber-200',
        'Biasa' => 'bg-slate-50 text-slate-700 border-slate-200',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFungsiMetaAttribute()
    {
        return self::FUNGSI_LIST[$this->kategori_fungsi] ?? [
            'label' => $this->kategori_fungsi,
            'icon' => 'folder',
            'badge' => 'bg-gray-100 text-gray-800 border-gray-300',
            'color' => '#475569',
            'prefix' => 'SEK-GEN',
            'sub_kegiatan' => [],
            'deskripsi' => 'Fungsi Sekretariat Dinas',
        ];
    }

    public function getFungsiLabelAttribute()
    {
        return $this->fungsi_meta['label'] ?? $this->kategori_fungsi;
    }

    public function getFileUrlAttribute()
    {
        return $this->file_dokumen ? asset('storage/' . $this->file_dokumen) : null;
    }
}
