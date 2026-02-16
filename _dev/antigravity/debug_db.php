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
require_once __DIR__ . '/../../config/app.php';
Config::load(__DIR__ . '/../../config/app.php');

use Core\Database;

try {
    $db = Database::getInstance();
    $stmt = $db->query("DESCRIBE order_items");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>order_items Columns:</h3>";
    echo "<pre>";
    print_r($columns);
    echo "</pre>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
