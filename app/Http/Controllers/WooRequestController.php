<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessWooRequestDocument;
use App\Models\WooRequest;
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

        // Dispatch job to process document
        ProcessWooRequestDocument::dispatch($wooRequest);

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
            'internalRequests.submissions',
            'caseTimeline',
            'caseDecision',
        ]);

        return view('woo-requests.show', ['wooRequest' => $wooRequest]);
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
            'case_manager_id' => 'required|exists:users,id',
        ]);

        $wooRequest->update($validated);

        return redirect()
            ->route('woo-requests.show', $wooRequest)
            ->with('success', 'Case manager is toegewezen.');
    }
}
