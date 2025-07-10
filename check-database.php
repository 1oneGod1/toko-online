<?php

/**
 * This script checks if all required database tables exist and have the correct structure
 * Run with: php check-database.php
 */

echo "Checking database structure...\n\n";

try {
    // Connect to the database using the settings from .env file
    require __DIR__ . '/vendor/autoload.php';
    
    // Create Laravel application
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    // Get database credentials from the .env file
    $dbName = config('database.connections.mysql.database');
    $dbUser = config('database.connections.mysql.username');
    $dbPass = config('database.connections.mysql.password');
    $dbHost = config('database.connections.mysql.host');
    
    // Create connection
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully to database: $dbName\n\n";
    
    // Required tables
    $requiredTables = [
        'users', 'products', 'categories', 'orders', 'order_items', 
        'carts', 'settings', 'migrations', 'notifications', 'jobs',
        'password_resets', 'password_reset_tokens', 'failed_jobs', 
        'personal_access_tokens'
    ];
    
    // Get all tables in the database
    $tables = [];
    $stmt = $conn->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }
    
    echo "Found " . count($tables) . " tables in the database.\n";
    
    // Check if all required tables exist
    $missingTables = array_diff($requiredTables, $tables);
    
    if (empty($missingTables)) {
        echo "✅ All required tables exist!\n";
    } else {
        echo "❌ Missing tables: " . implode(", ", $missingTables) . "\n";
        echo "Would you like to run a fresh migration? (y/n): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        if (trim($line) == 'y') {
            echo "Running php artisan migrate:fresh --seed\n";
            exec('php artisan migrate:fresh --seed', $output, $returnCode);
            
            if ($returnCode === 0) {
                echo "✅ Migration completed successfully!\n";
                foreach ($output as $line) {
                    echo $line . "\n";
                }
            } else {
                echo "❌ Migration failed with error code $returnCode\n";
                foreach ($output as $line) {
                    echo $line . "\n";
                }
            }
        }
    }
    
    // Check important columns in products table
    if (in_array('products', $tables)) {
        echo "\nChecking products table structure...\n";
        $stmt = $conn->query("DESCRIBE products");
        $columns = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $columns[] = $row['Field'];
        }
        
        $requiredColumns = ['stock', 'discount_price'];
        $missingColumns = array_diff($requiredColumns, $columns);
        
        if (empty($missingColumns)) {
            echo "✅ Products table has all required columns.\n";
        } else {
            echo "❌ Products table is missing columns: " . implode(", ", $missingColumns) . "\n";
            echo "Adding missing columns to products table...\n";
            
            if (in_array('stock', $missingColumns)) {
                $conn->exec("ALTER TABLE products ADD COLUMN stock INT DEFAULT 0 AFTER price");
                echo "✅ Added stock column to products table.\n";
            }
            
            if (in_array('discount_price', $missingColumns)) {
                $conn->exec("ALTER TABLE products ADD COLUMN discount_price INT NULL AFTER stock");
                echo "✅ Added discount_price column to products table.\n";
            }
        }
    }
    
    echo "\nDatabase check completed!\n";
    
} catch(PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
}