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
    ) {}

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
                'external_document_id' => $this->document->external_document_id,
                'woo_request_id' => $this->document->woo_request_id,
            ]);

            // Ensure document has external UUID
            if (empty($this->document->external_document_id)) {
                throw new \Exception('Document must have external_document_id');
            }

            // Update status to processing
            $this->document->update([
                'api_processing_status' => 'processing',
                'api_processing_error' => null,
            ]);

            // Get related models
            $wooRequest = $this->document->wooRequest;
            $submission = $this->document->submission;

            if (! $wooRequest || ! $submission) {
                throw new \Exception('Document must have associated WooRequest and Submission');
            }

            // Ensure case exists in API
            $processingService->ensureCase($wooRequest);

            // Ensure submission exists in API
            $processingService->ensureSubmission($submission);

            // Upload document to API (processing happens synchronously)
            $uploadResult = $processingService->uploadDocument($this->document);
            Log::info('Document uploaded successfully', [
                'document_id' => $this->document->id,
                'upload_result' => $uploadResult,
            ]);

            // Fetch detailed document information
            $documentDetails = $processingService->getDocumentDetails($this->document->external_document_id);

            // Fetch markdown content
            $markdownData = $processingService->getDocumentMarkdown($this->document->external_document_id);

            // Extract data from API responses
            $contentMarkdown = $markdownData['markdown_text'] ?? null;
            $aiSummary = $documentDetails['processing']['summary'] ?? null;
            $relevanceScore = $documentDetails['processing']['relevance_score'] ?? null;
            $timelineEvents = $documentDetails['timeline'] ?? [];
            $processingMetadata = [
                'confidence_score' => $documentDetails['processing']['confidence_score'] ?? null,
                'has_markdown' => $documentDetails['processing']['has_markdown'] ?? false,
                'has_timeline' => $documentDetails['processing']['has_timeline'] ?? false,
                'file_type' => $documentDetails['file_type'] ?? null,
                'page_count' => $documentDetails['page_count'] ?? null,
                'processed_at_api' => $documentDetails['processed_at'] ?? null,
            ];

            // Update document with processed data
            $this->document->update([
                'content_markdown' => $contentMarkdown,
                'ai_summary' => $aiSummary,
                'relevance_score' => $relevanceScore,
                'timeline_events_json' => $timelineEvents,
                'processing_metadata_json' => $processingMetadata,
                'processed_at' => now(),
                'api_processing_status' => 'completed',
                'api_processing_error' => null,
            ]);

            Log::info('Document data stored successfully', [
                'document_id' => $this->document->id,
                'has_markdown' => ! empty($contentMarkdown),
                'has_summary' => ! empty($aiSummary),
                'timeline_event_count' => count($timelineEvents),
            ]);

            // Regenerate aggregated timeline and decision
            try {
                $timelineData = $processingService->getTimeline($wooRequest);
                $processingService->storeTimeline($wooRequest, $timelineData);
                Log::info('Timeline updated', [
                    'woo_request_id' => $wooRequest->id,
                    'event_count' => count($timelineData['events'] ?? []),
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to update timeline (non-critical)', [
                    'woo_request_id' => $wooRequest->id,
                    'error' => $e->getMessage(),
                ]);
            }

            try {
                $decisionData = $processingService->getDecisionOverview($wooRequest);
                $processingService->storeDecision($wooRequest, $decisionData);
                Log::info('Decision overview updated', [
                    'woo_request_id' => $wooRequest->id,
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to update decision overview (non-critical)', [
                    'woo_request_id' => $wooRequest->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Auto-link to questions
            $linkedCount = $linkingService->autoLink($this->document, 0.5);

            // Update related question statuses
            foreach ($this->document->questions as $question) {
                $linkingService->updateQuestionStatus($question);
            }

            Log::info('Document processed successfully', [
                'document_id' => $this->document->id,
                'linked_questions' => $linkedCount,
            ]);

            // Update submission's internal request status if needed
            $internalRequest = $submission->internalRequest;

            if ($internalRequest && $internalRequest->status === 'pending') {
                $internalRequest->update(['status' => 'submitted']);
            }
        } catch (\Exception $e) {
            Log::error('Failed to process uploaded document', [
                'document_id' => $this->document->id,
                'external_document_id' => $this->document->external_document_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Update document with failed status
            $this->document->update([
                'api_processing_status' => 'failed',
                'api_processing_error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Document processing failed permanently', [
            'document_id' => $this->document->id,
            'external_document_id' => $this->document->external_document_id,
            'error' => $exception->getMessage(),
        ]);

        // Mark document as permanently failed
        $this->document->update([
            'api_processing_status' => 'failed',
            'api_processing_error' => 'Processing failed after ' . $this->tries . ' attempts: ' . $exception->getMessage(),
        ]);

        // Optionally notify case manager
    }
}
