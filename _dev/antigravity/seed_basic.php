<?php

require_once __DIR__ . '/../../src/bootstrap.php';
use Core\Database;

$db = Database::getInstance();
$pdo = $db->getConnection();

echo "Seeding Test Data...<br>";

// Clear existing items
$pdo->exec("DELETE FROM products");
$pdo->exec("DELETE FROM categories");

// Seed Categories
$pdo->exec("INSERT INTO categories (id, name, sort_order) VALUES (1, 'Main Dishes', 1)");
$pdo->exec("INSERT INTO categories (id, name, sort_order) VALUES (2, 'Drinks', 2)");
$pdo->exec("INSERT INTO categories (id, name, sort_order) VALUES (3, 'Desserts', 3)");

// Seed Products
$products = [
    [1, 'Cheeseburger', 'Classic beef burger with cheese', 9.99],
    [1, 'Fried Chicken', 'Crispy fried chicken', 12.50],
    [1, 'Nasi Goreng', 'Special fried rice', 8.00],
    [2, 'Iced Tea', 'Sweet lemon tea', 2.50],
    [2, 'Coffee', 'Black coffee', 3.00],
    [3, 'Ice Cream', 'Vanilla scoop', 4.00],
];

$stmt = $pdo->prepare("INSERT INTO products (category_id, name, description, price, is_available) VALUES (?, ?, ?, ?, 1)");

foreach ($products as $p) {
    $stmt->execute($p);
}

echo "Seeded " . count($products) . " products.<br>";
echo "<a href='index.php'>Go Home</a>";
