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

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
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

        $questions = $query->paginate(10);

        return view('livewire.questions-list', [
            'questions' => $questions,
        ]);
    }
}

