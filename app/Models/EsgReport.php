<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EsgReport extends Model
{
    /* ============================================================
     * FILLABLE
     * ============================================================ */
    protected $fillable = [
        'user_id',
        'title',
        'status',
        'progress',
        'chapter_content',
        'output_format',
        'file_path',
        'error_message',
        'started_at',
        'completed_at',
    ];

    /* ============================================================
     * CASTS
     * ============================================================ */
    protected $casts = [
        'progress' => 'array',
        'chapter_content' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /* ============================================================
     * RELATIONSHIPS
     * ============================================================ */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* ============================================================
     * HELPER METHODS
     * ============================================================ */

    /**
     * Update progress state
     */
    public function updateProgress(int $currentChapter, int $totalChapters, string $currentSection, int $percentage)
    {
        $this->update([
            'progress' => [
                'current_chapter' => $currentChapter,
                'total_chapters' => $totalChapters,
                'current_section' => $currentSection,
                'percentage' => $percentage,
            ]
        ]);
    }

    /**
     * Append chapter content
     */
    public function appendChapterContent(string $chapterKey, string $content)
    {
        $existing = $this->chapter_content ?? [];
        $existing[$chapterKey] = $content;
        $this->update(['chapter_content' => $existing]);
    }

    /**
     * Mark as processing
     */
    public function markAsProcessing()
    {
        $this->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted(string $filePath)
    {
        $this->update([
            'status' => 'completed',
            'file_path' => $filePath,
            'completed_at' => now(),
            'progress' => [
                'current_chapter' => 7,
                'total_chapters' => 7,
                'current_section' => 'Selesai',
                'percentage' => 100,
            ]
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(string $errorMessage)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'completed_at' => now(),
        ]);
    }

    /**
     * Get status label for display
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'processing' => 'Sedang Diproses',
            'completed' => 'Selesai',
            'failed' => 'Gagal',
            default => $this->status,
        };
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'completed' => 'green',
            'failed' => 'red',
            default => 'gray',
        };
    }
}
