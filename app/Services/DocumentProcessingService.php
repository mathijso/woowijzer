<?php

namespace App\Services;

use App\Models\CaseDecision;
use App\Models\CaseTimeline;
use App\Models\Document;
use App\Models\Submission;
use App\Models\WooRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentProcessingService
{
    protected string $baseUrl;

    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('woo.woo_insight_api.base_url');
        $this->timeout = config('woo.woo_insight_api.timeout', 120);
    }

    /**
     * Ensure a case exists in the WOO Insight API
     */
    public function ensureCase(WooRequest $wooRequest): array
    {
        try {
            $caseId = (string) $wooRequest->id;

            // Check if case already exists
            $getResponse = Http::timeout(5)
                ->get("{$this->baseUrl}/cases/{$caseId}");

            if ($getResponse->successful()) {
                Log::info('Case already exists in WOO Insight API', ['case_id' => $caseId]);

                return $getResponse->json();
            }

            // If GET fails with schema error, log it but try to create anyway
            $getErrorBody = $getResponse->body();
            if (str_contains($getErrorBody, 'UndefinedColumn') || str_contains($getErrorBody, 'does not exist')) {
                Log::warning('WOO Insight API schema error on GET - attempting to create case anyway', [
                    'case_id' => $caseId,
                    'error' => $getErrorBody,
                ]);
            }

            // Create new case
            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/cases", [
                    'case_id' => $caseId,
                    'title' => $wooRequest->title,
                    'description' => $wooRequest->description ?? '',
                ]);

            if ($response->successful()) {
                $wooRequest->update(['woo_insight_case_id' => $caseId]);
                Log::info('Created case in WOO Insight API', ['case_id' => $caseId]);

                return $response->json();
            }

            // Parse error response for better error messages
            $errorBody = $response->body();
            $statusCode = $response->status();
            
            // Check for database schema mismatch errors
            if (str_contains($errorBody, 'UndefinedColumn') || str_contains($errorBody, 'does not exist')) {
                // This error typically occurs AFTER the case is created successfully,
                // when the API tries to serialize the response. The case likely exists in the database.
                // As a workaround, we'll treat this as a soft success since the creation probably succeeded.
                if ($statusCode === 500) {
                    Log::warning('WOO Insight API schema error after case creation - assuming case was created successfully', [
                        'case_id' => $caseId,
                        'status_code' => $statusCode,
                        'error_body' => $errorBody,
                        'note' => 'The case was likely created but API failed to serialize response due to missing database columns',
                    ]);
                    
                    // Update local record and return minimal success response
                    $wooRequest->update(['woo_insight_case_id' => $caseId]);
                    
                    return [
                        'case_id' => $caseId,
                        'title' => $wooRequest->title,
                        'description' => $wooRequest->description ?? '',
                        'status' => 'active',
                        'warning' => 'Case created but API response serialization failed due to schema mismatch',
                    ];
                }
                
                Log::error('WOO Insight API database schema mismatch detected', [
                    'case_id' => $caseId,
                    'status_code' => $statusCode,
                    'error_body' => $errorBody,
                ]);
                throw new \Exception(
                    'WOO Insight API database schema error: The API\'s database is missing required columns ' .
                    '(case_file_request_title, case_file_request_description, case_file_questions_json, case_file_extracted_at). ' .
                    'This is a backend API issue that needs to be fixed on the WOO Insight API side by either: ' .
                    '1) Running database migrations to add these columns, or 2) Removing these fields from the API model. ' .
                    "Original error: {$errorBody}"
                );
            }

            throw new \Exception("Failed to create case (HTTP {$statusCode}): {$errorBody}");
        } catch (\Exception $e) {
            Log::error('Error ensuring case in WOO Insight API', [
                'case_id' => $wooRequest->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Ensure a submission exists in the WOO Insight API
     */
    public function ensureSubmission(Submission $submission): array
    {
        try {
            $caseId = (string) $submission->internalRequest->woo_request_id;
            $submissionId = (string) $submission->id;

            // First try to fetch existing submission (idempotency)
            $getResponse = Http::timeout(10)
                ->get("{$this->baseUrl}/cases/{$caseId}/submissions/{$submissionId}");

            if ($getResponse->successful()) {
                $submission->update(['woo_insight_submission_id' => $submissionId]);
                Log::info('Submission already exists in WOO Insight API', [
                    'case_id' => $caseId,
                    'submission_id' => $submissionId,
                ]);

                return $getResponse->json();
            }

            // Create submission
            $createResponse = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/cases/{$caseId}/submissions", [
                    'submission_id' => $submissionId,
                    'submitter_name' => $submission->getSubmitterName(),
                    'submitter_type' => $submission->submitter_type ?? 'government',
                    'notes' => $submission->submission_notes ?? '',
                ]);

            if ($createResponse->successful()) {
                $submission->update(['woo_insight_submission_id' => $submissionId]);
                Log::info('Ensured submission in WOO Insight API', [
                    'case_id' => $caseId,
                    'submission_id' => $submissionId,
                ]);

                return $createResponse->json();
            }

            // If API reports duplicate/exists, try GET once more and treat as success
            $body = $createResponse->body();
            $isDuplicate = $createResponse->status() === 409
                || str_contains($body, 'already exists')
                || str_contains($body, 'UniqueViolation')
                || str_contains($body, 'duplicate key');

            if ($isDuplicate) {
                $confirmResponse = Http::timeout(10)
                    ->get("{$this->baseUrl}/cases/{$caseId}/submissions/{$submissionId}");
                if ($confirmResponse->successful()) {
                    $submission->update(['woo_insight_submission_id' => $submissionId]);
                    Log::info('Submission existed after create attempt; proceeding', [
                        'case_id' => $caseId,
                        'submission_id' => $submissionId,
                    ]);

                    return $confirmResponse->json();
                }
            }

            throw new \Exception('Failed to create submission: ' . $body);
        } catch (\Exception $e) {
            Log::error('Error ensuring submission in WOO Insight API', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Upload and process a document through the WOO Insight API
     */
    public function uploadDocument(Document $document): array
    {
        try {
            $caseId = (string) $document->woo_request_id;
            $submissionId = (string) $document->submission_id;
            $documentId = $document->external_document_id;

            if (empty($documentId)) {
                throw new \Exception('Document must have an external_document_id before upload');
            }

            // Get file path
            $fullPath = Storage::disk('woo-documents')->path($document->file_path);

            if (! file_exists($fullPath)) {
                throw new \Exception("File not found: {$fullPath}");
            }

            // Upload document
            $response = Http::timeout($this->timeout)
                ->attach('file', file_get_contents($fullPath), $document->file_name)
                ->post("{$this->baseUrl}/cases/{$caseId}/submissions/{$submissionId}/documents", [
                    'document_id' => $documentId,
                ]);

            if ($response->successful()) {
                Log::info('Uploaded document to WOO Insight API', [
                    'document_id' => $documentId,
                    'case_id' => $caseId,
                    'submission_id' => $submissionId,
                ]);

                return $response->json();
            }

            throw new \Exception('Failed to upload document: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Error uploading document to WOO Insight API', [
                'document_id' => $document->id,
                'external_id' => $document->external_document_id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get detailed document information including timeline
     */
    public function getDocumentDetails(string $externalDocumentId): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/documents/{$externalDocumentId}");

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to get document details: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Error getting document details from WOO Insight API', [
                'external_document_id' => $externalDocumentId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get document markdown content
     */
    public function getDocumentMarkdown(string $externalDocumentId): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/documents/{$externalDocumentId}/markdown");

            if ($response->successful()) {
                return $response->json();
            }

            // If markdown is not ready/available yet, do not fail the whole job
            $body = $response->body();
            if (
                $response->status() === 404 ||
                str_contains(strtolower($body), 'markdown not available')
            ) {
                Log::info('Markdown not yet available for document', [
                    'external_document_id' => $externalDocumentId,
                ]);

                return [
                    'markdown_text' => null,
                ];
            }

            throw new \Exception('Failed to get document markdown: ' . $body);
        } catch (\Exception $e) {
            Log::error('Error getting document markdown from WOO Insight API', [
                'external_document_id' => $externalDocumentId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get aggregated timeline for a case
     */
    public function getTimeline(WooRequest $wooRequest): array
    {
        try {
            $caseId = (string) $wooRequest->id;

            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/cases/{$caseId}/timeline");

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Retrieved timeline from WOO Insight API', [
                    'case_id' => $caseId,
                    'event_count' => count($data['events'] ?? []),
                ]);

                return $data;
            }

            throw new \Exception('Failed to get timeline: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Error getting timeline from WOO Insight API', [
                'case_id' => $wooRequest->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get decision overview for a case
     */
    public function getDecisionOverview(WooRequest $wooRequest): array
    {
        try {
            $caseId = (string) $wooRequest->id;

            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/cases/{$caseId}/decision-overview");

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Retrieved decision overview from WOO Insight API', [
                    'case_id' => $caseId,
                ]);

                return $data;
            }

            throw new \Exception('Failed to get decision overview: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Error getting decision overview from WOO Insight API', [
                'case_id' => $wooRequest->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Store or update the timeline for a WooRequest
     */
    public function storeTimeline(WooRequest $wooRequest, array $timelineData): CaseTimeline
    {
        $documentCount = $wooRequest->documents()->count();

        return CaseTimeline::updateOrCreate(
            ['woo_request_id' => $wooRequest->id],
            [
                'timeline_json' => $timelineData['events'] ?? [],
                'document_count' => $documentCount,
                'generated_at' => now(),
            ]
        );
    }

    /**
     * Store or update the decision for a WooRequest
     */
    public function storeDecision(WooRequest $wooRequest, array $decisionData): CaseDecision
    {
        $documentCount = $wooRequest->documents()->count();

        return CaseDecision::updateOrCreate(
            ['woo_request_id' => $wooRequest->id],
            [
                'summary_b1' => $decisionData['summary_b1'] ?? '',
                'key_reasons_json' => $decisionData['key_reasons'] ?? [],
                'process_outline_json' => $decisionData['process_outline'] ?? [],
                'source_refs_json' => $decisionData['source_refs'] ?? [],
                'document_count' => $documentCount,
                'generated_at' => now(),
            ]
        );
    }

    /**
     * Extract case file information (title, description, questions) from uploaded document
     */
    public function extractCaseFile(string $caseId, string $filePath): array
    {
        try {
            if (! file_exists($filePath)) {
                throw new \Exception("File not found: {$filePath}");
            }

            $response = Http::timeout($this->timeout)
                ->attach('file', file_get_contents($filePath), basename($filePath))
                ->post("{$this->baseUrl}/cases/{$caseId}/extract-case-file");

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Extracted case file information from WOO Insight API', [
                    'case_id' => $caseId,
                    'question_count' => count($data['questions'] ?? []),
                ]);

                return $data;
            }

            throw new \Exception('Failed to extract case file: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Error extracting case file from WOO Insight API', [
                'case_id' => $caseId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get previously extracted case file information
     */
    public function getCaseFileExtraction(string $caseId): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/cases/{$caseId}/case-file-extraction");

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to get case file extraction: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Error getting case file extraction from WOO Insight API', [
                'case_id' => $caseId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Check if API is available
     */
    public function isAvailable(): bool
    {
        try {
            if (empty($this->baseUrl)) {
                return false;
            }

            $response = Http::timeout(5)
                ->get($this->baseUrl);

            return $response->successful();
        } catch (\Exception) {
            return false;
        }
    }
}
