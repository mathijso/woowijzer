<?php

namespace App\Jobs;

use App\Models\Question;
use App\Models\WooRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateQuestionSummaries implements ShouldQueue
{
    use Queueable;

    public $timeout = 180; // 3 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(
        public WooRequest $wooRequest
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Generating question summaries', [
                'woo_request_id' => $this->wooRequest->id,
            ]);

            $questions = $this->wooRequest->questions;

            foreach ($questions as $question) {
                $this->generateSummaryForQuestion($question);
            }

            Log::info('Question summaries generated successfully', [
                'woo_request_id' => $this->wooRequest->id,
                'questions_count' => $questions->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to generate question summaries', [
                'woo_request_id' => $this->wooRequest->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Generate summary for a single question
     */
    protected function generateSummaryForQuestion(Question $question): void
    {
        $documents = $question->documents()
            ->wherePivot('confirmed_by_case_manager', true)
            ->get();

        if ($documents->isEmpty()) {
            return;
        }

        // Compile summary from linked documents
        $summaryParts = [];

        foreach ($documents as $document) {
            if ($document->ai_summary) {
                $summaryParts[] = "**{$document->file_name}:**\n{$document->ai_summary}";
            } elseif ($document->content_markdown) {
                // Take first 500 characters if no AI summary
                $excerpt = substr((string) $document->content_markdown, 0, 500);
                $summaryParts[] = "**{$document->file_name}:**\n{$excerpt}...";
            }
        }

        if ($summaryParts !== []) {
            $summary = "## Samenvatting\n\n" . implode("\n\n---\n\n", $summaryParts);

            $question->update([
                'ai_summary' => $summary,
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Question summaries generation failed permanently', [
            'woo_request_id' => $this->wooRequest->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
