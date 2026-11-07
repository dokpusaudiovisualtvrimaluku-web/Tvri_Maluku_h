<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SimandatDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'simandat_documents';

    protected $fillable = [
        'folder_id',
        'document_number',
        'title',
        'category',
        'division',
        'description',
        'document_date',
        'year',
        'confidentiality',
        'status',
        'current_version',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'school_id',
        'culture_id',
        'tags',
        'download_count',
        'view_count',
        'qr_token',
        'uploader_id',
        'uploader_name',
    ];

    protected $casts = [
        'document_date' => 'date',
        'year' => 'integer',
        'download_count' => 'integer',
        'view_count' => 'integer',
    ];

    public const KATEGORI_LIST = [
        'Surat Keputusan (SK)',
        'Surat Edaran & Instruksi',
        'Laporan Kinerja & Akuntabilitas',
        'RKA & DPA Perencanaan',
        'Naskah Akademik & Kajian Budaya',
        'Pedoman, Panduan & SOP',
        'Sertifikat & Piagam Penetapan',
        'MoU & Kerjasama Kemitraan',
        'Dokumentasi Kegiatan & Media',
        'Data Pokok & Statistik',
        'Lainnya',
    ];

    public const BIDANG_LIST = [
        'Tata Usaha',
        'Berita',
        'Program',
        'Umum',
        'Program Umum',
        'Teknik Studio',
        'Teknik Transmisi',
        'Promo',
        'KBM',
        'Pengembangan Usaha',
        'Keuangan',
    ];

    public const CONFIDENTIALITY_LIST = [
        'PUBLIK' => ['label' => 'Publik', 'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
        'INTERNAL' => ['label' => 'Internal Dinas', 'badge' => 'bg-blue-100 text-blue-800 border-blue-300'],
        'TERBATAS' => ['label' => 'Terbatas (Pimpinan)', 'badge' => 'bg-amber-100 text-amber-800 border-amber-300'],
        'RAHASIA' => ['label' => 'Rahasia & Khusus', 'badge' => 'bg-rose-100 text-rose-800 border-rose-300'],
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($doc) {
            if (empty($doc->qr_token)) {
                $doc->qr_token = (string) Str::uuid();
            }
        });
    }

    /**
     * Folder relation
     */
    public function folder()
    {
        return $this->belongsTo(SimandatDocumentFolder::class, 'folder_id');
    }

    /**
     * Document versions history
     */
    public function versions()
    {
        return $this->hasMany(SimandatDocumentVersion::class, 'document_id')->orderBy('created_at', 'desc');
    }

    /**
     * Associated School
     */
    public function school()
    {
        return $this->belongsTo(SimandatSchool::class, 'school_id');
    }

    /**
     * Associated Culture Object
     */
    public function culture()
    {
        return $this->belongsTo(SimandatCulture::class, 'culture_id');
    }

    /**
     * Uploader user
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    /**
     * Verification URL for QR code
     */
    public function getVerificationUrlAttribute()
    {
        return route('dokumen.verify', ['token' => $this->qr_token]);
    }

    /**
     * QR Code Image URL (using reliable SVG QR generator API)
     */
    public function getQrImageUrlAttribute()
    {
        $url = urlencode($this->verification_url);
        return "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={$url}&color=003B70&bgcolor=FFFFFF&qzone=1";
    }

    /**
     * Public file URL accessor
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    /**
     * Format badge color class
     */
    public function getFormatBadgeColorAttribute()
    {
        return match (strtoupper($this->file_type)) {
            'PDF' => 'bg-rose-100 text-rose-800 border-rose-300',
            'DOC', 'DOCX' => 'bg-blue-100 text-blue-800 border-blue-300',
            'XLS', 'XLSX' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'PPT', 'PPTX' => 'bg-amber-100 text-amber-800 border-amber-300',
            'JPG', 'JPEG', 'PNG' => 'bg-purple-100 text-purple-800 border-purple-300',
            'MP4', 'AVI', 'MKV' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'MP3', 'WAV' => 'bg-pink-100 text-pink-800 border-pink-300',
            'ZIP', 'RAR' => 'bg-slate-100 text-slate-800 border-slate-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    /**
     * Check if a user can access / download this document
     */
    public function canAccess($user): bool
    {
        if (!$user) {
            return $this->confidentiality === 'PUBLIK';
        }

        // Admin and Kepala Dinas can access all documents
        if ($user->hasRole('ADMIN') || $user->hasRole('KEPALA_DINAS') || $user->role === 'ADMIN' || $user->role === 'KEPALA_DINAS') {
            return true;
        }

        if ($this->confidentiality === 'RAHASIA') {
            return false;
        }

        return true;
    }
}
