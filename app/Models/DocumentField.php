<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentField extends Model
{
    /* ============================================================
     * FILLABLE
     * ============================================================ */
    protected $fillable = [
        'code',
        'name',
        'description',
        'sort_order',
    ];

    /* ============================================================
     * RELATIONSHIPS
     * ============================================================ */

    // Subfield (jenis dokumen) di bawah kategori ini
    public function subfields()
    {
        return $this
            ->hasMany(DocumentSubfield::class, 'field_id')
            ->orderBy('sort_order');
    }
}
