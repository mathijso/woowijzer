<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessUploadedDocument;
use App\Models\Document;
use App\Models\InternalRequest;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadPortalController extends Controller
{
    /**
     * Show upload form for the given token
     */
    public function show($token): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $internalRequest = InternalRequest::where('upload_token', $token)
            ->with('wooRequest', 'caseManager')
            ->firstOrFail();

        if ($internalRequest->isExpired()) {
            return view('upload-portal.expired', ['internalRequest' => $internalRequest]);
        }

        return view('upload-portal.show', ['internalRequest' => $internalRequest]);
    }

    /**
     * Store uploaded documents
     */
    public function store(Request $request, $token)
    {
        $internalRequest = InternalRequest::where('upload_token', $token)->firstOrFail();

        if ($internalRequest->isExpired()) {
            return back()->with('error', 'Deze upload link is verlopen.');
        }

        $validated = $request->validate([
            'submitted_by_name' => 'nullable|string|max:255',
            'submission_notes' => 'nullable|string',
            'documents' => 'required|array|min:1',
            'documents.*' => 'required|file|max:' . (config('woo.max_upload_size_mb', 50) * 1024),
        ]);

        // Create submission
        $submission = Submission::create([
            'internal_request_id' => $internalRequest->id,
            'submitted_by_email' => $internalRequest->colleague_email,
            'submitted_by_name' => $validated['submitted_by_name'] ?? null,
            'submission_notes' => $validated['submission_notes'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'documents_count' => 0, // Will be updated by document observer
        ]);

        // Store each uploaded document
        foreach ($request->file('documents') as $file) {
            $filePath = $file->store('submissions/' . $submission->id, 'woo-documents');

            $document = Document::create([
                'woo_request_id' => $internalRequest->woo_request_id,
                'submission_id' => $submission->id,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);

            // Dispatch job to process document
            ProcessUploadedDocument::dispatch($document);
        }

        return redirect()
            ->route('upload.show', $token)
            ->with('success', 'Uw documenten zijn succesvol geüpload en worden verwerkt. Bedankt voor uw bijdrage!');
    }
}
