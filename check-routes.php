<?php

/**
 * This script checks and verifies that all necessary routes are defined
 * Run with: php check-routes.php
 */

echo "Checking required routes...\n";

// Load Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Get a request instance
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

// Access the router
$router = app('router');
$routes = $router->getRoutes();

// Required route names
$requiredRoutes = [
    'admin.dashboard',
    'admin.orders.index',
    'admin.orders.updateStatus',
    'admin.users.index',
    'admin.users.edit',
    'admin.users.update',
    'admin.landing.index',
    'admin.landing.update',
    'profile.edit',
    'profile.update'
];

$missingRoutes = [];
foreach ($requiredRoutes as $routeName) {
    if (!$routes->hasNamedRoute($routeName)) {
        $missingRoutes[] = $routeName;
    }
}

if (empty($missingRoutes)) {
    echo "✅ All required routes are defined.\n";
} else {
    echo "❌ Missing routes: " . implode(", ", $missingRoutes) . "\n";
    echo "Would you like to attempt to fix the routes? (y/n): ";
    
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    if (trim($line) == 'y') {
        echo "Fixing routes by updating web.php...\n";
        
        // Read the current routes file
        $routesFile = __DIR__ . '/routes/web.php';
        $routesContent = file_get_contents($routesFile);
        
        // Check if admin routes section exists
        if (strpos($routesContent, '// --- GRUP KHUSUS ADMIN ---') !== false) {
            // Backup the original file
            copy($routesFile, $routesFile . '.bak');
            
            // Add the missing routes
            $routesContent = str_replace(
                '// --- GRUP KHUSUS ADMIN ---',
                "// --- GRUP KHUSUS ADMIN ---
    Route::middleware('can:is-admin')->group(function () {
        // Admin Dashboard
        Route::get('/admin/dashboard', [App\\Http\\Controllers\\Admin\\DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Product Management
        Route::post('/products', [App\\Http\\Controllers\\ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [App\\Http\\Controllers\\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [App\\Http\\Controllers\\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [App\\Http\\Controllers\\ProductController::class, 'destroy'])->name('products.destroy');
        
        // Category Management
        Route::resource('categories', App\\Http\\Controllers\\CategoryController::class)->except(['show']);
        
        // Order Management
        Route::get('/admin/orders', [App\\Http\\Controllers\\Admin\\OrderController::class, 'index'])->name('admin.orders.index');
        Route::put('/admin/orders/{order}/status', [App\\Http\\Controllers\\Admin\\OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        
        // User Management
        Route::get('/admin/users', [App\\Http\\Controllers\\Admin\\UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/{user}/edit', [App\\Http\\Controllers\\Admin\\UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [App\\Http\\Controllers\\Admin\\UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [App\\Http\\Controllers\\Admin\\UserController::class, 'destroy'])->name('admin.users.destroy');
        
        // Landing Page Management
        Route::get('/admin/landing', [App\\Http\\Controllers\\Admin\\LandingPageController::class, 'index'])->name('admin.landing.index');
        Route::put('/admin/landing', [App\\Http\\Controllers\\Admin\\LandingPageController::class, 'update'])->name('admin.landing.update');",
                $routesContent
            );
            
            file_put_contents($routesFile, $routesContent);
            echo "✅ Routes file updated.\n";
        } else {
            echo "❌ Could not locate the admin routes section in the web.php file.\n";
        }
        
        // Add profile routes
        if (strpos($routesContent, 'profile.edit') === false) {
            // Find the orders route to add after it
            $profileRoutes = "\n    // Profile Management
    Route::get('/profile', [App\\Http\\Controllers\\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\\Http\\Controllers\\ProfileController::class, 'update'])->name('profile.update');";
            
            if (strpos($routesContent, 'Route::get(\'/orders\'') !== false) {
                $routesContent = str_replace(
                    "Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');",
                    "Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');" . $profileRoutes,
                    $routesContent
                );
                file_put_contents($routesFile, $routesContent);
                echo "✅ Profile routes added.\n";
            } else {
                echo "❌ Could not locate where to add the profile routes.\n";
            }
        }
    }
}

echo "\nRoute check completed!\n";