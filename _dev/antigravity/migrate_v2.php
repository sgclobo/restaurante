<?php
// Autoloader
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/../../src/';
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use Core\Config;

// Load Config
Config::load(__DIR__ . '/../../config/app.php');

use Core\Database;

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();

    echo "Checking 'orders' table columns...<br>";

    // Check if order_type exists
    try {
        $pdo->query("SELECT order_type FROM orders LIMIT 1");
        echo "Column 'order_type' already exists.<br>";
    } catch (PDOException $e) {
        echo "Adding 'order_type' column...<br>";
        $pdo->exec("ALTER TABLE orders ADD COLUMN order_type VARCHAR(20) DEFAULT 'dine_in'");
    }
    
    // Check if customer_name exists (just to be safe)
    try {
        $pdo->query("SELECT customer_name FROM orders LIMIT 1");
        echo "Column 'customer_name' already exists.<br>";
    } catch (PDOException $e) {
        echo "Adding 'customer_name' column...<br>";
        $pdo->exec("ALTER TABLE orders ADD COLUMN customer_name VARCHAR(100)");
    }

    echo "Database updated successfully.<br>";
    echo "<a href='index.php'>Go to Home</a>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
