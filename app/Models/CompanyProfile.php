<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
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
        'company_name',
        'legal_entity_type',
        'nib_number',
        'siup_number',
        'tax_id_npwp',
        'address',

        // Lokasi
        'city',
        'city_id',
        'province',
        'province_id',
        'country',
        'postal_code',

        // Kontak
        'website',
        'contact_email',
        'contact_phone',

        // Operasional
        'sector',
        'size_employees',
        'year_founded',
        'description',

        // Status
        'is_completed',
    ];

    /* ============================================================
     * CASTING
     * ============================================================ */
    protected $casts = [
        'is_completed' => 'boolean',
    ];

    /* ============================================================
     * RELATIONSHIPS
     * ============================================================ */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
