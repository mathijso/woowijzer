<?php

namespace App\Livewire;

use App\Models\Question;
use Livewire\Component;

class QuestionStatusBadge extends Component
{
    public Question $question;
    public string $size = 'md'; // sm or md

    protected $listeners = ['question-status-updated' => 'refreshQuestion'];

    public function refreshQuestion($data): void
    {
        // Only refresh if this is the same question
        if (isset($data['questionId']) && $data['questionId'] == $this->question->id) {
            $this->question->refresh();
        }
    }

    public function render()
    {
        return view('livewire.question-status-badge');
    }
}

