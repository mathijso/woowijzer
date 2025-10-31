<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property int $woo_request_id
 * @property string $question_text
 * @property int|null $order
 * @property string|null $status
 * @property string|null $ai_summary
 * @property-read WooRequest $wooRequest
 * @property-read \Illuminate\Support\Collection<int, Document> $documents
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Question answered()
 * @method static \Illuminate\Database\Eloquent\Builder|Question unanswered()
 * @method static \Illuminate\Database\Eloquent\Builder|Question partiallyAnswered()
 * @method static \Illuminate\Database\Eloquent\Builder|Question ordered()
 */
class Question extends Model
{
    protected $fillable = [
        'uuid',
        'woo_request_id',
        'question_text',
        'order',
        'status',
        'ai_summary',
    ];

    /**
     * Route key name for model binding
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($question): void {
            if (empty($question->uuid)) {
                $question->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationships
     */
    public function wooRequest(): BelongsTo
    {
        return $this->belongsTo(WooRequest::class);
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'document_question_links')
            ->withPivot('relevance_score', 'confirmed_by_case_manager', 'notes')
            ->withTimestamps();
    }

    /**
     * Scopes
     */
    public function scopeUnanswered($query)
    {
        return $query->where('status', 'unanswered');
    }

    public function scopePartiallyAnswered($query)
    {
        return $query->where('status', 'partially_answered');
    }

    public function scopeAnswered($query)
    {
        return $query->where('status', 'answered');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Helpers
     */
    public function isAnswered(): bool
    {
        return $this->status === 'answered';
    }

    public function hasDocuments(): bool
    {
        return $this->documents()->exists();
    }

    public function getLinkedDocumentsCount(): int
    {
        return $this->documents()->count();
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'unanswered' => 'text-red-700 bg-red-100 dark:bg-red-900/20 dark:text-red-400',
            'partially_answered' => 'text-yellow-700 bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400',
            'answered' => 'text-green-700 bg-green-100 dark:bg-green-900/20 dark:text-green-400',
            default => 'text-neutral-700 bg-neutral-100 dark:bg-neutral-900/20 dark:text-neutral-300',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'unanswered' => 'Open',
            'partially_answered' => 'Gedeeltelijk beantwoord',
            'answered' => 'Beantwoord',
            default => ucfirst((string) $this->status),
        };
    }
}
