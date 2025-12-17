<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /* ============================================================
     * FILLABLE
     * ============================================================ */
    protected $fillable = [
        'email',
        'username',
        'full_name',
        'password_hash',
        'phone_number',
        'role',
        'status',
        'last_login_at',
    ];

    /* ============================================================
     * HIDDEN
     * ============================================================ */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /* ============================================================
     * CASTING
     * ============================================================ */
    protected $casts = [
        'last_login_at'     => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    /* ============================================================
     * AUTH PASSWORD OVERRIDE
     * ============================================================ */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /* ============================================================
     * RELATIONSHIPS
     * ============================================================ */

    // OTP codes
    public function otpCodes()
    {
        return $this->hasMany(OtpCode::class);
    }

    // Profile pengguna
    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }

    // Profil perusahaan
    public function companyProfile()
    {
        return $this->hasOne(CompanyProfile::class);
    }

    // Dokumen user
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /* ============================================================
     * HELPERS
     * ============================================================ */

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
