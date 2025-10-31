<?php

namespace App\Services;

use App\Models\Question;
use App\Models\WooRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class QuestionExtractionService
{
    /**
     * Extract questions from API response
     */
    public function extractQuestionsFromApiResponse(WooRequest $wooRequest, array $apiResponse): Collection
    {
        $questions = collect($apiResponse['questions'] ?? []);

        return $questions->map(fn (array $questionData, int $index): \App\Models\Question => $this->createQuestion($wooRequest, $questionData, $index));
    }

    /**
     * Extract questions from markdown content
     */
    public function extractQuestionsFromMarkdown(WooRequest $wooRequest, string $markdown): Collection
    {
        $questions = $this->parseMarkdownForQuestions($markdown);

        return $questions->map(fn ($questionText, int $index): \App\Models\Question => $this->createQuestion($wooRequest, [
            'question_text' => $questionText,
        ], $index));
    }

    /**
     * Create a question from data
     */
    protected function createQuestion(WooRequest $wooRequest, array $questionData, int $order): Question
    {
        return Question::create([
            'woo_request_id' => $wooRequest->id,
            'question_text' => $questionData['question_text'] ?? $questionData['text'] ?? '',
            'order' => $order,
            'status' => 'unanswered',
        ]);
    }

    /**
     * Parse markdown content for questions
     */
    protected function parseMarkdownForQuestions(string $markdown): Collection
    {
        $questions = collect();

        // Split by common question patterns
        $lines = explode("\n", $markdown);

        foreach ($lines as $line) {
            $line = trim($line);

            // Check if line looks like a question
            if ($this->looksLikeQuestion($line)) {
                $questions->push($this->cleanQuestion($line));
            }
        }

        return $questions->filter()->values();
    }

    /**
     * Check if text looks like a question
     */
    protected function looksLikeQuestion(string $text): bool
    {
        if ($text === '' || $text === '0') {
            return false;
        }

        // Check for question mark
        if (Str::contains($text, '?')) {
            return true;
        }

        // Check for numbered questions (e.g., "1. ", "a) ", "1) ")
        if (preg_match('/^(\d+[\.\)]\s|[a-z][\.\)]\s)/i', $text)) {
            return true;
        }

        // Check for question keywords in Dutch
        $questionKeywords = [
            'wat', 'wie', 'waar', 'wanneer', 'waarom', 'hoe',
            'welk', 'welke', 'hoeveel', 'kan ', 'wil ', 'zou ',
            'is er', 'zijn er', 'heeft', 'hebben',
        ];

        foreach ($questionKeywords as $keyword) {
            if (Str::startsWith(Str::lower($text), $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Clean up question text
     */
    protected function cleanQuestion(string $text): string
    {
        // Remove numbering (e.g., "1. ", "a) ")
        $text = preg_replace('/^(\d+[\.\)]\s|[a-z][\.\)]\s)/i', '', $text);

        // Remove markdown formatting
        $text = preg_replace('/[*_`#]/', '', (string) $text);

        // Trim whitespace
        $text = trim((string) $text);

        return $text;
    }

    /**
     * Update questions from new data
     */
    public function updateQuestions(WooRequest $wooRequest, array $newQuestions): void
    {
        // Get existing questions
        $existingQuestions = $wooRequest->questions;

        // Delete questions that are no longer in the new list
        $newQuestionTexts = collect($newQuestions)->pluck('question_text');
        $existingQuestions->each(function ($question) use ($newQuestionTexts): void {
            if (! $newQuestionTexts->contains($question->question_text)) {
                $question->delete();
            }
        });

        // Add new questions
        foreach ($newQuestions as $index => $questionData) {
            $exists = $existingQuestions->firstWhere('question_text', $questionData['question_text']);

            if (! $exists) {
                $this->createQuestion($wooRequest, $questionData, $index);
            }
        }
    }

    /**
     * Merge similar questions
     */
    public function mergeSimilarQuestions(WooRequest $wooRequest, float $similarityThreshold = 0.8): int
    {
        /** @phpstan-ignore-next-line */
        $questions = $wooRequest->questions()->ordered()->get();
        $mergedCount = 0;

        foreach ($questions as $question) {
            foreach ($questions as $otherQuestion) {
                if ($question->id === $otherQuestion->id) {
                    continue;
                }

                $similarity = $this->calculateSimilarity(
                    $question->question_text,
                    $otherQuestion->question_text
                );

                if ($similarity >= $similarityThreshold) {
                    // Merge questions by keeping the first one
                    $otherQuestion->delete();
                    $mergedCount++;
                }
            }
        }

        return $mergedCount;
    }

    /**
     * Calculate similarity between two strings
     */
    protected function calculateSimilarity(string $text1, string $text2): float
    {
        similar_text(
            Str::lower($text1),
            Str::lower($text2),
            $percentage
        );

        return $percentage / 100;
    }
}
