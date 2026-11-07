<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimandatDocumentVersion extends Model
{
    use HasFactory;

    protected $table = 'simandat_document_versions';

    protected $fillable = [
        'document_id',
        'version_number',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'changelog_note',
        'uploaded_by',
        'uploader_name',
    ];

    /**
     * Document parent relation
     */
    public function document()
    {
        return $this->belongsTo(SimandatDocument::class, 'document_id');
    }

    /**
     * Uploader relation
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
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
}
