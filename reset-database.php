<?php

/**
 * This script completely resets the database and removes problematic migrations
 * Run this file using: php reset-database.php
 */

echo "Starting complete database reset...\n";

try {
    // Connect to MySQL without selecting a database
    $conn = new PDO("mysql:host=127.0.0.1;port=3306", "root", "Helios007");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to MySQL server.\n";
    
    // Drop database if exists
    $dbName = "toko_online";
    $conn->exec("DROP DATABASE IF EXISTS $dbName");
    echo "Database '$dbName' dropped successfully.\n";
    
    // Create fresh database
    $conn->exec("CREATE DATABASE $dbName");
    echo "Fresh database '$dbName' created successfully.\n";
    
    echo "\n--- Now let's find and remove problematic migration files ---\n";
    
    // Find problematic migration files
    $migrationPath = __DIR__ . '/database/migrations';
    $files = scandir($migrationPath);
    
    $problemMigrations = [
        'create_wishlists_table',
        'wishlists',
        'create_notifications_table',
        'create_jobs_table'
    ];
    
    $migrationsRemoved = false;
    
    foreach ($files as $file) {
        $shouldRemove = false;
        foreach ($problemMigrations as $problemPattern) {
            if (strpos($file, $problemPattern) !== false) {
                $shouldRemove = true;
                break;
            }
        }
        
        if ($shouldRemove) {
            $fullPath = $migrationPath . '/' . $file;
            echo "Found problematic migration: $file\n";
            if (unlink($fullPath)) {
                echo "✓ Successfully deleted: $file\n";
                $migrationsRemoved = true;
            } else {
                echo "× Failed to delete: $file\n";
            }
        }
    }
    
    if (!$migrationsRemoved) {
        echo "No problematic migration files found.\n";
    }
    
    // Check for Wishlist model
    $wishlistModelPath = __DIR__ . '/app/Models/Wishlist.php';
    if (file_exists($wishlistModelPath)) {
        echo "Found Wishlist model. Deleting...\n";
        if (unlink($wishlistModelPath)) {
            echo "✓ Successfully deleted Wishlist model\n";
        } else {
            echo "× Failed to delete Wishlist model\n";
        }
    }
    
    // Check for WishlistController
    $wishlistControllerPath = __DIR__ . '/app/Http/Controllers/WishlistController.php';
    if (file_exists($wishlistControllerPath)) {
        echo "Found WishlistController. Deleting...\n";
        if (unlink($wishlistControllerPath)) {
            echo "✓ Successfully deleted WishlistController\n";
        } else {
            echo "× Failed to delete WishlistController\n";
        }
    }
    
    // Create necessary tables directly using SQL to avoid migration conflicts
    echo "\nCreating essential Laravel tables directly...\n";
    
    try {
        // Connect to the new database
        $db = new PDO("mysql:host=127.0.0.1;port=3306;dbname=$dbName", "root", "Helios007");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create migrations table
        $sql = "CREATE TABLE IF NOT EXISTS `migrations` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `migration` varchar(255) NOT NULL,
            `batch` int(11) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $db->exec($sql);
        echo "✓ Migrations table created successfully.\n";
        
        // Create notifications table
        $sql = "CREATE TABLE IF NOT EXISTS `notifications` (
            `id` char(36) NOT NULL,
            `type` varchar(255) NOT NULL,
            `notifiable_type` varchar(255) NOT NULL,
            `notifiable_id` bigint unsigned NOT NULL,
            `data` text NOT NULL,
            `read_at` timestamp NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $db->exec($sql);
        echo "✓ Notifications table created successfully.\n";
        
        // Create jobs table
        $sql = "CREATE TABLE IF NOT EXISTS `jobs` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `queue` varchar(255) NOT NULL,
            `payload` longtext NOT NULL,
            `attempts` tinyint unsigned NOT NULL,
            `reserved_at` int unsigned DEFAULT NULL,
            `available_at` int unsigned NOT NULL,
            `created_at` int unsigned NOT NULL,
            PRIMARY KEY (`id`),
            KEY `jobs_queue_index` (`queue`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $db->exec($sql);
        echo "✓ Jobs table created successfully.\n";
        
        // Insert into migrations to mark these as already run
        $migrations = [
            '2025_06_25_181300_create_notifications_table',
            '2025_06_25_181301_create_jobs_table'
        ];
        
        $stmt = $db->prepare("INSERT INTO migrations (migration, batch) VALUES (?, 1)");
        foreach ($migrations as $migration) {
            $stmt->execute([$migration]);
            echo "✓ Marked migration $migration as already run.\n";
        }
        
    } catch(PDOException $e) {
        echo "Error creating tables: " . $e->getMessage() . "\n";
    }

    echo "\nDatabase has been reset and problematic features removed.\n";
    echo "Next steps:\n";
    echo "1. Run: php artisan migrate\n";
    echo "2. Run: php artisan db:seed\n";
    echo "3. Run: php artisan serve\n";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}