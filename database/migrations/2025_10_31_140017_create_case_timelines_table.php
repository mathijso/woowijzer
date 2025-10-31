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
        Schema::create('case_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('woo_request_id')->constrained('woo_requests')->onDelete('cascade');
            $table->json('timeline_json');
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
        Schema::dropIfExists('case_timelines');
    }
};
