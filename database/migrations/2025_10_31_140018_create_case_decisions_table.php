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
        Schema::create('case_decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('woo_request_id')->constrained('woo_requests')->onDelete('cascade');
            $table->text('summary_b1');
            $table->json('key_reasons_json');
            $table->json('process_outline_json');
            $table->json('source_refs_json');
            $table->integer('document_count')->default(0);
            $table->timestamp('generated_at');
            $table->timestamps();
            
            $table->index('woo_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_decisions');
    }
};
