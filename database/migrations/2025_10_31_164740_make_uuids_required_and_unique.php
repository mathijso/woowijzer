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
        // Make UUIDs required and unique for woo_requests
        Schema::table('woo_requests', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });

        // Make UUIDs required and unique for documents
        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });

        // Make UUIDs required and unique for questions
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });

        // Make UUIDs required and unique for internal_requests
        Schema::table('internal_requests', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to nullable with index (no unique constraint)
        Schema::table('woo_requests', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->uuid('uuid')->nullable()->change();
            $table->index('uuid');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->uuid('uuid')->nullable()->change();
            $table->index('uuid');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->uuid('uuid')->nullable()->change();
            $table->index('uuid');
        });

        Schema::table('internal_requests', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->uuid('uuid')->nullable()->change();
            $table->index('uuid');
        });
    }
};
