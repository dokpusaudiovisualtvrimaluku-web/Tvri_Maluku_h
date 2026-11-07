<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimandatPerencanaanProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_perencanaan_programs';

    protected $fillable = [
        'nomor_registrasi',
        'jenis_dokumen',
        'kode_rekening',
        'nama_program_kegiatan',
        'bidang_penanggung_jawab',
        'tahun_anggaran',
        'periode_pelaksanaan',
        'pagu_anggaran',
        'realisasi_anggaran',
        'target_kinerja',
        'realisasi_kinerja',
        'persentase_capaian',
        'status_pelaksanaan',
        'uraian_kegiatan',
        'file_dokumen',
        'file_nama_dokumen',
        'file_size',
        'pejabat_penanggung_jawab',
        'catatan_evaluasi',
        'user_id',
    ];

    protected $casts = [
        'pagu_anggaran' => 'decimal:2',
        'realisasi_anggaran' => 'decimal:2',
        'persentase_capaian' => 'decimal:2',
        'tahun_anggaran' => 'integer',
    ];

    public const JENIS_DOKUMEN_LIST = [
        'Program' => [
            'label' => 'Program Kerja Sektoral',
            'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            'prefix' => 'REN-PRG',
            'deskripsi' => 'Rencana program strategis Dinas Pendidikan dan Kebudayaan Provinsi Maluku.',
        ],
        'Kegiatan' => [
            'label' => 'Kegiatan & Subkegiatan',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'prefix' => 'REN-KGT',
            'deskripsi' => 'Rincian aktivitas operasional dan pelaksanaan teknis program dinas.',
        ],
        'Renstra' => [
            'label' => 'Rencana Strategis (Renstra)',
            'badge' => 'bg-purple-100 text-purple-800 border-purple-300',
            'prefix' => 'REN-RENSTRA',
            'deskripsi' => 'Dokumen perencanaan jangka menengah (5 tahunan) Disdikbud Provinsi Maluku.',
        ],
        'Renja' => [
            'label' => 'Rencana Kerja (Renja)',
            'badge' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'prefix' => 'REN-RENJA',
            'deskripsi' => 'Dokumen rencana kerja tahunan satuan kerja perangkat daerah.',
        ],
        'RKA' => [
            'label' => 'Rencana Kerja & Anggaran (RKA)',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
            'prefix' => 'REN-RKA',
            'deskripsi' => 'Usulan rincian anggaran belanja program dan kegiatan per bidang kerja.',
        ],
        'DPA' => [
            'label' => 'Dokumen Pelaksanaan Anggaran (DPA)',
            'badge' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            'prefix' => 'REN-DPA',
            'deskripsi' => 'Dokumen resmi penetapan alokasi APBD pelaksanaan anggaran dinas.',
        ],
    ];

    public const BIDANG_LIST = [
        'Sekretariat Dinas' => 'Sekretariat Dinas (Umum, Kepegawaian & Keuangan)',
        'Bidang Pembinaan SMA' => 'Bidang Pembinaan Sekolah Menengah Atas (SMA)',
        'Bidang Pembinaan SMK' => 'Bidang Pembinaan Sekolah Menengah Kejuruan (SMK)',
        'Bidang Pembinaan Pendidikan Khusus' => 'Bidang Pembinaan Pendidikan Khusus (SLB / PKLK)',
        'Bidang Kebudayaan' => 'Bidang Kebudayaan & Pelestarian Nilai Tradisi',
        'Bidang Guru & Tenaga Kependidikan' => 'Bidang Guru dan Tenaga Kependidikan (GTK)',
        'UPTD BTKP' => 'UPTD Balai Teknologi Komunikasi Pendidikan (BTKP)',
        'UPTD Taman Budaya' => 'UPTD Taman Budaya Provinsi Maluku',
        'UPTD Museum Siwalima' => 'UPTD Museum Siwalima Ambon',
    ];

    public const STATUS_LIST = [
        'PERENCANAAN' => [
            'label' => 'Tahap Perencanaan',
            'badge' => 'bg-slate-100 text-slate-800 border-slate-300',
            'bg' => 'bg-slate-100',
            'text' => 'text-slate-800',
            'border' => 'border-slate-300',
        ],
        'SEDANG_BERJALAN' => [
            'label' => 'Sedang Berjalan',
            'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-800',
            'border' => 'border-blue-300',
        ],
        'BERJALAN' => [
            'label' => 'Sedang Berjalan',
            'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-800',
            'border' => 'border-blue-300',
        ],
        'SELESAI' => [
            'label' => 'Selesai / Tercapai',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'bg' => 'bg-emerald-100',
            'text' => 'text-emerald-800',
            'border' => 'border-emerald-300',
        ],
        'DITUNDA' => [
            'label' => 'Ditunda / Evaluasi',
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

    public static function generateNomorRegistrasi($jenisDokumen): string
    {
        $prefix = self::JENIS_DOKUMEN_LIST[$jenisDokumen]['prefix'] ?? 'REN-DOC';
        $year = date('Y');
        $random = strtoupper(substr(uniqid(), -4));
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('%s-%s-%04d-%s', $prefix, $year, $count, $random);
    }

    public function getDokumenMetaAttribute()
    {
        return self::JENIS_DOKUMEN_LIST[$this->jenis_dokumen] ?? [
            'label' => $this->jenis_dokumen,
            'badge' => 'bg-gray-100 text-gray-800 border-gray-300',
            'prefix' => 'REN-GEN',
            'deskripsi' => 'Dokumen Program & Perencanaan',
        ];
    }

    public function getStatusMetaAttribute()
    {
        return self::STATUS_LIST[$this->status_pelaksanaan] ?? [
            'label' => $this->status_pelaksanaan,
            'badge' => 'bg-slate-100 text-slate-800 border-slate-300',
            'bg' => 'bg-slate-100',
            'text' => 'text-slate-800',
            'border' => 'border-slate-300',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status_meta['label'] ?? $this->status_pelaksanaan;
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status_meta['badge'] ?? 'bg-slate-100 text-slate-800 border-slate-300';
    }

    public function getFormattedPaguAttribute(): string
    {
        return 'Rp ' . number_format((float)$this->pagu_anggaran, 0, ',', '.');
    }

    public function getFormattedRealisasiAttribute(): string
    {
        return 'Rp ' . number_format((float)$this->realisasi_anggaran, 0, ',', '.');
    }

    public function getFormattedSisaAttribute(): string
    {
        $sisa = max(0, (float)$this->pagu_anggaran - (float)$this->realisasi_anggaran);
        return 'Rp ' . number_format($sisa, 0, ',', '.');
    }

    public function getPersentaseRealisasiAttribute(): float
    {
        if ((float)$this->pagu_anggaran <= 0) {
            return 0;
        }
        return round(((float)$this->realisasi_anggaran / (float)$this->pagu_anggaran) * 100, 1);
    }

    public function getFilePathAttribute()
    {
        return $this->file_dokumen;
    }

    public function getFileDokumenUrlAttribute()
    {
        return $this->file_dokumen ? asset('storage/' . $this->file_dokumen) : null;
    }
}
