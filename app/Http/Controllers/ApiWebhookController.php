<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\WooRequest;
use App\Services\QuestionExtractionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiWebhookController extends Controller
{
    /**
     * Receive processing results from API
     */
    public function receiveProcessing(Request $request, QuestionExtractionService $questionService)
    {
        // Validate webhook signature/auth here if needed
        $apiKey = $request->header('X-API-Key');
        if ($apiKey !== config('woo.document_processing.api_key')) {
            abort(401, 'Unauthorized');
        }

        $validated = $request->validate([
            'type' => 'required|in:woo_request,uploaded_document',
            'id' => 'required|integer',
            'status' => 'required|in:success,failed',
            'content_markdown' => 'nullable|string',
            'questions' => 'nullable|array',
            'summary' => 'nullable|string',
            'error' => 'nullable|string',
        ]);

        try {
            if ($validated['type'] === 'woo_request') {
                $this->handleWooRequestProcessing($validated, $questionService);
            } elseif ($validated['type'] === 'uploaded_document') {
                $this->handleDocumentProcessing($validated);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'data' => $validated,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle WOO request processing webhook
     */
    protected function handleWooRequestProcessing(array $data, QuestionExtractionService $questionService): void
    {
        $wooRequest = WooRequest::findOrFail($data['id']);

        if ($data['status'] === 'success') {
            $wooRequest->update([
                'original_file_content_markdown' => $data['content_markdown'] ?? null,
            ]);

            // Extract questions if provided
            if (!empty($data['questions'])) {
                $questionService->extractQuestionsFromApiResponse($wooRequest, $data);
            }

            Log::info('WOO request processed via webhook', [
                'woo_request_id' => $wooRequest->id,
                'questions_count' => count($data['questions'] ?? []),
            ]);
        } else {
            Log::error('WOO request processing failed', [
                'woo_request_id' => $wooRequest->id,
                'error' => $data['error'] ?? 'Unknown error',
            ]);
        }
    }

    /**
     * Handle document processing webhook
     */
    protected function handleDocumentProcessing(array $data): void
    {
        $document = Document::findOrFail($data['id']);

        if ($data['status'] === 'success') {
            $document->update([
                'content_markdown' => $data['content_markdown'] ?? null,
                'ai_summary' => $data['summary'] ?? null,
                'processed_at' => now(),
            ]);

            Log::info('Document processed via webhook', [
                'document_id' => $document->id,
            ]);
        } else {
            Log::error('Document processing failed', [
                'document_id' => $document->id,
                'error' => $data['error'] ?? 'Unknown error',
            ]);
        }
    }
}
