<?php

/**
 * This script diagnoses issues with admin access and layout rendering
 * Run with: php diagnose.php
 */

// Load the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

echo "=================================================================\n";
echo " DIAGNOSTICS FOR ADMIN PANEL ISSUES\n";
echo "=================================================================\n\n";

// Check for the role column in users table
echo "Checking users table...\n";
if (Schema::hasTable('users')) {
    if (Schema::hasColumn('users', 'role')) {
        echo "✅ 'role' column exists in users table.\n";
        
        // Check if there's any admin user
        $adminCount = DB::table('users')->where('role', 'admin')->count();
        echo "   Found $adminCount admin users.\n";
        
        if ($adminCount > 0) {
            $adminUsers = DB::table('users')->where('role', 'admin')->get(['id', 'name', 'email']);
            echo "   Admin users:\n";
            foreach ($adminUsers as $admin) {
                echo "   - ID: {$admin->id}, Name: {$admin->name}, Email: {$admin->email}\n";
            }
        } else {
            echo "❌ No admin users found. You should run add-role-to-users.php\n";
        }
    } else {
        echo "❌ 'role' column does not exist in users table. Run add-role-to-users.php\n";
    }
} else {
    echo "❌ 'users' table does not exist.\n";
}

// Check the view files
echo "\nChecking view files...\n";

$viewsToCheck = [
    'components/layouts/admin.blade.php',
    'components/layouts/admin-dropdown.blade.php',
    'admin/dashboard.blade.php',
    'admin/orders/index.blade.php',
    'admin/users/index.blade.php',
    'admin/landing/index.blade.php',
];

foreach ($viewsToCheck as $view) {
    $viewPath = resource_path("views/$view");
    if (File::exists($viewPath)) {
        echo "✅ View exists: $view\n";
        
        // Check if layout is correctly used
        if (strpos($view, 'admin/') === 0) {
            $content = File::get($viewPath);
            if (strpos($content, '<x-layouts.admin') !== false) {
                echo "   ✅ Uses admin layout correctly\n";
            } else {
                echo "   ❌ Does NOT use admin layout. Should start with <x-layouts.admin>\n";
            }
        }
    } else {
        echo "❌ View missing: $view\n";
    }
}

// Check AuthServiceProvider
echo "\nChecking AuthServiceProvider...\n";
$authProviderPath = app_path('Providers/AuthServiceProvider.php');
if (File::exists($authProviderPath)) {
    $content = File::get($authProviderPath);
    if (strpos($content, "Gate::define('is-admin'") !== false) {
        echo "✅ 'is-admin' Gate is defined in AuthServiceProvider\n";
    } else {
        echo "❌ 'is-admin' Gate is NOT defined in AuthServiceProvider\n";
    }
} else {
    echo "❌ AuthServiceProvider.php not found\n";
}

echo "\nDiagnostics completed!\n";