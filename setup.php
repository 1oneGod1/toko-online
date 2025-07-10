<?php

/**
 * This script sets up everything needed for the application to run properly
 * Run with: php setup.php
 */

echo "=======================================\n";
echo "Toko Online - Setup Script\n";
echo "=======================================\n\n";

echo "Step 1: Checking and updating database schema...\n";
include_once __DIR__ . '/update-schema.php';

echo "\nStep 2: Ensuring storage link exists...\n";
exec('php artisan storage:link', $output, $returnCode);
if ($returnCode === 0) {
    echo "✅ Storage link created/verified successfully.\n";
} else {
    echo "❌ Error creating storage link (code $returnCode).\n";
    foreach ($output as $line) {
        echo $line . "\n";
    }
}

echo "\nStep 3: Clearing cache...\n";
exec('php artisan cache:clear', $output, $returnCode);
if ($returnCode === 0) {
    echo "✅ Cache cleared successfully.\n";
} else {
    echo "❌ Error clearing cache (code $returnCode).\n";
}

exec('php artisan config:clear', $output, $returnCode);
if ($returnCode === 0) {
    echo "✅ Config cache cleared successfully.\n";
} else {
    echo "❌ Error clearing config cache (code $returnCode).\n";
}

exec('php artisan view:clear', $output, $returnCode);
if ($returnCode === 0) {
    echo "✅ View cache cleared successfully.\n";
} else {
    echo "❌ Error clearing view cache (code $returnCode).\n";
}

echo "\nStep 4: Ensuring an admin user exists...\n";
// Create an admin user using artisan tinker instead of direct PHP code
$createAdminScript = <<<'EOD'
use App\Models\User;
use Illuminate\Support\Facades\Hash;
$adminExists = User::where('role', 'admin')->exists();
if (!$adminExists) {
    User::create([
        'name' => 'Admin Toko',
        'email' => 'admin@toko.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);
    echo "Admin user created successfully.\n";
} else {
    echo "Admin user already exists.\n";
}
EOD;

// Write the script to a temporary file
file_put_contents('create_admin.php', $createAdminScript);

// Execute the script with artisan tinker
exec('php artisan tinker --execute="require \'create_admin.php\'"', $output, $returnCode);

if ($returnCode === 0) {
    echo "✅ Admin user check completed.\n";
    echo "   If needed, you can log in with:\n";
    echo "   Email: admin@toko.com\n";
    echo "   Password: password\n";
} else {
    echo "❌ Error checking/creating admin user (code $returnCode).\n";
    foreach ($output as $line) {
        echo $line . "\n";
    }
}

// Clean up temporary file
if (file_exists('create_admin.php')) {
    unlink('create_admin.php');
}

$adminExists = User::where('role', 'admin')->exists();

if ($adminExists) {
    echo "✅ Admin user already exists.\n";
} else {
    try {
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        echo "✅ Created default admin user:\n";
        echo "   Email: admin@toko.com\n";
        echo "   Password: password\n";
    } catch (Exception $e) {
        echo "❌ Error creating admin user: " . $e->getMessage() . "\n";
    }
}

echo "\n=======================================\n";
echo "Setup completed! Your application is ready.\n";
echo "Run 'php artisan serve' to start the server.\n";
echo "=======================================\n";