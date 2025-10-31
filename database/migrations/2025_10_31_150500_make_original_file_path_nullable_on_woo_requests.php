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
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite doesn't support MODIFY, but columns are nullable by default
            // So we don't need to do anything for SQLite
            // However, if we need to ensure it's nullable, we can update existing NOT NULL constraints
            // For now, SQLite columns are nullable by default unless explicitly set
            return;
        }
        
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `woo_requests` MODIFY `original_file_path` VARCHAR(255) NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE woo_requests ALTER COLUMN original_file_path DROP NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite doesn't support MODIFY
            return;
        }
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `woo_requests` MODIFY `original_file_path` VARCHAR(255) NOT NULL");
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE woo_requests ALTER COLUMN original_file_path SET NOT NULL');
        }
    }
};


