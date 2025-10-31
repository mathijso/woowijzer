<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Question;
use App\Models\WooRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DocumentLinkingService
{
    /**
     * Suggest document-question links based on content similarity
     */
    public function suggestLinks(Document $document): Collection
    {
        $wooRequest = $document->wooRequest;
        $questions = $wooRequest->questions;
        $suggestions = collect();

        if (! $document->hasContent()) {
            return $suggestions;
        }

        foreach ($questions as $question) {
            $relevanceScore = $this->calculateRelevance($document, $question);

            if ($relevanceScore > 0.3) { // Only suggest if relevance > 30%
                $suggestions->push([
                    'question' => $question,
                    'relevance_score' => $relevanceScore,
                ]);
            }
        }

        return $suggestions->sortByDesc('relevance_score');
    }

    /**
     * Calculate relevance between document and question
     */
    protected function calculateRelevance(Document $document, Question $question): float
    {
        $documentContent = Str::lower($document->content_markdown ?? '');
        $questionText = Str::lower($question->question_text);

        // Extract keywords from question
        $keywords = $this->extractKeywords($questionText);

        if ($keywords === []) {
            return 0.0;
        }

        // Count keyword matches
        $matchCount = 0;
        $totalKeywords = count($keywords);

        foreach ($keywords as $keyword) {
            if (Str::contains($documentContent, $keyword)) {
                $matchCount++;
            }
        }

        // Calculate base relevance
        $relevance = $matchCount / $totalKeywords;

        // Boost if question text appears directly in document
        if (Str::contains($documentContent, $questionText)) {
            $relevance = min(1.0, $relevance + 0.3);
        }

        return round($relevance, 2);
    }

    /**
     * Extract keywords from text
     */
    protected function extractKeywords(string $text): array
    {
        // Remove common Dutch stop words
        $stopWords = [
            'de', 'het', 'een', 'en', 'van', 'in', 'op', 'te', 'is', 'voor',
            'wat', 'wie', 'waar', 'wanneer', 'waarom', 'hoe', 'welk', 'welke',
            'zijn', 'heeft', 'hebben', 'kan', 'worden', 'werd', 'wordt',
        ];

        // Split into words and clean
        $words = preg_split('/\s+/', Str::lower($text));
        $words = array_map(fn ($word): ?string => preg_replace('/[^\w]/', '', $word), $words);

        // Remove stop words and short words
        $keywords = array_filter($words, fn (array|string|null $word): bool => ! in_array($word, $stopWords) && strlen((string) $word) > 3);

        return array_values(array_unique($keywords));
    }

    /**
     * Auto-link document to questions
     */
    public function autoLink(Document $document, float $threshold = 0.5): int
    {
        $suggestions = $this->suggestLinks($document);
        $linkedCount = 0;

        foreach ($suggestions as $suggestion) {
            if ($suggestion['relevance_score'] >= $threshold) {
                $this->linkDocumentToQuestion(
                    $document,
                    $suggestion['question'],
                    $suggestion['relevance_score']
                );
                $linkedCount++;
            }
        }

        return $linkedCount;
    }

    /**
     * Link document to question
     */
    public function linkDocumentToQuestion(
        Document $document,
        Question $question,
        ?float $relevanceScore = null,
        bool $confirmed = false
    ): void {
        $document->questions()->syncWithoutDetaching([
            $question->id => [
                'relevance_score' => $relevanceScore,
                'confirmed_by_case_manager' => $confirmed,
            ],
        ]);
    }

    /**
     * Unlink document from question
     */
    public function unlinkDocumentFromQuestion(Document $document, Question $question): void
    {
        $document->questions()->detach($question->id);
    }

    /**
     * Confirm link
     */
    public function confirmLink(Document $document, Question $question, ?string $notes = null): void
    {
        $document->questions()->updateExistingPivot($question->id, [
            'confirmed_by_case_manager' => true,
            'notes' => $notes,
        ]);
    }

    /**
     * Update question status based on linked documents
     */
    public function updateQuestionStatus(Question $question): void
    {
        $linkedDocuments = $question->documents()->count();
        $confirmedDocuments = $question->documents()
            ->wherePivot('confirmed_by_case_manager', true)
            ->count();

        if ($confirmedDocuments > 0 && $confirmedDocuments >= $linkedDocuments) {
            $question->update(['status' => 'answered']);
        } elseif ($linkedDocuments > 0) {
            $question->update(['status' => 'partially_answered']);
        } else {
            $question->update(['status' => 'unanswered']);
        }
    }

    /**
     * Auto-link all documents for a WOO request
     */
    public function autoLinkAllDocuments(WooRequest $wooRequest, float $threshold = 0.5): array
    {
        $documents = $wooRequest->documents()->processed()->get();
        $stats = [
            'total_documents' => $documents->count(),
            'total_links' => 0,
        ];

        foreach ($documents as $document) {
            $linkedCount = $this->autoLink($document, $threshold);
            $stats['total_links'] += $linkedCount;
        }

        return $stats;
    }
}
