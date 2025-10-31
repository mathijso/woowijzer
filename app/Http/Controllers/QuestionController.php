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
    public function index(Request $request)
    {
        $wooRequestId = $request->query('woo_request_id');
        $wooRequest = WooRequest::findOrFail($wooRequestId);
        
        $this->authorize('view', $wooRequest);

        $questions = $wooRequest->questions()
            ->with('documents')
            ->ordered()
            ->get();

        return view('questions.index', compact('questions', 'wooRequest'));
    }

    /**
     * Update question
     */
    public function update(Request $request, Question $question)
    {
        $this->authorize('update', $question->wooRequest);

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
    public function generateSummary(Question $question, DocumentLinkingService $linkingService)
    {
        $this->authorize('update', $question->wooRequest);

        $documents = $question->documents()
            ->wherePivot('confirmed_by_case_manager', true)
            ->get();

        if ($documents->isEmpty()) {
            return back()->with('error', 'Er zijn nog geen bevestigde documenten gekoppeld aan deze vraag.');
        }

        // Compile summary
        $summaryParts = [];
        foreach ($documents as $document) {
            if ($document->ai_summary) {
                $summaryParts[] = "**{$document->file_name}:**\n{$document->ai_summary}";
            }
        }

        if (!empty($summaryParts)) {
            $summary = "## Samenvatting\n\n" . implode("\n\n---\n\n", $summaryParts);
            $question->update(['ai_summary' => $summary]);
        }

        return back()->with('success', 'Samenvatting is gegenereerd.');
    }

    /**
     * Delete question
     */
    public function destroy(Question $question)
    {
        $this->authorize('delete', $question->wooRequest);

        $wooRequestId = $question->woo_request_id;
        $question->delete();

        return redirect()
            ->route('questions.index', ['woo_request_id' => $wooRequestId])
            ->with('success', 'Vraag is verwijderd.');
    }
}
