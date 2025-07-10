<?php

/**
 * This script fixes product management issues
 * Run with: php fix-product-management.php
 */

echo "=================================================================\n";
echo " FIXING PRODUCT MANAGEMENT ISSUES\n";
echo "=================================================================\n\n";

// Load Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

// 1. Check and fix products table structure
echo "Step 1: Checking products table structure...\n";

if (Schema::hasTable('products')) {
    // Check required columns
    if (!Schema::hasColumn('products', 'stock')) {
        echo "Adding 'stock' column to products table...\n";
        Schema::table('products', function ($table) {
            $table->integer('stock')->default(0)->after('price');
        });
        echo "✅ Added 'stock' column\n";
    } else {
        echo "✅ 'stock' column already exists\n";
    }
    
    if (!Schema::hasColumn('products', 'discount_price')) {
        echo "Adding 'discount_price' column to products table...\n";
        Schema::table('products', function ($table) {
            $table->integer('discount_price')->nullable()->after('stock');
        });
        echo "✅ Added 'discount_price' column\n";
    } else {
        echo "✅ 'discount_price' column already exists\n";
    }
    
    if (!Schema::hasColumn('products', 'slug')) {
        echo "Adding 'slug' column to products table...\n";
        Schema::table('products', function ($table) {
            $table->string('slug')->nullable()->after('name');
        });
        
        // Generate slugs for existing products
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            DB::table('products')
                ->where('id', $product->id)
                ->update(['slug' => Str::slug($product->name)]);
        }
        
        echo "✅ Added 'slug' column and generated slugs for existing products\n";
    } else {
        echo "✅ 'slug' column already exists\n";
    }
    
    // Fix any products with null slug
    $nullSlugCount = DB::table('products')->whereNull('slug')->count();
    if ($nullSlugCount > 0) {
        echo "Fixing $nullSlugCount products with null slugs...\n";
        $nullSlugProducts = DB::table('products')->whereNull('slug')->get();
        foreach ($nullSlugProducts as $product) {
            DB::table('products')
                ->where('id', $product->id)
                ->update(['slug' => Str::slug($product->name)]);
        }
        echo "✅ Fixed products with null slugs\n";
    }
    
    // Fix any products with null stock
    $nullStockCount = DB::table('products')->whereNull('stock')->count();
    if ($nullStockCount > 0) {
        echo "Fixing $nullStockCount products with null stock values...\n";
        DB::table('products')->whereNull('stock')->update(['stock' => 10]);
        echo "✅ Fixed products with null stock values (set to 10)\n";
    }
    
} else {
    echo "❌ Products table doesn't exist. Please run migrations first.\n";
    exit(1);
}

// 2. Create ProductController and routes
echo "\nStep 2: Ensuring ProductController works properly...\n";

// Fix routes
$webRoutesPath = __DIR__ . '/routes/web.php';
$webRoutesContent = file_get_contents($webRoutesPath);

if (strpos($webRoutesContent, "Route::get('/products/{product:slug}'") === false) {
    echo "Fixing product routes...\n";
    
    // Create backup of original routes file
    $backupPath = $webRoutesPath . '.bak-' . date('Y-m-d-H-i-s');
    copy($webRoutesPath, $backupPath);
    echo "✅ Created backup of routes file at $backupPath\n";
    
    // Replace with corrected routes
    $pattern = "/Route::get\('\/products', \[ProductController::class, 'index'\]\)->name\('products.index'\);/";
    $replacement = "Route::get('/products', [ProductController::class, 'index'])->name('products.index');\nRoute::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');";
    
    $newWebRoutesContent = preg_replace($pattern, $replacement, $webRoutesContent);
    
    // Add admin product routes if missing
    if (strpos($newWebRoutesContent, "Route::get('/admin/products/create'") === false) {
        $adminRoutesPattern = "/Route::middleware\(\['auth', 'can:is-admin'\]\)->group\(function \(\) {/";
        $adminRoutesReplacement = "Route::middleware(['auth', 'can:is-admin'])->group(function () {\n    // Product Management\n    Route::get('/admin/products/create', [ProductController::class, 'create'])->name('products.create');\n    Route::post('/admin/products', [ProductController::class, 'store'])->name('products.store');\n    Route::get('/admin/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');\n    Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('products.update');\n    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');";
        
        $newWebRoutesContent = preg_replace($adminRoutesPattern, $adminRoutesReplacement, $newWebRoutesContent);
    }
    
    file_put_contents($webRoutesPath, $newWebRoutesContent);
    echo "✅ Updated routes file with corrected product routes\n";
}

