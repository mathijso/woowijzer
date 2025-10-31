<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $case_manager_id
 * @property string $title
 * @property string|null $description
 * @property string|null $original_file_path
 * @property string|null $original_file_content_markdown
 * @property string $status
 * @property \Carbon\CarbonInterface|null $submitted_at
 * @property \Carbon\CarbonInterface|null $completed_at
 * @property-read User $user
 * @property-read User|null $caseManager
 * @property-read \Illuminate\Support\Collection<int, Question> $questions
 * @property-read \Illuminate\Support\Collection<int, InternalRequest> $internalRequests
 * @property-read \Illuminate\Support\Collection<int, Document> $documents
 * @property-read \Illuminate\Support\Collection<int, Submission> $submissions
 */
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

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'submitted' => 'text-yellow-700 bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400',
            'in_review' => 'text-blue-700 bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400',
            'in_progress' => 'text-indigo-700 bg-indigo-100 dark:bg-indigo-900/20 dark:text-indigo-400',
            'completed' => 'text-green-700 bg-green-100 dark:bg-green-900/20 dark:text-green-400',
            'rejected' => 'text-red-700 bg-red-100 dark:bg-red-900/20 dark:text-red-400',
            default => 'text-neutral-700 bg-neutral-100 dark:bg-neutral-900/20 dark:text-neutral-300',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'submitted' => 'Ingediend',
            'in_review' => 'In beoordeling',
            'in_progress' => 'In behandeling',
            'completed' => 'Afgerond',
            'rejected' => 'Afgewezen',
            default => ucfirst((string) $this->status),
        };
    }
}
