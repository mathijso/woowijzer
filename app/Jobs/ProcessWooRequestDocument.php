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
            // This also extracts and stores case_file data if available (new API format)
            $caseData = $processingService->ensureCase($this->wooRequest);

            Log::info('Case data received from API', [
                'woo_request_id' => $this->wooRequest->id,
                'case_data' => $caseData,
                'has_case_file_in_response' => isset($caseData['case_file']),
            ]);

            // Refresh model to get any updates from ensureCase
            $this->wooRequest->refresh();

            Log::info('WooRequest state after ensureCase', [
                'woo_request_id' => $this->wooRequest->id,
                'extracted_at' => $this->wooRequest->extracted_at,
                'extracted_title' => $this->wooRequest->extracted_title,
                'extracted_description' => $this->wooRequest->extracted_description,
                'extracted_questions' => $this->wooRequest->extracted_questions,
            ]);

            // If case_file data wasn't in the case response, extract it separately
            if (empty($this->wooRequest->extracted_at)) {
                Log::info('No case_file in response, calling separate extract endpoint');
                $filePath = \Illuminate\Support\Facades\Storage::disk('woo-documents')->path($this->wooRequest->original_file_path);
                $caseId = (string) $this->wooRequest->id;
                $result = $processingService->extractCaseFile($caseId, $filePath);

                // Store extracted case file data
                $this->wooRequest->update([
                    'extracted_title' => $result['title'] ?? null,
                    'extracted_description' => $result['description'] ?? null,
                    'extracted_at' => now(),
                ]);

                // Extract questions from API response
                if (! empty($result['questions'])) {
                    $questionService->extractQuestionsFromApiResponse($this->wooRequest, $result);
                }
            } else {
                Log::info('Case file data already extracted from case response', [
                    'woo_request_id' => $this->wooRequest->id,
                    'question_count' => count($this->wooRequest->extracted_questions ?? []),
                ]);

                // Create questions from already extracted data
                if (! empty($this->wooRequest->extracted_questions)) {
                    $questionService->extractQuestionsFromApiResponse($this->wooRequest, [
                        'questions' => $this->wooRequest->extracted_questions,
                    ]);
                }
            }

            // Fallback: extract questions from markdown if no questions yet
            if ($this->wooRequest->questions()->count() === 0 && $this->wooRequest->original_file_content_markdown) {
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
