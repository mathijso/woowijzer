<?php

namespace App\Livewire;

use App\Models\WooRequest;
use Livewire\Component;

class WooRequestStatusButtons extends Component
{
    public WooRequest $wooRequest;

    protected $listeners = ['refresh' => '$refresh'];

    public function updateStatus(string $status): void
    {
        // Validate authorization
        if (! auth()->user()?->can('update', $this->wooRequest)) {
            abort(403, 'Unauthorized');
        }

        // Validate status
        $validStatuses = ['submitted', 'in_review', 'in_progress', 'completed', 'rejected'];
        if (! in_array($status, $validStatuses)) {
            return;
        }

        $oldStatus = $this->wooRequest->status;

        // Update WOO request status
        $this->wooRequest->update([
            'status' => $status,
            'completed_at' => $status === 'completed' ? now() : null,
        ]);

        // Refresh the model
        $this->wooRequest->refresh();

        // Dispatch event to update other parts of the page
        $this->dispatch('woo-request-status-updated', [
            'status' => $status,
            'wooRequestId' => $this->wooRequest->id,
        ]);

        // Flash success message
        $oldLabel = config('woo.woo_request_statuses')[$oldStatus] ?? $oldStatus;
        $newLabel = config('woo.woo_request_statuses')[$status] ?? $status;
        session()->flash('status-updated', sprintf(
            'Status is gewijzigd van "%s" naar "%s".',
            $oldLabel,
            $newLabel
        ));
    }

    public function render()
    {
        return view('livewire.woo-request-status-buttons');
    }
}

