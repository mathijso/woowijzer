<?php

namespace App\Livewire;

use Livewire\Component;

class DocumentSummarizer extends Component
{
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.document-summarizer');
    }
}
