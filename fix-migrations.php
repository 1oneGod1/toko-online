<?php

/**
 * This script fixes migration issues when some tables already exist.
 * It manually checks for tables and updates the migrations log.
 * Run with: php fix-migrations.php
 */

echo "=================================================================\n";
echo " FIXING DATABASE MIGRATION STATE\n";
echo "=================================================================\n\n";

// Set up Laravel environment
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// --- Configuration ---
$migrationsToFix = [
    '2023_06_30_000000_create_reviews_table' => 'reviews',
    '2023_06_30_000001_create_discussions_table' => 'discussions',
];

try {
    // Ensure migrations table exists
    if (!Schema::hasTable('migrations')) {
        echo "Running: php artisan migrate:install\n";
        Artisan::call('migrate:install');
        echo "✅ 'migrations' table created.\n\n";
    }

    // 1. Check and create missing tables
    echo "Step 1: Checking for missing tables...\n";

    // Create 'discussions' table if it doesn't exist
    if (!Schema::hasTable('discussions')) {
        echo "   - Table 'discussions' not found. Creating it...\n";
        Schema::create('discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('discussions')->onDelete('cascade');
            $table->text('message');
            $table->timestamps();
        });
        echo "   ✅ Table 'discussions' created successfully.\n";
    } else {
        echo "   - Table 'discussions' already exists. Skipping creation.\n";
    }

    // Verify 'reviews' table exists
    if (Schema::hasTable('reviews')) {
        echo "   - Table 'reviews' already exists. Skipping creation.\n";
    } else {
        echo "   - WARNING: Table 'reviews' not found. Please run `php artisan migrate` again.\n";
    }

    // 2. Synchronize the migrations table
    echo "\nStep 2: Synchronizing 'migrations' table...\n";

    // Get the last batch number
    $lastBatch = DB::table('migrations')->max('batch');
    $nextBatch = $lastBatch ? $lastBatch + 1 : 1;

    foreach ($migrationsToFix as $migrationName => $tableName) {
        $migrationExists = DB::table('migrations')->where('migration', $migrationName)->exists();

        if (!$migrationExists) {
            echo "   - Recording migration '$migrationName'...\n";
            DB::table('migrations')->insert([
                'migration' => $migrationName,
                'batch' => $nextBatch,
            ]);
            echo "   ✅ Recorded successfully.\n";
        } else {
            echo "   - Migration '$migrationName' is already recorded. Skipping.\n";
        }
    }

    echo "\n=================================================================\n";
    echo " MIGRATION FIX COMPLETED\n";
    echo "=================================================================\n\n";
    echo "✅ Database state has been synchronized.\n";
    echo "Both 'reviews' and 'discussions' tables should now exist and be correctly logged.\n\n";
    echo "Please clear your cache and restart the server:\n";
    echo "1. php artisan optimize:clear\n";
    echo "2. php artisan serve\n";

} catch (\Exception $e) {
    echo "\n❌ An error occurred: " . $e->getMessage() . "\n";
    echo "Please check your database connection and permissions.\n";
}