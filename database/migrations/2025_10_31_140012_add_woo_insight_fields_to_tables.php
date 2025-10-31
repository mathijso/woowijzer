<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add fields to documents table
        Schema::table('documents', function (Blueprint $table) {
            $table->string('external_document_id')->unique()->nullable()->after('id');
            $table->enum('api_processing_status', ['pending', 'processing', 'completed', 'failed'])
                ->default('pending')
                ->after('processed_at');
            $table->text('api_processing_error')->nullable()->after('api_processing_status');
            $table->json('timeline_events_json')->nullable()->after('ai_summary');
            $table->json('processing_metadata_json')->nullable()->after('timeline_events_json');
            
            $table->index('external_document_id');
            $table->index('api_processing_status');
        });

        // Add fields to submissions table
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('submitter_type')->default('government')->after('submission_notes');
            $table->string('woo_insight_submission_id')->nullable()->after('submitter_type');
            
            $table->index('woo_insight_submission_id');
        });

        // Add fields to woo_requests table
        Schema::table('woo_requests', function (Blueprint $table) {
            $table->string('woo_insight_case_id')->nullable()->after('id');
            
            $table->index('woo_insight_case_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['external_document_id']);
            $table->dropIndex(['api_processing_status']);
            $table->dropColumn([
                'external_document_id',
                'api_processing_status',
                'api_processing_error',
                'timeline_events_json',
                'processing_metadata_json',
            ]);
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropIndex(['woo_insight_submission_id']);
            $table->dropColumn([
                'submitter_type',
                'woo_insight_submission_id',
            ]);
        });

        Schema::table('woo_requests', function (Blueprint $table) {
            $table->dropIndex(['woo_insight_case_id']);
            $table->dropColumn('woo_insight_case_id');
        });
    }
};
