<?php

/**
 * This script sets up everything needed for reviews and discussions features
 * Run with: php install-reviews-discussions.php
 */

echo "=======================================\n";
echo "Toko Online - Reviews & Discussions Setup\n";
echo "=======================================\n\n";

// 1. Copy the new product show view
echo "Step 1: Updating product detail view...\n";
$sourcePath = __DIR__ . '/resources/views/products/show-with-tabs.blade.php';
$destPath = __DIR__ . '/resources/views/products/show.blade.php';

// Create backup of original
if (file_exists($destPath)) {
    $backupPath = $destPath . '.backup-' . date('Y-m-d-His');
    if (copy($destPath, $backupPath)) {
        echo "✅ Created backup of original show.blade.php\n";
    } else {
        echo "❌ Failed to create backup of show.blade.php\n";
    }
}

// Copy the new file
if (copy($sourcePath, $destPath)) {
    echo "✅ Product detail view updated successfully\n";
} else {
    echo "❌ Failed to update product detail view\n";
}

// 2. Update routes
echo "\nStep 2: Updating routes...\n";
$updateRoutesScript = __DIR__ . '/update-web-routes.php';
if (file_exists($updateRoutesScript)) {
    include $updateRoutesScript;
} else {
    echo "❌ Could not find update-web-routes.php script\n";
}

// 3. Run migrations
echo "\nStep 3: Running migrations for new tables...\n";
$output = [];
exec('php artisan migrate', $output, $returnCode);

if ($returnCode === 0) {
    echo "✅ Migrations completed successfully\n";
    foreach ($output as $line) {
        echo "   $line\n";
    }
} else {
    echo "❌ Migration failed with code $returnCode\n";
    foreach ($output as $line) {
        echo "   $line\n";
    }
}

// 4. Update Product model if not already done
echo "\nStep 4: Verifying Product model relationships...\n";
$productModelPath = __DIR__ . '/app/Models/Product.php';
$productModel = file_get_contents($productModelPath);

if (strpos($productModel, 'getAverageRatingAttribute') === false) {
    echo "⚠️ Product model doesn't have average rating attribute. Please update your Product.php model manually.\n";
}

if (strpos($productModel, 'discussions()') === false) {
    echo "⚠️ Product model doesn't have discussions relationship. Please update your Product.php model manually.\n";
}

// 5. Add support for @stack directive in app layout
echo "\nStep 5: Adding @stack support to layout...\n";
$layoutPath = __DIR__ . '/resources/views/components/layouts/app.blade.php';
$layout = file_get_contents($layoutPath);

if (strpos($layout, '@stack(\'scripts\')') === false) {
    // Find where to add the stack directive
    $scriptPosition = strrpos($layout, '<script src="https://cdn.jsdelivr.net/npm/bootstrap');
    if ($scriptPosition !== false) {
        $endOfScripts = strpos($layout, '</body>', $scriptPosition);
        if ($endOfScripts !== false) {
            $newLayout = substr($layout, 0, $endOfScripts) . "\n    <!-- Custom Scripts -->\n    @stack('scripts')\n" . substr($layout, $endOfScripts);
            file_put_contents($layoutPath, $newLayout);
            echo "✅ Added @stack('scripts') to layout\n";
        } else {
            echo "❌ Could not find position to add scripts stack\n";
        }
    } else {
        echo "❌ Could not find position to add scripts stack\n";
    }
} else {
    echo "✅ Layout already has @stack('scripts') support\n";
}

echo "\nInstallation completed! 🎉\n";
echo "Your online store now has reviews and discussions features.\n";
echo "\nPlease run: php artisan serve\n\n";