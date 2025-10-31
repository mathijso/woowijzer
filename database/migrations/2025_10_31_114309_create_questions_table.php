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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('woo_request_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->integer('order')->default(0);
            $table->enum('status', ['unanswered', 'partially_answered', 'answered'])->default('unanswered');
            $table->longText('ai_summary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
