<?php
require_once __DIR__ . '/../../src/bootstrap.php';

use Core\Database;
use Core\Config;

$db = Database::getInstance();
$pdo = $db->getConnection();

echo "Running Migration...<br>";

try {
    // 1. Create product_variants table
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_variants (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        name VARCHAR(50) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )");
    echo "Created table 'product_variants'.<br>";

    // 2. Add variant_id to order_items
    // Check if column exists first
    $stmt = $pdo->query("SHOW COLUMNS FROM order_items LIKE 'variant_id'");
    if ($stmt->fetchColumn() === false) {
        $pdo->exec("ALTER TABLE order_items ADD COLUMN variant_id INT DEFAULT NULL AFTER product_id");
        echo "Added column 'variant_id' to 'order_items'.<br>";
    } else {
        echo "Column 'variant_id' already exists in 'order_items'.<br>";
    }

    echo "Migration Complete. <a href='index.php?url=menu'>Go to Menu</a>";

} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage();
}
