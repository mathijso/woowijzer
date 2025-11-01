<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\WooRequest;
use Livewire\Component;

class QuestionStatusButtons extends Component
{
    public Question $question;
    public WooRequest $wooRequest;

    protected $listeners = ['refresh' => '$refresh'];

    public function updateStatus(string $status): void
    {
        // Validate authorization
        if (! auth()->user()?->isCaseManager()) {
            abort(403, 'Unauthorized');
        }

        // Validate status
        $validStatuses = ['unanswered', 'partially_answered', 'answered'];
        if (! in_array($status, $validStatuses)) {
            return;
        }

        // Update question status
        $this->question->update(['status' => $status]);

        // Refresh the question model
        $this->question->refresh();

        // Dispatch event to update other parts of the page
        $this->dispatch('question-status-updated', ['status' => $status, 'questionId' => $this->question->id]);

        // Flash success message
        session()->flash('status-updated', 'Status is bijgewerkt naar: ' . config('woo.question_statuses')[$status]);
    }

    public function render()
    {
        return view('livewire.question-status-buttons');
    }
}

