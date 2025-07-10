<?php

/**
 * This script adds missing columns to the products table
 * Run with: php update-schema.php
 */

// Load the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Updating database schema...\n";

try {
    // Check if stock column exists in products table
    if (!Schema::hasColumn('products', 'stock')) {
        echo "Adding 'stock' column to products table...\n";
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock')->default(0)->after('price');
        });
        echo "✅ 'stock' column added successfully.\n";
    } else {
        echo "✅ 'stock' column already exists.\n";
    }
    
    // Check if discount_price column exists in products table
    if (!Schema::hasColumn('products', 'discount_price')) {
        echo "Adding 'discount_price' column to products table...\n";
        Schema::table('products', function (Blueprint $table) {
            $table->integer('discount_price')->nullable()->after('stock');
        });
        echo "✅ 'discount_price' column added successfully.\n";
    } else {
        echo "✅ 'discount_price' column already exists.\n";
    }
    
    // Update all products to have some stock
    DB::table('products')->update(['stock' => 10]);
    echo "✅ Updated all products to have default stock of 10.\n";
    
    // Create migrations table if it doesn't exist
    if (!Schema::hasTable('migrations')) {
        echo "Creating 'migrations' table...\n";
        Schema::create('migrations', function (Blueprint $table) {
            $table->id();
            $table->string('migration');
            $table->integer('batch');
        });
        echo "✅ 'migrations' table created successfully.\n";
    }
    
    // Create settings table if it doesn't exist
    if (!Schema::hasTable('settings')) {
        echo "Creating 'settings' table...\n";
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('settings')->insert([
            ['key' => 'hero_title', 'value' => 'Wujudkan Imajinasimu', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_subtitle', 'value' => 'Temukan produk berkualitas tinggi dengan harga terbaik.', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        echo "✅ 'settings' table created and populated with default settings.\n";
    }
    
    echo "\nSchema update completed successfully!\n";
    echo "You can now run 'php artisan serve' to start your application.\n";
    
} catch (Exception $e) {
    echo "❌ An error occurred: " . $e->getMessage() . "\n";
}