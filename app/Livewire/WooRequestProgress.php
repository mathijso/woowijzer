<?php

namespace App\Livewire;

use App\Models\WooRequest;
use Livewire\Component;

class WooRequestProgress extends Component
{
    public WooRequest $wooRequest;
    public string $viewType = 'simple'; // 'detailed' or 'simple'

    protected $listeners = [
        'question-status-updated' => 'refreshProgress',
        'woo-request-status-updated' => 'refreshProgress',
    ];

    public function mount(WooRequest $wooRequest, string $viewType = 'simple'): void
    {
        $this->wooRequest = $wooRequest;
        $this->viewType = $viewType;
    }

    public function refreshProgress(): void
    {
        // Refresh the WooRequest model to get latest question stats
        $this->wooRequest->refresh();
        $this->wooRequest->load('questions');
    }

    public function filterByStatus(string $status): void
    {
        // Dispatch event to QuestionsList component to filter
        $this->dispatch('filter-questions-by-status', status: $status);
        
        // Scroll to questions section
        $this->dispatch('scroll-to-questions');
    }

    public function getProgressPercentageProperty(): float
    {
        $totalQuestions = $this->wooRequest->questions()->count();

        if ($totalQuestions === 0) {
            return 0;
        }

        $answeredQuestions = $this->wooRequest->questions()
            ->where('status', 'answered')
            ->count();

        return round(($answeredQuestions / $totalQuestions) * 100, 2);
    }

    public function getQuestionStatsProperty(): array
    {
        return [
            'total' => $this->wooRequest->questions()->count(),
            'unanswered' => $this->wooRequest->questions()
                ->where('status', 'unanswered')
                ->count(),
            'partially_answered' => $this->wooRequest->questions()
                ->where('status', 'partially_answered')
                ->count(),
            'answered' => $this->wooRequest->questions()
                ->where('status', 'answered')
                ->count(),
        ];
    }

    public function render()
    {
        return view('livewire.woo-request-progress', [
            'progressPercentage' => $this->progressPercentage,
            'questionStats' => $this->questionStats,
        ]);
    }
}

