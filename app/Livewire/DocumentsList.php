<?php

namespace App\Livewire;

use App\Models\WooRequest;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentsList extends Component
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
        $query = $this->wooRequest->documents()
            ->withCount('questions')
            ->orderBy('relevance_score', 'desc')
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('file_name', 'like', '%' . $this->search . '%')
                    ->orWhere('file_type', 'like', '%' . $this->search . '%');
            });
        }

        $documents = $query->paginate(10);

        return view('livewire.documents-list', [
            'documents' => $documents,
        ]);
    }
}

