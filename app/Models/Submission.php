<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    protected $fillable = [
        'internal_request_id',
        'submitted_by_email',
        'submitted_by_name',
        'submission_notes',
        'ip_address',
        'user_agent',
        'documents_count',
    ];

    /**
     * Relationships
     */
    public function internalRequest(): BelongsTo
    {
        return $this->belongsTo(InternalRequest::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Scopes
     */
    public function scopeByEmail($query, $email)
    {
        return $query->where('submitted_by_email', $email);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Helpers
     */
    public function getSubmitterName(): string
    {
        return $this->submitted_by_name ?? $this->submitted_by_email;
    }

    public function hasDocuments(): bool
    {
        return $this->documents_count > 0;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Update documents count when documents are added/removed
        static::created(function ($submission): void {
            $submission->updateDocumentsCount();
        });
    }

    public function updateDocumentsCount(): void
    {
        $this->documents_count = $this->documents()->count();
        $this->saveQuietly();
    }
}
