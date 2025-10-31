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
        Schema::table('woo_requests', function (Blueprint $table) {
            $table->string('extracted_title')->nullable()->after('description');
            $table->text('extracted_description')->nullable()->after('extracted_title');
            $table->json('extracted_questions')->nullable()->after('extracted_description');
            $table->timestamp('extracted_at')->nullable()->after('extracted_questions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('woo_requests', function (Blueprint $table) {
            $table->dropColumn(['extracted_title', 'extracted_description', 'extracted_questions', 'extracted_at']);
        });
    }
};
