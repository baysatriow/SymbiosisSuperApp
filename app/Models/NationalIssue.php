<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NationalIssue extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = 'national_issues_sentiment_hourly';

    protected $fillable = [
        'data_id',
        'platform',
        'timestamp',
        'author_name',
        'content_text',
        'cleaned_text',
        'sentiment_score',
        'sentiment_label',
        'category_issue',
        'engagement_count',
        'location',
        'url_source',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];
}
