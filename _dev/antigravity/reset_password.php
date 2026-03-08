<?php
// This is a helper script to reset the admin password.
// Once executed, it should be deleted or kept securely in this _dev folder.
require_once __DIR__ . '/../../src/bootstrap.php';

use Core\Database;

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $username = 'admin';
    $newPass = 'newadmin123';
    
    // Check if the user exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    
    if ($stmt->fetchColumn() > 0) {
        $hash = password_hash($newPass, PASSWORD_DEFAULT);
        $updateStmt = $pdo->prepare("UPDATE users SET password_hash = ?, force_pass_change = 0 WHERE username = ?");
        $updateStmt->execute([$hash, $username]);
        echo "<h1>Success!</h1>";
        echo "<p>The password for the admin user has been successfully reset.</p>";
        echo "<ul>";
        echo "<li><strong>Username:</strong> " . htmlspecialchars($username) . "</li>";
        echo "<li><strong>New Password:</strong> " . htmlspecialchars($newPass) . "</li>";
        echo "</ul>";
        echo "<p><a href='../../public/index.php?url=admin/login'>Go to Login Page</a></p>";
    } else {
        echo "<h1>Error</h1>";
        echo "<p>User '$username' not found in the database.</p>";
    }
} catch (PDOException $e) {
    if ($e->getCode() == 2002 || strpos($e->getMessage(), 'has gone away') !== false) {
         echo "<h1>Database Error</h1>";
         echo "<p>Could not connect to the database. Please make sure that <strong>MySQL is running in your XAMPP Control Panel</strong>.</p>";
         echo "<p>Technical details: " . htmlspecialchars($e->getMessage()) . "</p>";
    } else {
         echo "<h1>Database Error</h1>";
         echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    }
} catch (Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
