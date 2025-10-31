<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $woo_request_id
 * @property string $summary_b1
 * @property array $key_reasons_json
 * @property array $process_outline_json
 * @property array $source_refs_json
 * @property int $document_count
 * @property \Carbon\CarbonInterface $generated_at
 * @property-read WooRequest $wooRequest
 */
class CaseDecision extends Model
{
    protected $fillable = [
        'woo_request_id',
        'summary_b1',
        'key_reasons_json',
        'process_outline_json',
        'source_refs_json',
        'document_count',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'key_reasons_json' => 'array',
            'process_outline_json' => 'array',
            'source_refs_json' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    /**
     * Relationships
     */
    public function wooRequest(): BelongsTo
    {
        return $this->belongsTo(WooRequest::class);
    }

    /**
     * Helpers
     */
    public function getKeyReasons(): array
    {
        return $this->key_reasons_json ?? [];
    }

    public function getProcessOutline(): array
    {
        return $this->process_outline_json ?? [];
    }

    public function getSourceRefs(): array
    {
        return $this->source_refs_json ?? [];
    }

    public function hasSummary(): bool
    {
        return ! empty($this->summary_b1);
    }

    public function isStale(int $currentDocumentCount): bool
    {
        return $this->document_count !== $currentDocumentCount;
    }

    public function getFormattedSummary(): string
    {
        return nl2br(e($this->summary_b1));
    }
}
