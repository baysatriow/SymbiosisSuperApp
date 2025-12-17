<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    use HasFactory;

    /* ============================================================
     * FILLABLE
     * ============================================================ */
    protected $fillable = [
        'user_id',
        'code',
        'purpose',
        'expires_at',
        'consumed_at',
    ];

    /* ============================================================
     * CASTS
     * ============================================================ */
    protected $casts = [
        'expires_at'  => 'datetime',
        'consumed_at' => 'datetime',
    ];

    /* ============================================================
     * RELATIONSHIPS
     * ============================================================ */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* ============================================================
     * HELPERS
     * ============================================================ */

    // Mengecek apakah OTP masih berlaku (belum digunakan & belum expired)
    public function isValid()
    {
        return is_null($this->consumed_at) && $this->expires_at->isFuture();
    }
}
