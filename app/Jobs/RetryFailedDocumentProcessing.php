<?php

namespace App\Jobs;

use App\Models\Document;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class RetryFailedDocumentProcessing implements ShouldQueue
{
    use Queueable;

    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting retry of failed document processing');

        // Find documents that need retry (pending or failed status)
        $documents = Document::needsApiRetry()
            ->with(['wooRequest', 'submission'])
            ->limit(10) // Process in batches to avoid overwhelming the system
            ->get();

        if ($documents->isEmpty()) {
            Log::info('No documents need retry');

            return;
        }

        Log::info('Found documents to retry', [
            'count' => $documents->count(),
        ]);

        foreach ($documents as $document) {
            try {
                Log::info('Retrying document processing', [
                    'document_id' => $document->id,
                    'external_document_id' => $document->external_document_id,
                    'status' => $document->api_processing_status,
                    'previous_error' => $document->api_processing_error,
                ]);

                // Dispatch the processing job
                ProcessUploadedDocument::dispatch($document);

                Log::info('Dispatched retry job for document', [
                    'document_id' => $document->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to dispatch retry job for document', [
                    'document_id' => $document->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Completed retry batch', [
            'documents_retried' => $documents->count(),
        ]);
    }
}
