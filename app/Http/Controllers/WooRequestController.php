<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateQuestionSummaries;
use App\Jobs\ProcessWooRequestDocument;
use App\Models\User;
use App\Models\WooRequest;
use App\Services\DocumentLinkingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WooRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $user = Auth::user();

        $query = WooRequest::with(['user', 'caseManager', 'questions']);

        if ($user->isBurger()) {
            // Burgers only see their own requests
            $query->where('user_id', $user->id);
        } elseif ($user->isCaseManager()) {
            // Case managers see all or their assigned requests
            if ($request->has('my_cases')) {
                $query->where('case_manager_id', $user->id);
            }
        }

        $wooRequests = $query->latest()->paginate(20);

        return view('woo-requests.index', ['wooRequests' => $wooRequests]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('woo-requests.create');
    }

    /**
     * Show the form for creating a manual case (without document upload).
     */
    public function createManual(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('woo-requests.create-manual');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'required|file|mimes:pdf|max:' . (config('woo.max_upload_size_mb', 50) * 1024),
            'questions' => 'nullable|array',
            'questions.*' => 'nullable|string|max:1000',
        ]);

        // Store the uploaded file
        $filePath = $request->file('document')->store('woo-requests', 'woo-documents');

        // Create WOO request
        $wooRequest = WooRequest::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'original_file_path' => $filePath,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Create questions if provided (from extraction)
        if (! empty($validated['questions'])) {
            $order = 1;
            foreach ($validated['questions'] as $questionText) {
                if (! empty(trim($questionText))) {
                    $wooRequest->questions()->create([
                        'question_text' => trim($questionText),
                        'order' => $order++,
                        'status' => 'unanswered',
                    ]);
                }
            }
        }

        // Dispatch job to process document (this will extract questions if none were provided)
        if ($wooRequest->questions()->count() === 0) {
            ProcessWooRequestDocument::dispatch($wooRequest);
        }

        return redirect()
            ->route('woo-requests.show', $wooRequest)
            ->with('success', 'Uw WOO-verzoek is succesvol ingediend en wordt verwerkt.');
    }

    /**
     * Store a manually created case (without document upload).
     */
    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'nullable|array',
            'questions.*' => 'nullable|string|max:1000',
        ]);

        // Create WOO request without document
        $wooRequest = WooRequest::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'original_file_path' => null,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Create questions if provided
        if (! empty($validated['questions'])) {
            $order = 1;
            foreach ($validated['questions'] as $questionText) {
                if (! empty(trim($questionText))) {
                    $wooRequest->questions()->create([
                        'question_text' => trim($questionText),
                        'order' => $order++,
                        'status' => 'unanswered',
                    ]);
                }
            }
        }

        return redirect()
            ->route('woo-requests.show', $wooRequest)
            ->with('success', 'Uw WOO-verzoek is succesvol aangemaakt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WooRequest $wooRequest): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $this->authorize('view', $wooRequest);

        $wooRequest->load([
            'user',
            'caseManager',
            'questions.documents',
            'documents.submission.internalRequest',
            'internalRequests.submissions.documents',
            'caseTimeline',
            'caseDecision',
        ]);

        // Calculate progress (used by case managers)
        $totalQuestions = $wooRequest->questions()->count();
        /** @phpstan-ignore-next-line */
        $answeredQuestions = $wooRequest->questions()->answered()->count();
        $progressPercentage = $totalQuestions > 0
            ? round(($answeredQuestions / $totalQuestions) * 100, 2)
            : 0;

        // Get question status breakdown
        $questionStats = [
            /** @phpstan-ignore-next-line */
            'unanswered' => $wooRequest->questions()->unanswered()->count(),
            /** @phpstan-ignore-next-line */
            'partially_answered' => $wooRequest->questions()->partiallyAnswered()->count(),
            'answered' => $answeredQuestions,
        ];

        // Get all case managers for assignment dropdown (if user is case manager)
        $caseManagers = Auth::user()->isCaseManager()
            ? User::caseManagers()->orderBy('name')->get()
            : collect();

        return view('woo-requests.show', [
            'wooRequest' => $wooRequest,
            'progressPercentage' => $progressPercentage,
            'questionStats' => $questionStats,
            'caseManagers' => $caseManagers,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WooRequest $wooRequest): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $this->authorize('update', $wooRequest);

        /** @phpstan-ignore-next-line */
        return view('woo-requests.edit', ['wooRequest' => $wooRequest]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WooRequest $wooRequest)
    {
        $this->authorize('update', $wooRequest);

        $validated = $request->validate([
            'status' => 'sometimes|in:submitted,in_review,in_progress,completed,rejected',
            'case_manager_id' => 'sometimes|nullable|exists:users,id',
        ]);

        $wooRequest->update($validated);

        if ($wooRequest->status === 'completed') {
            $wooRequest->update(['completed_at' => now()]);
        }

        return redirect()
            ->route('woo-requests.show', $wooRequest)
            ->with('success', 'WOO-verzoek is bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WooRequest $wooRequest)
    {
        $this->authorize('delete', $wooRequest);

        // Delete associated file
        Storage::disk('woo-documents')->delete($wooRequest->original_file_path);

        $wooRequest->delete();

        return redirect()
            ->route('woo-requests.index')
            ->with('success', 'WOO-verzoek is verwijderd.');
    }

    /**
     * Assign case manager to WOO request
     */
    public function assignCaseManager(Request $request, WooRequest $wooRequest)
    {
        $this->authorize('update', $wooRequest);

        $validated = $request->validate([
            'case_manager_id' => 'nullable|exists:users,id',
        ]);

        // If case_manager_id is provided, verify it's a case manager
        if ($validated['case_manager_id']) {
            $caseManager = User::caseManagers()->findOrFail($validated['case_manager_id']);
            $wooRequest->update(['case_manager_id' => $caseManager->id]);
            $message = sprintf('Case is toegewezen aan %s.', $caseManager->name);
        } else {
            $wooRequest->update(['case_manager_id' => null]);
            $message = 'Case is niet meer toegewezen.';
        }

        return redirect()
            ->route('woo-requests.show', $wooRequest)
            ->with('success', $message);
    }

    /**
     * Pick up a case (assign to current case manager)
     */
    public function pickupCase(WooRequest $wooRequest)
    {
        $user = Auth::user();

        if (! $user->isCaseManager()) {
            abort(403, 'Alleen case managers kunnen cases oppakken.');
        }

        $this->authorize('update', $wooRequest);

        $wooRequest->update(['case_manager_id' => $user->id]);

        return redirect()
            ->route('woo-requests.show', $wooRequest)
            ->with('success', 'Case is toegewezen aan u.');
    }

    /**
     * Update the status of a WooRequest (case manager only)
     */
    public function updateStatus(Request $request, WooRequest $wooRequest)
    {
        $this->authorize('update', $wooRequest);

        $validated = $request->validate([
            'status' => 'required|in:submitted,in_review,in_progress,completed,rejected',
        ]);

        $oldStatus = $wooRequest->status;
        $wooRequest->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === 'completed' ? now() : null,
        ]);

        return back()->with('success', sprintf(
            'Status is gewijzigd van "%s" naar "%s".',
            config('woo.woo_request_statuses')[$oldStatus] ?? $oldStatus,
            config('woo.woo_request_statuses')[$validated['status']] ?? $validated['status']
        ));
    }

    /**
     * Auto-link all documents for a case (case manager only)
     */
    public function autoLinkDocuments(WooRequest $wooRequest, DocumentLinkingService $linkingService)
    {
        $this->authorize('update', $wooRequest);

        $stats = $linkingService->autoLinkAllDocuments($wooRequest);

        return back()->with('success', sprintf(
            '%d documenten automatisch gekoppeld (%d koppelingen gemaakt).',
            $stats['total_documents'],
            $stats['total_links']
        ));
    }

    /**
     * Generate summaries for all questions (case manager only)
     */
    public function generateSummaries(WooRequest $wooRequest)
    {
        $this->authorize('update', $wooRequest);

        GenerateQuestionSummaries::dispatch($wooRequest);

        return back()->with('success', 'Samenvattingen worden gegenereerd op de achtergrond.');
    }

    /**
     * Generate and download a case report (case manager only)
     */
    public function generateReport(WooRequest $wooRequest)
    {
        $this->authorize('view', $wooRequest);

        $wooRequest->load([
            'user',
            'caseManager',
            'questions.documents',
            'documents.submission.internalRequest',
            'internalRequests.submissions.documents',
        ]);

        // Calculate statistics
        $totalQuestions = $wooRequest->questions()->count();
        $answeredQuestions = $wooRequest->questions()->where('status', 'answered')->count();
        $progressPercentage = $totalQuestions > 0
            ? round(($answeredQuestions / $totalQuestions) * 100, 2)
            : 0;

        $questionStats = [
            'unanswered' => $wooRequest->questions()->where('status', 'unanswered')->count(),
            'partially_answered' => $wooRequest->questions()->where('status', 'partially_answered')->count(),
            'answered' => $answeredQuestions,
        ];

        // Generate HTML report
        $html = view('cases.report', [
            'wooRequest' => $wooRequest,
            'progressPercentage' => $progressPercentage,
            'questionStats' => $questionStats,
        ])->render();

        $filename = sprintf('case-report-%s-%s.html', $wooRequest->id, now()->format('Y-m-d'));

        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, $filename, [
            'Content-Type' => 'text/html; charset=utf-8',
        ]);
    }

    /**
     * Extract case file information from uploaded document (AJAX endpoint)
     */
    public function extractCaseFile(Request $request, DocumentProcessingService $processingService)
    {
        $validated = $request->validate([
            'document' => 'required|file|mimes:pdf,docx,doc,txt,jpg,jpeg,png|max:' . (config('woo.max_upload_size_mb', 50) * 1024),
        ]);

        try {
            // Get the uploaded file
            $file = $request->file('document');
            $tempPath = $file->getRealPath();

            // Generate a temporary case ID for extraction
            $tempCaseId = 'temp-' . \Illuminate\Support\Str::uuid();

            // Call the API to extract case file information
            $extractedData = $processingService->extractCaseFile($tempCaseId, $tempPath);

            return response()->json([
                'success' => true,
                'data' => [
                    'title' => $extractedData['title'] ?? '',
                    'description' => $extractedData['description'] ?? '',
                    'questions' => $extractedData['questions'] ?? [],
                ],
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to extract case file', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Er is een fout opgetreden bij het analyseren van het document. Probeer het opnieuw.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
