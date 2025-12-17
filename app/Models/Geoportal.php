<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geoportal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'coordinates',
        'properties',
        'type'
    ];

    protected $casts = [
        'coordinates' => 'array',
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
