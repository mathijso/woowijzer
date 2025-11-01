<?php

namespace App\Livewire;

use App\Models\WooRequest;
use Livewire\Component;

class TimelineList extends Component
{
    public WooRequest $wooRequest;
    public array $expandedEvents = [];

    public function toggleEvent(int $index): void
    {
        if (in_array($index, $this->expandedEvents)) {
            $this->expandedEvents = array_values(array_diff($this->expandedEvents, [$index]));
        } else {
            $this->expandedEvents[] = $index;
            sort($this->expandedEvents);
        }
    }

    public function isExpanded(int $index): bool
    {
        return in_array($index, $this->expandedEvents);
    }

    public function render()
    {
        $events = [];
        
        if ($this->wooRequest->caseTimeline && $this->wooRequest->caseTimeline->hasEvents()) {
            $events = $this->wooRequest->caseTimeline->getEvents();
        }

        return view('livewire.timeline-list', [
            'events' => $events,
        ]);
    }
}

