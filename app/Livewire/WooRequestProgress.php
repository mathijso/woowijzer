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

    public function getStatusPhasesProperty(): array
    {
        $status = $this->wooRequest->status;
        
        // Define all phases with their details
        $phases = [
            [
                'key' => 'submitted',
                'title' => 'Aanvraag ingediend',
                'description' => 'Uw Woo-verzoek is ontvangen',
                'icon' => 'document-text',
                'completed' => in_array($status, ['submitted', 'in_review', 'in_progress', 'completed']),
                'current' => $status === 'submitted',
                'date' => $this->wooRequest->submitted_at?->format('d-m-Y H:i'),
            ],
            [
                'key' => 'in_review',
                'title' => 'In behandeling',
                'description' => 'Het verzoek wordt beoordeeld',
                'icon' => 'clipboard-document-check',
                'completed' => in_array($status, ['in_review', 'in_progress', 'completed']),
                'current' => $status === 'in_review',
                'date' => null,
            ],
            [
                'key' => 'collecting',
                'title' => 'Documenten verzamelen',
                'description' => 'Relevante documenten worden verzameld',
                'icon' => 'folder-open',
                'completed' => in_array($status, ['in_progress', 'completed']) && $this->wooRequest->documents()->count() > 0,
                'current' => $status === 'in_progress' && $this->wooRequest->documents()->count() === 0,
                'date' => null,
            ],
            [
                'key' => 'processing',
                'title' => 'Informatie verwerken',
                'description' => 'Documenten worden geanalyseerd en vragen beantwoord',
                'icon' => 'cog-6-tooth',
                'completed' => in_array($status, ['in_progress', 'completed']) && $this->progressPercentage > 0,
                'current' => $status === 'in_progress' && $this->wooRequest->documents()->count() > 0,
                'date' => null,
            ],
            [
                'key' => 'decision',
                'title' => 'Besluitvorming',
                'description' => 'Het definitieve besluit wordt voorbereid',
                'icon' => 'scale',
                'completed' => $status === 'completed',
                'current' => $status === 'in_progress' && $this->progressPercentage >= 80,
                'date' => null,
            ],
            [
                'key' => 'publication',
                'title' => 'Communicatie / Publicatie',
                'description' => 'Het besluit wordt gecommuniceerd',
                'icon' => 'paper-airplane',
                'completed' => $status === 'completed',
                'current' => false,
                'date' => $this->wooRequest->completed_at?->format('d-m-Y H:i'),
            ],
        ];

        return $phases;
    }

    public function render()
    {
        return view('livewire.woo-request-progress', [
            'progressPercentage' => $this->progressPercentage,
            'questionStats' => $this->questionStats,
            'statusPhases' => $this->statusPhases,
        ]);
    }
}

