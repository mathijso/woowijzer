<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\WooRequest;
use App\Services\DocumentLinkingService;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display questions for a WOO request
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $wooRequestId = $request->query('woo_request_id');

        // Support both numeric ID and UUID
        if (is_numeric($wooRequestId)) {
            $wooRequest = WooRequest::findOrFail($wooRequestId);
        } else {
            $wooRequest = WooRequest::where('uuid', $wooRequestId)->firstOrFail();
        }

        $this->authorize('view', $wooRequest);

        /** @phpstan-ignore-next-line */
        $questions = $wooRequest->questions()
            ->with('documents')
            ->ordered()
            ->get();

        /** @phpstan-ignore-next-line */
        return view('questions.index', ['questions' => $questions, 'wooRequest' => $wooRequest]);
    }

    /**
     * Show a single question with linked documents
     */
    public function show(WooRequest $wooRequest, Question $question)
    {
        // Validate that question belongs to the case
        if ($question->woo_request_id !== $wooRequest->id) {
            abort(404, 'Question does not belong to this case.');
        }

        $this->authorize('view', $wooRequest);

        // Load question with documents and their pivot data
        $question->load(['documents' => function ($query) {
            $query->orderByPivot('created_at', 'desc');
        }, 'wooRequest']);

        return view('questions.show', [
            'question' => $question,
            'wooRequest' => $wooRequest,
        ]);
    }

    /**
     * Update question
     */
    public function update(Request $request, WooRequest $wooRequest, Question $question)
    {
        // Validate that question belongs to the case
        if ($question->woo_request_id !== $wooRequest->id) {
            abort(404, 'Question does not belong to this case.');
        }

        $this->authorize('update', $wooRequest);

        $validated = $request->validate([
            'status' => 'sometimes|in:unanswered,partially_answered,answered',
            'ai_summary' => 'sometimes|nullable|string',
            'question_text' => 'sometimes|required|string',
        ]);

        $question->update($validated);

        return back()->with('success', 'Vraag is bijgewerkt.');
    }

    /**
     * Generate summary for a question
     */
    public function generateSummary(WooRequest $wooRequest, Question $question, DocumentLinkingService $linkingService)
    {
        // Validate that question belongs to the case
        if ($question->woo_request_id !== $wooRequest->id) {
            abort(404, 'Question does not belong to this case.');
        }

        $this->authorize('update', $wooRequest);

        $documents = $question->documents()
            ->wherePivot('confirmed_by_case_manager', true)
            ->get();

        if ($documents->isEmpty()) {
            return back()->with('error', 'Er zijn nog geen bevestigde documenten gekoppeld aan deze vraag.');
        }

        // Compile summary
        $summaryParts = [];
        foreach ($documents as $document) {
            /** @phpstan-ignore-next-line */
            if ($document->ai_summary) {
                /** @phpstan-ignore-next-line */
                $summaryParts[] = "**{$document->file_name}:**\n{$document->ai_summary}";
            }
        }

        if ($summaryParts !== []) {
            $summary = "## Samenvatting\n\n" . implode("\n\n---\n\n", $summaryParts);
            $question->update(['ai_summary' => $summary]);
        }

        return back()->with('success', 'Samenvatting is gegenereerd.');
    }

    /**
     * Delete question
     */
    public function destroy(WooRequest $wooRequest, Question $question)
    {
        // Validate that question belongs to the case
        if ($question->woo_request_id !== $wooRequest->id) {
            abort(404, 'Question does not belong to this case.');
        }

        $this->authorize('delete', $wooRequest);

        $question->delete();

        return redirect()
            ->route('questions.index', ['woo_request_id' => $wooRequest->uuid])
            ->with('success', 'Vraag is verwijderd.');
    }
}
