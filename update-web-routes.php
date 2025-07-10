<?php

/**
 * This script updates the web.php routes file with the latest routes
 * Run with: php update-web-routes.php
 */

echo "Updating web routes file...\n";

try {
    // Read the content of web.php.new
    $newContent = file_get_contents(__DIR__ . '/routes/web.php.new');
    
    if ($newContent === false) {
        throw new Exception("Could not read routes/web.php.new file");
    }
    
    // Backup the existing web.php file
    $originalFile = __DIR__ . '/routes/web.php';
    $backupFile = __DIR__ . '/routes/web.php.backup-' . date('Y-m-d-His');
    
    if (file_exists($originalFile)) {
        if (!copy($originalFile, $backupFile)) {
            throw new Exception("Could not create backup of web.php");
        }
        echo "✅ Created backup of web.php at $backupFile\n";
    }
    
    // Write the new content to web.php
    if (file_put_contents($originalFile, $newContent) === false) {
        throw new Exception("Could not write to web.php");
    }
    
    echo "✅ Successfully updated web.php with new routes\n";
    echo "\nPlease run the following commands to update your application:\n";
    echo "   php artisan route:clear\n";
    echo "   php artisan optimize\n";
    echo "   php artisan serve\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}