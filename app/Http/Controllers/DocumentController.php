<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Question;
use App\Models\WooRequest;
use App\Services\DocumentLinkingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents for a WOO request
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $wooRequestId = $request->query('woo_request_id');
        $wooRequest = null;
        $sortBy = $request->query('sort', $wooRequestId ? 'relevance' : 'date');
        $sortOrder = $request->query('order', 'desc');

        $query = Document::with(['submission.internalRequest', 'questions']);

        if ($wooRequestId) {
            $wooRequest = WooRequest::findOrFail($wooRequestId);
            $this->authorize('view', $wooRequest);
            $query->where('woo_request_id', $wooRequestId);
        }

        // Apply sorting
        if ($sortBy === 'relevance' && $wooRequestId) {
            // Sort by relevance_score (nulls last), then by date
            // SQLite doesn't support NULLS LAST, so we use a CASE statement
            $query->orderByRaw('CASE WHEN relevance_score IS NULL THEN 1 ELSE 0 END')
                  ->orderBy('relevance_score', 'desc')
                  ->orderBy('created_at', $sortOrder);
        } elseif ($sortBy === 'relevance' && !$wooRequestId) {
            // If no woo_request_id but relevance sort requested, fall back to date
            $query->orderBy('created_at', $sortOrder);
        } elseif ($sortBy === 'name') {
            $query->orderBy('file_name', $sortOrder);
        } else {
            // Default: sort by date
            $query->orderBy('created_at', $sortOrder);
        }

        $documents = $query->paginate(20)->withQueryString();

        return view('documents.index', [
            'documents' => $documents,
            'wooRequest' => $wooRequest,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    /**
     * Display the specified document
     */
    public function show(Document $document): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $this->authorize('view', $document->wooRequest);

        $document->load([
            'wooRequest',
            'submission.internalRequest',
            'questions',
        ]);

        return view('documents.show', ['document' => $document]);
    }

    /**
     * Remove the specified document
     */
    public function destroy(Document $document)
    {
        $this->authorize('delete', $document->wooRequest);

        $wooRequestId = $document->woo_request_id;

        // File will be deleted by model observer
        $document->delete();

        return redirect()
            ->route('documents.index', ['woo_request_id' => $wooRequestId])
            ->with('success', 'Document is verwijderd.');
    }

    /**
     * Link document to question
     */
    public function linkToQuestion(Request $request, Document $document, DocumentLinkingService $linkingService)
    {
        $this->authorize('update', $document->wooRequest);

        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'relevance_score' => 'nullable|numeric|min:0|max:1',
            'confirmed' => 'boolean',
        ]);

        $question = Question::findOrFail($validated['question_id']);

        $linkingService->linkDocumentToQuestion(
            $document,
            $question,
            $validated['relevance_score'] ?? null,
            $validated['confirmed'] ?? false
        );

        return back()->with('success', 'Document is gekoppeld aan de vraag.');
    }

    /**
     * Unlink document from question
     */
    public function unlinkFromQuestion(Document $document, Question $question, DocumentLinkingService $linkingService)
    {
        $this->authorize('update', $document->wooRequest);

        $linkingService->unlinkDocumentFromQuestion($document, $question);

        return back()->with('success', 'Koppeling is verwijderd.');
    }

    /**
     * Confirm link between document and question
     */
    public function confirmLink(Request $request, Document $document, Question $question, DocumentLinkingService $linkingService)
    {
        $this->authorize('update', $document->wooRequest);

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $linkingService->confirmLink($document, $question, $validated['notes'] ?? null);
        $linkingService->updateQuestionStatus($question);

        return back()->with('success', 'Koppeling is bevestigd.');
    }

    /**
     * Download document
     */
    public function download(Document $document)
    {
        $this->authorize('view', $document->wooRequest);

        return Storage::disk('woo-documents')->download(
            $document->file_path,
            $document->file_name
        );
    }
}
