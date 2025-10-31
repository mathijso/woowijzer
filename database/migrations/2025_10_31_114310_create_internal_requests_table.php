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
        Schema::create('internal_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('woo_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('case_manager_id')->constrained('users')->onDelete('cascade');
            $table->string('colleague_email');
            $table->string('colleague_name')->nullable();
            $table->text('description');
            $table->string('upload_token')->unique();
            $table->timestamp('token_expires_at');
            $table->enum('status', ['pending', 'submitted', 'completed', 'expired'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_requests');
    }
};
