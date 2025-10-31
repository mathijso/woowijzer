<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentQuestionLink extends Model
{
    protected $fillable = [
        'document_id',
        'question_id',
        'relevance_score',
        'confirmed_by_case_manager',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'relevance_score' => 'decimal:2',
            'confirmed_by_case_manager' => 'boolean',
        ];
    }

    /**
     * Relationships
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Scopes
     */
    public function scopeConfirmed($query)
    {
        return $query->where('confirmed_by_case_manager', true);
    }

    public function scopeUnconfirmed($query)
    {
        return $query->where('confirmed_by_case_manager', false);
    }

    public function scopeWithHighRelevance($query, $threshold = 0.7)
    {
        return $query->where('relevance_score', '>=', $threshold);
    }

    /**
     * Helpers
     */
    public function isConfirmed(): bool
    {
        return $this->confirmed_by_case_manager;
    }

    public function hasHighRelevance($threshold = 0.7): bool
    {
        return $this->relevance_score && $this->relevance_score >= $threshold;
    }
}
