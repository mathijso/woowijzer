<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to avoid requiring doctrine/dbal for change()
        DB::statement('ALTER TABLE `woo_requests` MODIFY `original_file_path` VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to NOT NULL with empty string default to prevent failures
        DB::statement("ALTER TABLE `woo_requests` MODIFY `original_file_path` VARCHAR(255) NOT NULL");
    }
};


