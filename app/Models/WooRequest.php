<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class WooRequest extends Model
{
    protected $fillable = [
        'user_id',
        'case_manager_id',
        'title',
        'description',
        'original_file_path',
        'original_file_content_markdown',
        'status',
        'submitted_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function caseManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'case_manager_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function internalRequests(): HasMany
    {
        return $this->hasMany(InternalRequest::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function submissions(): HasManyThrough
    {
        return $this->hasManyThrough(Submission::class, InternalRequest::class);
    }

    /**
     * Scopes
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeInReview($query)
    {
        return $query->where('status', 'in_review');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCaseManager($query, $caseManagerId)
    {
        return $query->where('case_manager_id', $caseManagerId);
    }

    /**
     * Accessors & Helpers
     */
    public function getProgressPercentageAttribute(): float
    {
        $totalQuestions = $this->questions()->count();

        if ($totalQuestions === 0) {
            return 0;
        }

        $answeredQuestions = $this->questions()
            ->where('status', 'answered')
            ->count();

        return round(($answeredQuestions / $totalQuestions) * 100, 2);
    }

    public function isAssigned(): bool
    {
        return $this->case_manager_id !== null;
    }

    public function isComplete(): bool
    {
        return $this->status === 'completed';
    }

    public function canBeProcessed(): bool
    {
        return in_array($this->status, ['submitted', 'in_review', 'in_progress']);
    }
}
