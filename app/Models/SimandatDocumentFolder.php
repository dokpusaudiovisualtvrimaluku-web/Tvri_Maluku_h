<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimandatDocumentFolder extends Model
{
    use HasFactory;

    protected $table = 'simandat_document_folders';

    protected $fillable = [
        'parent_id',
        'name',
        'code',
        'icon',
        'color',
        'description',
        'created_by',
    ];

    /**
     * Parent folder relation
     */
    public function parent()
    {
        return $this->belongsTo(SimandatDocumentFolder::class, 'parent_id');
    }

    /**
     * Subfolders relation
     */
    public function children()
    {
        return $this->hasMany(SimandatDocumentFolder::class, 'parent_id');
    }

    /**
     * Documents in this folder
     */
    public function documents()
    {
        return $this->hasMany(SimandatDocument::class, 'folder_id');
    }

    /**
     * Creator user
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get total recursive documents count
     */
    public function getTotalDocumentsCountAttribute()
    {
        $count = $this->documents()->count();
        foreach ($this->children as $child) {
            $count += $child->total_documents_count;
        }
        return $count;
    }
}
