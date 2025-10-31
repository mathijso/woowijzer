<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends Model
{
    protected $fillable = [
        'woo_request_id',
        'question_text',
        'order',
        'status',
        'ai_summary',
    ];

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
}
