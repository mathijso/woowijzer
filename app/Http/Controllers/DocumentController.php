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
    public function index(Request $request, WooRequest $wooRequest = null): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // Support both route parameter and query parameter for backwards compatibility
        if (! $wooRequest && $request->has('woo_request_id')) {
            $wooRequestId = $request->query('woo_request_id');
            if (is_numeric($wooRequestId)) {
                $wooRequest = WooRequest::find($wooRequestId);
            } else {
                $wooRequest = WooRequest::where('uuid', $wooRequestId)->firstOrFail();
            }
        }

        $sortBy = $request->query('sort', $wooRequest ? 'relevance' : 'date');
        $sortOrder = $request->query('order', 'desc');

        $query = Document::with(['submission.internalRequest', 'questions']);

        if ($wooRequest) {
            $this->authorize('view', $wooRequest);
            $query->where('woo_request_id', $wooRequest->id);
        }

        // Apply sorting
        if ($sortBy === 'relevance' && $wooRequest) {
            // Sort by relevance_score (nulls last), then by date
            // SQLite doesn't support NULLS LAST, so we use a CASE statement
            $query->orderByRaw('CASE WHEN relevance_score IS NULL THEN 1 ELSE 0 END')
                  ->orderBy('relevance_score', 'desc')
                  ->orderBy('created_at', $sortOrder);
        } elseif ($sortBy === 'relevance' && !$wooRequest) {
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
    public function show(Request $request, WooRequest $wooRequest, Document $document, ?string $tab = null): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // Validate that document belongs to the case
        if ($document->woo_request_id !== $wooRequest->id) {
            abort(404, 'Document does not belong to this case.');
        }

        $this->authorize('view', $wooRequest);

        // Get tab from request parameter if not in URL
        if (!$tab) {
            $tab = $request->query('tab', 'overview');
        }

        // Validate tab
        $validTabs = ['overview', 'summary', 'timeline', 'content', 'questions'];
        if (!in_array($tab, $validTabs)) {
            $tab = 'overview';
        }

        $document->load([
            'wooRequest',
            'submission.internalRequest',
            'questions',
        ]);

        return view('documents.show', [
            'document' => $document,
            'wooRequest' => $wooRequest,
            'activeTab' => $tab,
        ]);
    }

    /**
     * Remove the specified document
     */
    public function destroy(WooRequest $wooRequest, Document $document)
    {
        // Validate that document belongs to the case
        if ($document->woo_request_id !== $wooRequest->id) {
            abort(404, 'Document does not belong to this case.');
        }

        $this->authorize('delete', $wooRequest);

        // File will be deleted by model observer
        $document->delete();

        return redirect()
            ->route('woo-requests.show.tab', [$wooRequest, 'documents'])
            ->with('success', 'Document is verwijderd.');
    }

    /**
     * Link document to question
     */
    public function linkToQuestion(Request $request, WooRequest $wooRequest, Document $document, DocumentLinkingService $linkingService)
    {
        // Validate that document belongs to the case
        if ($document->woo_request_id !== $wooRequest->id) {
            abort(404, 'Document does not belong to this case.');
        }

        $this->authorize('update', $wooRequest);

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
    public function unlinkFromQuestion(WooRequest $wooRequest, Document $document, Question $question, DocumentLinkingService $linkingService)
    {
        // Validate that document belongs to the case
        if ($document->woo_request_id !== $wooRequest->id) {
            abort(404, 'Document does not belong to this case.');
        }

        // Validate that question belongs to the case
        if ($question->woo_request_id !== $wooRequest->id) {
            abort(404, 'Question does not belong to this case.');
        }

        $this->authorize('update', $wooRequest);

        $linkingService->unlinkDocumentFromQuestion($document, $question);

        return back()->with('success', 'Koppeling is verwijderd.');
    }

    /**
     * Confirm link between document and question
     */
    public function confirmLink(Request $request, WooRequest $wooRequest, Document $document, Question $question, DocumentLinkingService $linkingService)
    {
        // Validate that document belongs to the case
        if ($document->woo_request_id !== $wooRequest->id) {
            abort(404, 'Document does not belong to this case.');
        }

        // Validate that question belongs to the case
        if ($question->woo_request_id !== $wooRequest->id) {
            abort(404, 'Question does not belong to this case.');
        }

        $this->authorize('update', $wooRequest);

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
    public function download(WooRequest $wooRequest, Document $document)
    {
        // Validate that document belongs to the case
        if ($document->woo_request_id !== $wooRequest->id) {
            abort(404, 'Document does not belong to this case.');
        }

        $this->authorize('view', $wooRequest);

        return Storage::disk('woo-documents')->download(
            $document->file_path,
            $document->file_name
        );
    }
}
