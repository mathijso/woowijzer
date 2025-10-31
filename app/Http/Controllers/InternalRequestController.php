<?php

namespace App\Http\Controllers;

use App\Models\InternalRequest;
use App\Models\WooRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternalRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $wooRequestId = $request->query('woo_request_id');

        $query = InternalRequest::with(['wooRequest', 'caseManager', 'submissions']);

        if ($wooRequestId) {
            $query->where('woo_request_id', $wooRequestId);
        } else {
            $query->where('case_manager_id', Auth::id());
        }

        $internalRequests = $query->latest()->paginate(20);

        return view('internal-requests.index', ['internalRequests' => $internalRequests]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $wooRequestId = $request->query('woo_request_id');
        $wooRequest = null;

        if ($wooRequestId) {
            $wooRequest = WooRequest::findOrFail($wooRequestId);
            $this->authorize('update', $wooRequest);
        }

        return view('internal-requests.create', ['wooRequest' => $wooRequest]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'woo_request_id' => 'required|exists:woo_requests,id',
            'colleague_email' => 'required|email',
            'colleague_name' => 'nullable|string|max:255',
            'description' => 'required|string',
        ]);

        $wooRequest = WooRequest::findOrFail($validated['woo_request_id']);
        $this->authorize('update', $wooRequest);

        $internalRequest = InternalRequest::create([
            'woo_request_id' => $validated['woo_request_id'],
            'case_manager_id' => Auth::id(),
            'colleague_email' => $validated['colleague_email'],
            'colleague_name' => $validated['colleague_name'] ?? null,
            'description' => $validated['description'],
            'sent_at' => now(),
        ]);

        // TODO: Send email notification
        // Mail::to($internalRequest->colleague_email)->send(new InternalRequestSent($internalRequest));

        return redirect()
            ->route('woo-requests.show', $wooRequest)
            ->with('success', 'Upload verzoek is verstuurd naar ' . $internalRequest->colleague_email);
    }

    /**
     * Display the specified resource.
     */
    public function show(InternalRequest $internalRequest): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $this->authorize('view', $internalRequest);

        $internalRequest->load([
            'wooRequest',
            'caseManager',
            'submissions.documents',
        ]);

        return view('internal-requests.show', ['internalRequest' => $internalRequest]);
    }

    /**
     * Resend internal request email
     */
    public function resend(InternalRequest $internalRequest)
    {
        $this->authorize('update', $internalRequest);

        if ($internalRequest->isExpired()) {
            return back()->with('error', 'Kan geen reminder sturen voor een verlopen verzoek.');
        }

        // TODO: Send email notification
        // Mail::to($internalRequest->colleague_email)->send(new InternalRequestSent($internalRequest));

        return back()->with('success', 'Reminder email is verstuurd.');
    }

    /**
     * Manually expire a token
     */
    public function expire(InternalRequest $internalRequest)
    {
        $this->authorize('update', $internalRequest);

        $internalRequest->update([
            'status' => 'expired',
            'closed_at' => now(),
        ]);

        return back()->with('success', 'Upload verzoek is handmatig gesloten.');
    }

    /**
     * Mark as completed
     */
    public function complete(InternalRequest $internalRequest)
    {
        $this->authorize('update', $internalRequest);

        $internalRequest->update([
            'status' => 'completed',
            'closed_at' => now(),
        ]);

        return back()->with('success', 'Upload verzoek is afgerond.');
    }
}
