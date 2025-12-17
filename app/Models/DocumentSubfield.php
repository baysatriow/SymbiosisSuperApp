<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentSubfield extends Model
{
    /* ============================================================
     * FILLABLE
     * ============================================================ */
    protected $fillable = [
        'field_id',
        'name',
        'description',
        'required',
        'max_size_mb',
        'sort_order',
        'is_custom',
        'user_id',
    ];

    /* ============================================================
     * RELATIONSHIPS
     * ============================================================ */

    // Kategori utama dokumen
    public function field()
    {
        return $this->belongsTo(DocumentField::class, 'field_id');
    }

    // Dokumen yang di-upload user untuk subfield ini
    public function documents()
    {
        return $this->hasMany(Document::class, 'subfield_id');
    }
}
