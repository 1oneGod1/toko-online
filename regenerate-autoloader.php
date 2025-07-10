<?php

/**
 * This script regenerates the Composer autoloader files.
 * This is useful when new classes are added and not being found by Laravel.
 * Run with: php regenerate-autoloader.php
 */

echo "=================================================================\n";
echo " REGENERATING COMPOSER AUTOLOADER\n";
echo "=================================================================\n\n";

echo "Running: composer dump-autoload\n\n";

// Execute the command
$output = [];
$returnCode = 0;
exec('composer dump-autoload -o', $output, $returnCode);

// Display the output
foreach ($output as $line) {
    echo $line . "\n";
}

if ($returnCode === 0) {
    echo "\n✅ Composer autoloader files regenerated successfully!\n";
    echo "Laravel should now be able to find the new controllers.\n";
} else {
    echo "\n❌ Command failed with exit code: $returnCode\n";
    echo "Please check if Composer is installed and accessible in your PATH.\n";
}

echo "\nNext, please clear the application cache:\n";
echo "php artisan optimize:clear\n";

?>
