<?php

namespace App\Jobs;

use App\Models\Document;
use App\Services\DocumentLinkingService;
use App\Services\DocumentProcessingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessUploadedDocument implements ShouldQueue
{
    use Queueable;

    public $timeout = 300; // 5 minutes
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Document $document
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(
        DocumentProcessingService $processingService,
        DocumentLinkingService $linkingService
    ): void {
        try {
            Log::info('Processing uploaded document', [
                'document_id' => $this->document->id,
                'woo_request_id' => $this->document->woo_request_id,
            ]);

            // Process document through API
            $result = $processingService->processUploadedDocument($this->document);

            // Auto-link to questions
            $linkedCount = $linkingService->autoLink($this->document, 0.5);

            // Update related question statuses
            foreach ($this->document->questions as $question) {
                $linkingService->updateQuestionStatus($question);
            }

            Log::info('Uploaded document processed successfully', [
                'document_id' => $this->document->id,
                'linked_questions' => $linkedCount,
            ]);

            // Update submission's internal request status
            $submission = $this->document->submission;
            $internalRequest = $submission->internalRequest;
            
            if ($internalRequest->status === 'pending') {
                $internalRequest->update(['status' => 'submitted']);
            }
        } catch (\Exception $e) {
            Log::error('Failed to process uploaded document', [
                'document_id' => $this->document->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Uploaded document processing failed permanently', [
            'document_id' => $this->document->id,
            'error' => $exception->getMessage(),
        ]);

        // Optionally notify case manager
    }
}
