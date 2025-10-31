<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateQuestionSummaries;
use App\Models\WooRequest;
use App\Services\DocumentLinkingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseOverviewController extends Controller
{
    /**
     * Display case manager dashboard
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $user = Auth::user();

        if (! $user->isCaseManager()) {
            abort(403, 'Alleen case managers hebben toegang tot dit dashboard.');
        }

        $query = WooRequest::with(['user', 'questions', 'internalRequests', 'documents'])
            ->withCount(['questions', 'documents']);

        // Filter options
        if ($request->has('my_cases')) {
            $query->where('case_manager_id', $user->id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('unassigned')) {
            $query->whereNull('case_manager_id');
        }

        $wooRequests = $query->latest()->paginate(20);

        $stats = [
            'total' => WooRequest::count(),
            'unassigned' => WooRequest::whereNull('case_manager_id')->count(),
            'my_cases' => WooRequest::where('case_manager_id', $user->id)->count(),
            'in_progress' => WooRequest::where('status', 'in_progress')->count(),
        ];

        return view('cases.index', ['wooRequests' => $wooRequests, 'stats' => $stats]);
    }

    /**
     * Display detailed case overview
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

        // Calculate progress
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

        return view('cases.show', ['wooRequest' => $wooRequest, 'progressPercentage' => $progressPercentage, 'questionStats' => $questionStats]);
    }

    /**
     * Auto-link all documents for a case
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
     * Generate summaries for all questions
     */
    public function generateSummaries(WooRequest $wooRequest)
    {
        $this->authorize('update', $wooRequest);

        GenerateQuestionSummaries::dispatch($wooRequest);

        return back()->with('success', 'Samenvattingen worden gegenereerd op de achtergrond.');
    }

    /**
     * Update the status of a WooRequest
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
     * Generate and download a case report
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
}
