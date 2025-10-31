<?php

use App\Models\Document;
use App\Models\InternalRequest;
use App\Models\Question;
use App\Models\WooRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Generate UUIDs for existing WooRequests
        WooRequest::whereNull('uuid')->chunk(100, function ($wooRequests) {
            foreach ($wooRequests as $wooRequest) {
                $wooRequest->update(['uuid' => (string) Str::uuid()]);
            }
        });

        // Generate UUIDs for existing Documents
        Document::whereNull('uuid')->chunk(100, function ($documents) {
            foreach ($documents as $document) {
                $document->update(['uuid' => (string) Str::uuid()]);
            }
        });

        // Generate UUIDs for existing Questions
        Question::whereNull('uuid')->chunk(100, function ($questions) {
            foreach ($questions as $question) {
                $question->update(['uuid' => (string) Str::uuid()]);
            }
        });

        // Generate UUIDs for existing InternalRequests
        InternalRequest::whereNull('uuid')->chunk(100, function ($internalRequests) {
            foreach ($internalRequests as $internalRequest) {
                $internalRequest->update(['uuid' => (string) Str::uuid()]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set all UUIDs to null (can't delete column in down, that's handled by the column migration)
        WooRequest::whereNotNull('uuid')->update(['uuid' => null]);
        Document::whereNotNull('uuid')->update(['uuid' => null]);
        Question::whereNotNull('uuid')->update(['uuid' => null]);
        InternalRequest::whereNotNull('uuid')->update(['uuid' => null]);
    }
};
