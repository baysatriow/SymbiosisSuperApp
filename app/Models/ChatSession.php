<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    /* ============================================================
     * FILLABLE
     * ============================================================ */
    protected $fillable = [
        'user_id',
        'title',
    ];

    /* ============================================================
     * RELATIONSHIPS
     * ============================================================ */

    // Chat messages dalam sesi ini
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'session_id');
    }

    // Dokumen konteks yang terlampir pada sesi
    public function documents()
    {
        return $this->belongsToMany(
            Document::class,
            'chat_session_documents',
            'session_id',
            'document_id'
        );
    }
}
