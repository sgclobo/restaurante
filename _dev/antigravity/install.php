<?php

require_once __DIR__ . '/../../src/bootstrap.php';

use Core\Database;
use Core\Config;

// Simple password protection or IP check could be added here
// For now, let's just run it.

// Pre-check for MySQL: Create Database if not exists
$dbConfig = Config::get('database');
// Pre-check for MySQL: Create Database if not exists
$dbConfig = Config::get('database');

if (isset($dbConfig['driver']) && $dbConfig['driver'] === 'mysql') {
    try {
        $host = $dbConfig['host'];
        $user = $dbConfig['user'];
        $pass = $dbConfig['pass'];
        $dbname = $dbConfig['name'];
        
        // Connect without dbname
        $pdoTemp = new PDO("mysql:host=$host", $user, $pass);
        $pdoTemp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database
        $pdoTemp->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "Database '$dbname' checked/created successfully.<br>";
        
        // Close temp connection
        $pdoTemp = null;
    } catch (PDOException $e) {
        die("MySQL Setup Error: " . $e->getMessage() . "<br>Please ensure XAMPP MySQL is running and credentials in config/app.php are correct.");
    }
}

$db = Database::getInstance();
$pdo = $db->getConnection();

$schemaFile = __DIR__ . '/../../database/schema.sql';
$sql = file_get_contents($schemaFile);

// Handle SQLite vs MySQL specific syntax if needed, but for now assuming compatible SQL or SQLite
try {
    // Split by semicolon to run statements individually if needed, or just run exec
    // SQLite can handle multiple statements in one go mostly, but safely split
    $statements = explode(';', $sql);
    foreach ($statements as $statement) {
        if (trim($statement) != '') {
            $pdo->exec($statement);
        }
    }
    
    echo "Database schema imported successfully.<br>";
    
    // Seed Admin
    $user = 'admin';
    $pass = 'admin123';
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    
    // Check if admin exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$user]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role, force_pass_change) VALUES (?, ?, 'admin', 1)");
        $stmt->execute([$user, $hash]);
        echo "Admin user created (admin / admin123).<br>";
    }

    // Seed Categories
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO categories (name, sort_order) VALUES ('Foods', 1)");
        $pdo->exec("INSERT INTO categories (name, sort_order) VALUES ('Drinks', 2)");
        echo "Default categories seeded.<br>";
    }
    
    // Seed Settings
    $stmt = $pdo->query("SELECT COUNT(*) FROM settings");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO settings (`key`, value) VALUES ('restaurant_name', 'My Offline Resto')");
        $pdo->exec("INSERT INTO settings (`key`, value) VALUES ('currency_symbol', '$')");
        echo "Default settings seeded.<br>";
    }

    echo "Installation Complete. <a href='index.php'>Go to Home</a>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
