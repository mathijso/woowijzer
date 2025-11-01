<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\WooRequest;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionDocumentsList extends Component
{
    use WithPagination;

    public Question $question;
    public WooRequest $wooRequest;
    public string $search = '';

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = $this->question->documents()
            ->orderByPivot('created_at', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('file_name', 'like', '%' . $this->search . '%')
                    ->orWhere('file_type', 'like', '%' . $this->search . '%');
            });
        }

        $documents = $query->paginate(10);

        return view('livewire.question-documents-list', [
            'documents' => $documents,
        ]);
    }
}

