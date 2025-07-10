<?php

/**
 * This script adds the role column to users table if it doesn't exist
 * Run with: php add-role-to-users.php
 */

// Load the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Checking users table structure...\n";

try {
    // Check if role column exists
    if (!Schema::hasColumn('users', 'role')) {
        echo "Adding 'role' column to users table...\n";
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('email');
        });
        echo "✅ 'role' column added successfully.\n";
        
        // Set a default admin user
        $admin = DB::table('users')->where('email', 'admin@toko.com')->first();
        
        if ($admin) {
            DB::table('users')->where('id', $admin->id)->update(['role' => 'admin']);
            echo "✅ Updated user 'admin@toko.com' to have admin role.\n";
        } else {
            // Create admin user if it doesn't exist
            DB::table('users')->insert([
                'name' => 'Admin Toko',
                'email' => 'admin@toko.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "✅ Created admin user with email 'admin@toko.com' and password 'password'.\n";
        }
    } else {
        echo "✅ 'role' column already exists in users table.\n";
        
        // Make sure at least one admin exists
        $adminExists = DB::table('users')->where('role', 'admin')->exists();
        
        if (!$adminExists) {
            $user = DB::table('users')->where('email', 'admin@toko.com')->first();
            
            if ($user) {
                DB::table('users')->where('id', $user->id)->update(['role' => 'admin']);
                echo "✅ Updated user 'admin@toko.com' to have admin role.\n";
            } else {
                // Create admin user
                DB::table('users')->insert([
                    'name' => 'Admin Toko',
                    'email' => 'admin@toko.com',
                    'password' => bcrypt('password'),
                    'role' => 'admin',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                echo "✅ Created admin user with email 'admin@toko.com' and password 'password'.\n";
            }
        } else {
            echo "✅ Admin user already exists.\n";
        }
    }
    
    echo "\nUsers table check completed successfully!\n";
    echo "You can login with admin@toko.com / password\n";
    
} catch (Exception $e) {
    echo "❌ An error occurred: " . $e->getMessage() . "\n";
}