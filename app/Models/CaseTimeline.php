<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $woo_request_id
 * @property array $timeline_json
 * @property int $document_count
 * @property \Carbon\CarbonInterface $generated_at
 * @property-read WooRequest $wooRequest
 */
class CaseTimeline extends Model
{
    protected $fillable = [
        'woo_request_id',
        'timeline_json',
        'document_count',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'timeline_json' => 'array',
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
    public function getEvents(): array
    {
        return $this->timeline_json ?? [];
    }

    public function hasEvents(): bool
    {
        return ! empty($this->timeline_json);
    }

    public function getEventCount(): int
    {
        return count($this->timeline_json ?? []);
    }

    public function isStale(int $currentDocumentCount): bool
    {
        return $this->document_count !== $currentDocumentCount;
    }

    public function getEventsGroupedByDate(): array
    {
        $events = $this->getEvents();
        $grouped = [];

        foreach ($events as $event) {
            $date = $event['date'] ?? 'unknown';
            if (! isset($grouped[$date])) {
                $grouped[$date] = [];
            }
            $grouped[$date][] = $event;
        }

        ksort($grouped);

        return $grouped;
    }
}
