<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    /* ============================================================
     * PRIMARY KEY SETTINGS
     * ============================================================ */
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    /* ============================================================
     * FILLABLE
     * ============================================================ */
    protected $fillable = [
        'user_id',
        'job_title',
        'company_address',
        'job_sector',

        // Lokasi
        'province_id',
        'province_name',
        'city_id',
        'city_name',
        'postal_code',

        // Data tambahan (JSON)
        'additional_primary_fields',

        // Status
        'is_completed',
    ];

    /* ============================================================
     * CASTING
     * ============================================================ */
    protected $casts = [
        'additional_primary_fields' => 'array',
        'is_completed'              => 'boolean',
    ];

    /* ============================================================
     * RELATIONSHIPS
     * ============================================================ */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
