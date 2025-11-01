<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string|null $external_document_id
 * @property int|null $woo_request_id
 * @property int|null $submission_id
 * @property string $file_path
 * @property string $file_name
 * @property string|null $file_type
 * @property int|null $file_size
 * @property string|null $content_markdown
 * @property string|null $ai_summary
 * @property float|null $relevance_score
 * @property string|null $relevance_explanation
 * @property \Carbon\CarbonInterface|null $processed_at
 * @property string $api_processing_status
 * @property string|null $api_processing_error
 * @property array|null $timeline_events_json
 * @property array|null $processing_metadata_json
 * @property-read WooRequest|null $wooRequest
 * @property-read Submission|null $submission
 * @property-read \Illuminate\Support\Collection<int, Question> $questions
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Document processed()
 * @method static \Illuminate\Database\Eloquent\Builder|Document unprocessed()
 * @method static \Illuminate\Database\Eloquent\Builder|Document apiPending()
 * @method static \Illuminate\Database\Eloquent\Builder|Document apiFailed()
 * @method static \Illuminate\Database\Eloquent\Builder|Document needsApiRetry()
 */
class Document extends Model
{
    protected $fillable = [
        'uuid',
        'external_document_id',
        'woo_request_id',
        'submission_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'content_markdown',
        'ai_summary',
        'relevance_score',
        'relevance_explanation',
        'processed_at',
        'api_processing_status',
        'api_processing_error',
        'timeline_events_json',
        'processing_metadata_json',
    ];

    protected function casts(): array
    {
        return [
            'processed_at' => 'datetime',
            'file_size' => 'integer',
            'relevance_score' => 'decimal:2',
            'timeline_events_json' => 'array',
            'processing_metadata_json' => 'array',
        ];
    }

    /**
     * Relationships
     */
    public function wooRequest(): BelongsTo
    {
        return $this->belongsTo(WooRequest::class);
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'document_question_links')
            ->withPivot('relevance_score', 'confirmed_by_case_manager', 'notes')
            ->withTimestamps();
    }

    /**
     * Scopes
     */
    public function scopeProcessed($query)
    {
        return $query->whereNotNull('processed_at');
    }

    public function scopeUnprocessed($query)
    {
        return $query->whereNull('processed_at');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('file_type', $type);
    }

    public function scopeApiPending($query)
    {
        return $query->where('api_processing_status', 'pending');
    }

    public function scopeApiFailed($query)
    {
        return $query->where('api_processing_status', 'failed');
    }

    public function scopeNeedsApiRetry($query)
    {
        return $query->whereIn('api_processing_status', ['pending', 'failed']);
    }

    /**
     * Helpers
     */
    public function isProcessed(): bool
    {
        return $this->processed_at !== null;
    }

    public function getFileSizeFormatted(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileUrl(): string
    {
        return Storage::disk('woo-documents')->url($this->file_path);
    }

    public function hasContent(): bool
    {
        return ! empty($this->content_markdown);
    }

    public function hasSummary(): bool
    {
        return ! empty($this->ai_summary);
    }

    public function isLinkedToQuestions(): bool
    {
        return $this->questions()->exists();
    }

    public function isApiProcessed(): bool
    {
        return $this->api_processing_status === 'completed';
    }

    public function hasTimelineEvents(): bool
    {
        return ! empty($this->timeline_events_json);
    }

    public function getTimelineEvents(): array
    {
        return $this->timeline_events_json ?? [];
    }

    public function getProcessingMetadata(): array
    {
        return $this->processing_metadata_json ?? [];
    }

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

        static::creating(function ($document): void {
            // Generate UUID if not set
            if (empty($document->uuid)) {
                $document->uuid = (string) Str::uuid();
            }

            // Generate external_document_id if not set
            if (empty($document->external_document_id)) {
                $document->external_document_id = (string) Str::uuid();
            }
        });

        static::created(function ($document): void {
            // Update submission documents count
            $document->submission->updateDocumentsCount();
        });

        static::deleted(function ($document): void {
            // Update submission documents count
            if ($document->submission) {
                $document->submission->updateDocumentsCount();
            }

            // Delete file from storage
            Storage::disk('woo-documents')->delete($document->file_path);
        });
    }
}
