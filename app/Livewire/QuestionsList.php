<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\WooRequest;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionsList extends Component
{
    use WithPagination;

    public WooRequest $wooRequest;
    public string $search = '';
    public string $statusFilter = '';

    protected $queryString = ['search', 'statusFilter'];

    protected $listeners = [
        'filter-questions-by-status' => 'filterByStatus',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function filterByStatus(string $status): void
    {
        // Set the status filter ('all', 'unanswered', 'partially_answered', 'answered')
        $this->statusFilter = $status === 'all' ? '' : $status;
        $this->resetPage();
    }

    public function clearFilter(): void
    {
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = $this->wooRequest->questions()
            ->withCount('documents')
            ->ordered();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('question_text', 'like', '%' . $this->search . '%')
                    ->orWhere('ai_summary', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $questions = $query->paginate(10);

        return view('livewire.questions-list', [
            'questions' => $questions,
        ]);
    }
}