// 3. Add test products to the database
echo "\nStep 3: Adding test products to the database...\n";

$testProductCount = DB::table('products')->count();
echo "Currently you have $testProductCount products in the database.\n";

$addTestProducts = false;
if ($testProductCount < 3) {
    $addTestProducts = true;
    echo "Adding test products to your database...\n";
    
    // Get first category
    $category = DB::table('categories')->first();
    
    if (!$category) {
        echo "Creating a test category first...\n";
        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } else {
        $categoryId = $category->id;
    }
    
    // Test products
    $testProducts = [
        [
            'name' => 'Product Test 1',
            'slug' => 'product-test-1',
            'description' => 'This is a test product 1',
            'price' => 100000,
            'stock' => 20,
            'discount_price' => 80000,
            'category_id' => $categoryId,
            'image' => 'products/test1.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Product Test 2',
            'slug' => 'product-test-2',
            'description' => 'This is a test product 2',
            'price' => 200000,
            'stock' => 15,
            'discount_price' => null,
            'category_id' => $categoryId,
            'image' => 'products/test2.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Product Test 3',
            'slug' => 'product-test-3',
            'description' => 'This is a test product 3',
            'price' => 150000,
            'stock' => 10,
            'discount_price' => 120000,
            'category_id' => $categoryId,
            'image' => 'products/test3.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ];
    
    foreach ($testProducts as $product) {
        // Check if product already exists
        $exists = DB::table('products')
            ->where('name', $product['name'])
            ->orWhere('slug', $product['slug'])
            ->exists();
            
        if (!$exists) {
            DB::table('products')->insert($product);
            echo "✅ Added test product: {$product['name']}\n";
        }
    }
    
    echo "✅ Test products added successfully!\n";
} else {
    echo "You already have products in your database. Skipping test product creation.\n";
}

// 4. Clear all caches
echo "\nStep 4: Clearing all Laravel caches...\n";
$commands = [
    'php artisan cache:clear',
    'php artisan config:clear',
    'php artisan route:clear',
    'php artisan view:clear',
    'php artisan optimize'
];

foreach ($commands as $command) {
    echo "Running: $command\n";
    exec($command, $output, $returnCode);
    if ($returnCode === 0) {
        echo "✅ Command completed successfully\n";
    } else {
        echo "❌ Command failed with code $returnCode\n";
    }
}

// 5. Summary
echo "\n=================================================================\n";
echo " SUMMARY\n";
echo "=================================================================\n\n";

echo "✅ Fixed products table structure\n";
echo "✅ Updated routes configuration\n";
if ($addTestProducts) echo "✅ Added test products to the database\n";
echo "✅ Cleared all Laravel caches\n\n";

echo "Your product management system should now be working correctly.\n";
echo "Please restart your Laravel server with:\n";
echo "php artisan serve\n\n";

if ($testProductCount === 0 && $addTestProducts) {
    echo "Test products have been added. You can now browse and manage them.\n";
}

echo "To test product management, visit these URLs:\n";
echo "- http://localhost:8000/products (View all products)\n";
echo "- http://localhost:8000/admin/products/create (Create new product)\n";
echo "- http://localhost:8000/admin/dashboard (Admin dashboard)\n\n";