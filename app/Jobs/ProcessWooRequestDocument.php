<?php

namespace App\Jobs;

use App\Models\WooRequest;
use App\Services\DocumentProcessingService;
use App\Services\QuestionExtractionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessWooRequestDocument implements ShouldQueue
{
    use Queueable;

    public $timeout = 300; // 5 minutes

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public WooRequest $wooRequest
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        DocumentProcessingService $processingService,
        QuestionExtractionService $questionService
    ): void {
        try {
            // Update status to processing
            $this->wooRequest->update([
                'processing_status' => 'processing',
            ]);

            Log::info('Processing WOO request document', [
                'woo_request_id' => $this->wooRequest->id,
            ]);

            // Ensure case exists in WOO Insight API
            $processingService->ensureCase($this->wooRequest);

            // Get the full file path
            $filePath = \Illuminate\Support\Facades\Storage::disk('woo-documents')->path($this->wooRequest->original_file_path);

            // Extract case file information through API
            $caseId = (string) $this->wooRequest->id;
            $result = $processingService->extractCaseFile($caseId, $filePath);

            // Extract and create questions
            if (! empty($result['questions'])) {
                $questionService->extractQuestionsFromApiResponse($this->wooRequest, $result);
            } elseif ($this->wooRequest->original_file_content_markdown) {
                // Fallback: extract questions from markdown
                $questionService->extractQuestionsFromMarkdown(
                    $this->wooRequest,
                    $this->wooRequest->original_file_content_markdown
                );
            }

            // Update status to completed
            $this->wooRequest->update([
                'processing_status' => 'completed',
                'processed_at' => now(),
                'processing_error' => null,
            ]);

            Log::info('WOO request document processed successfully', [
                'woo_request_id' => $this->wooRequest->id,
                'questions_count' => $this->wooRequest->questions()->count(),
            ]);
        } catch (\Exception $e) {
            // Update status to failed
            $this->wooRequest->update([
                'processing_status' => 'failed',
                'processing_error' => $e->getMessage(),
            ]);

            Log::error('Failed to process WOO request document', [
                'woo_request_id' => $this->wooRequest->id,
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
        // Update status to failed
        $this->wooRequest->update([
            'processing_status' => 'failed',
            'processing_error' => $exception->getMessage(),
        ]);

        Log::error('WOO request document processing failed permanently', [
            'woo_request_id' => $this->wooRequest->id,
            'error' => $exception->getMessage(),
        ]);

        // Optionally notify case manager or user
    }
}
