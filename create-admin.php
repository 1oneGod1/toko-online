<?php

/**
 * This script creates an admin user if one doesn't exist
 * Run with: php artisan tinker --execute="require 'create-admin.php'"
 */

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Check if an admin user already exists
$adminExists = User::where('role', 'admin')->exists();

if ($adminExists) {
    echo "An admin user already exists in the database.\n";
    $adminUser = User::where('role', 'admin')->first();
    echo "Admin email: " . $adminUser->email . "\n";
} else {
    // Create a new admin user
    try {
        $admin = User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        echo "Admin user created successfully!\n";
        echo "Email: admin@toko.com\n";
        echo "Password: password\n";
    } catch (Exception $e) {
        echo "Error creating admin user: " . $e->getMessage() . "\n";
    }
}

// Return true so Tinker shows success
return true;