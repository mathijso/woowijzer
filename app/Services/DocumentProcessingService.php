<?php

namespace App\Services;

use App\Models\Document;
use App\Models\WooRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentProcessingService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected int $timeout;

    public function __construct()
    {
        $this->apiUrl = config('woo.document_processing.api_url');
        $this->apiKey = config('woo.document_processing.api_key');
        $this->timeout = config('woo.document_processing.timeout', 120);
    }

    /**
     * Process a document through the API
     */
    public function processDocument(string $filePath, array $metadata = []): array
    {
        try {
            $fullPath = Storage::disk('woo-documents')->path($filePath);
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->attach('file', file_get_contents($fullPath), basename($filePath))
                ->post($this->apiUrl . '/process', $metadata);

            if ($response->successful()) {
                return $this->parseApiResponse($response->json());
            }

            Log::error('Document processing API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \Exception('Document processing failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Document processing exception', [
                'message' => $e->getMessage(),
                'file' => $filePath,
            ]);
            
            throw $e;
        }
    }

    /**
     * Parse the API response
     */
    public function parseApiResponse(array $response): array
    {
        return [
            'content_markdown' => $response['content_markdown'] ?? null,
            'questions' => $response['questions'] ?? [],
            'summary' => $response['summary'] ?? null,
            'metadata' => $response['metadata'] ?? [],
        ];
    }

    /**
     * Process WOO request document and extract questions
     */
    public function processWooRequestDocument(WooRequest $wooRequest): array
    {
        $result = $this->processDocument($wooRequest->original_file_path, [
            'type' => 'woo_request',
            'woo_request_id' => $wooRequest->id,
        ]);

        // Update WOO request with markdown content
        $wooRequest->update([
            'original_file_content_markdown' => $result['content_markdown'],
        ]);

        return $result;
    }

    /**
     * Process uploaded document
     */
    public function processUploadedDocument(Document $document): array
    {
        $result = $this->processDocument($document->file_path, [
            'type' => 'uploaded_document',
            'document_id' => $document->id,
            'woo_request_id' => $document->woo_request_id,
        ]);

        // Update document with processed content
        $document->update([
            'content_markdown' => $result['content_markdown'],
            'ai_summary' => $result['summary'],
            'processed_at' => now(),
        ]);

        return $result;
    }

    /**
     * Check if API is available
     */
    public function isAvailable(): bool
    {
        try {
            if (empty($this->apiUrl) || empty($this->apiKey)) {
                return false;
            }

            $response = Http::timeout(5)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])
                ->get($this->apiUrl . '/health');

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}

