<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /* ============================================================
     * FILLABLE
     * ============================================================ */
    protected $fillable = [
        'user_id',
        'subfield_id',

        // File storage
        'storage_path',
        'original_filename',
        'mime_type',

        // File size
        'size_bytes_original',
        'size_bytes_compressed',
        'compression_ratio',

        // Status & Verification
        'status',
        'tier',
        'checksum_sha256',
        'rejection_reason',
        'verified_at',
        'verified_by',
    ];

    /* ============================================================
     * RELATIONSHIPS
     * ============================================================ */

    // Jenis dokumen (subfield)
    public function subfield()
    {
        return $this->belongsTo(DocumentSubfield::class, 'subfield_id');
    }

    // Pemilik dokumen
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
