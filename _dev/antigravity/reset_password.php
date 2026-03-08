<?php
// This is a helper script to reset/create the admin password and seed required data.
// Once executed, it should be deleted or kept securely in this _dev folder.
require_once __DIR__ . '/../../src/bootstrap.php';

use Core\Database;

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $username = 'admin';
    $newPass = 'newadmin123';
    
    // 1. Check if the user exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    
    $hash = password_hash($newPass, PASSWORD_DEFAULT);
    if ($stmt->fetchColumn() > 0) {
        $updateStmt = $pdo->prepare("UPDATE users SET password_hash = ?, force_pass_change = 0 WHERE username = ?");
        $updateStmt->execute([$hash, $username]);
        echo "<h1>Success!</h1>";
        echo "<p>The password for the admin user has been successfully reset.</p>";
    } else {
        $insertStmt = $pdo->prepare("INSERT INTO users (username, password_hash, role, force_pass_change) VALUES (?, ?, 'admin', 0)");
        $insertStmt->execute([$username, $hash]);
        echo "<h1>Success!</h1>";
        echo "<p>The admin user was not found, so it has been successfully created.</p>";
    }

    // 2. Make sure categories are seeded too (in case this is a fresh database without data)
    $catStmt = $pdo->query("SELECT COUNT(*) FROM categories");
    if ($catStmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO categories (name, sort_order) VALUES ('Foods', 1)");
        $pdo->exec("INSERT INTO categories (name, sort_order) VALUES ('Drinks', 2)");
        echo "<p>Default categories (Foods, Drinks) seeded successfully.</p>";
    }

    // 3. Make sure settings are seeded too
    $setStmt = $pdo->query("SELECT COUNT(*) FROM settings");
    if ($setStmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO settings (`key`, value) VALUES ('restaurant_name', 'My Online Resto')");
        $pdo->exec("INSERT INTO settings (`key`, value) VALUES ('currency_symbol', '$')");
        echo "<p>Default configuration settings seeded successfully.</p>";
    }

    echo "<ul>";
    echo "<li><strong>Username:</strong> " . htmlspecialchars($username) . "</li>";
    echo "<li><strong>New Password:</strong> " . htmlspecialchars($newPass) . "</li>";
    echo "</ul>";
    echo "<p><a href='../../public/index.php?url=admin/login'>Go to Login Page</a></p>";

} catch (PDOException $e) {
    echo "<h1>Database Error</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
} catch (Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
