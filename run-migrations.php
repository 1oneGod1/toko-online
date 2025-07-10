<?php

/**
 * This script runs the database migrations to create missing tables.
 * Run with: php run-migrations.php
 */

echo "=================================================================\n";
echo " RUNNING DATABASE MIGRATIONS\n";
echo "=================================================================\n\n";

echo "Running: php artisan migrate\n\n";

// Execute the command
$output = [];
$returnCode = 0;
exec('php artisan migrate --force', $output, $returnCode);

// Display the output
foreach ($output as $line) {
    echo $line . "\n";
}

if ($returnCode === 0) {
    echo "\n✅ Migrations completed successfully!\n";
    echo "The 'discussions' and 'reviews' tables should now be created.\n";
} else {
    echo "\n❌ Migration command failed with exit code: $returnCode\n";
    echo "Please check the error messages above.\n";
}

echo "\nAfter running migrations, please clear your cache and restart the server:\n";
echo "1. php artisan optimize:clear\n";
echo "2. php artisan serve\n";

?>