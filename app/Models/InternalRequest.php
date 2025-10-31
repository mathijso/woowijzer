<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class InternalRequest extends Model
{
    protected $fillable = [
        'woo_request_id',
        'case_manager_id',
        'colleague_email',
        'colleague_name',
        'description',
        'upload_token',
        'token_expires_at',
        'status',
        'sent_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'token_expires_at' => 'datetime',
            'sent_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($internalRequest) {
            if (empty($internalRequest->upload_token)) {
                $internalRequest->upload_token = Str::random(64);
            }

            if (empty($internalRequest->token_expires_at)) {
                $expiryDays = config('woo.upload_token_expiry_days', 28);
                $internalRequest->token_expires_at = now()->addDays($expiryDays);
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

    public function caseManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'case_manager_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'submitted'])
            ->where('token_expires_at', '>', now());
    }

    public function scopeExpiringWithin($query, $days)
    {
        return $query->whereIn('status', ['pending', 'submitted'])
            ->whereBetween('token_expires_at', [now(), now()->addDays($days)]);
    }

    /**
     * Helpers
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->token_expires_at->isPast();
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'submitted']) && !$this->token_expires_at->isPast();
    }

    public function hasSubmissions(): bool
    {
        return $this->submissions()->exists();
    }

    public function getTotalDocumentsCount(): int
    {
        return $this->submissions()->withCount('documents')->get()->sum('documents_count');
    }

    public function getUploadUrl(): string
    {
        return route('upload.show', $this->upload_token);
    }
}
