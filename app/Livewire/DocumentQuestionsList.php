<?php

namespace App\Livewire;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentQuestionsList extends Component
{
    use WithPagination;

    public Document $document;
    public string $search = '';

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $wooRequest = $this->document->wooRequest;

        // Get questions with pivot data through the relationship
        $query = $this->document->questions()
            ->withCount('documents');

        // Search functionality
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('question_text', 'like', '%' . $this->search . '%')
                    ->orWhere('ai_summary', 'like', '%' . $this->search . '%');
            });
        }

        // Sort by relevance_score from pivot (descending), then by question creation date
        // We need to order by pivot, handling NULL values by treating them as 0
        // Note: orderByPivot doesn't support NULL handling directly, so we use orderByRaw
        $query->orderByRaw('COALESCE(document_question_links.relevance_score, 0) DESC')
              ->orderBy('questions.created_at', 'desc');

        $questions = $query->paginate(10);

        return view('livewire.document-questions-list', [
            'questions' => $questions,
            'wooRequest' => $wooRequest,
        ]);
    }
}

