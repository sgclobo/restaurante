<?php
require_once __DIR__ . '/../../src/bootstrap.php';

use Core\Database;

$db = Database::getInstance();
$pdo = $db->getConnection();

echo "Seeding Products...<br>";

try {
    // Get Categories
    $stmt = $pdo->query("SELECT id, name FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $foodsId = null;
    $drinksId = null;

    foreach ($categories as $cat) {
        if ($cat['name'] === 'Foods' || $cat['name'] === 'Main Dishes') $foodsId = $cat['id'];
        if ($cat['name'] === 'Drinks') $drinksId = $cat['id'];
    }

    // Prepare check statement globally
    $check = $pdo->prepare("SELECT id FROM products WHERE name = ?");

    // Seed Foods
    if ($foodsId) {
        // Burger
        $check->execute(['Burger']);
        $burgerId = $check->fetchColumn();

        if (!$burgerId) {
            $pdo->prepare("INSERT INTO products (category_id, name, description, price, is_available, image_path) VALUES (?, ?, ?, ?, 1, ?)")
                ->execute([$foodsId, 'Burger', 'Juicy beef patty with fresh lettuce and secret sauce.', 5.99, 'https://placehold.co/400x300/e67e22/ffffff?text=Burger']);
            $burgerId = $pdo->lastInsertId();
            echo "Seeded Burger.<br>";
        } else {
            $pdo->prepare("UPDATE products SET image_path = ? WHERE id = ?")
                ->execute(['https://placehold.co/400x300/e67e22/ffffff?text=Burger', $burgerId]);
             echo "Updated Burger Image.<br>";
        }
        
        // Burger Variants
        // Clear existing to avoid dupes or check? Let's check.
        $checkVar = $pdo->prepare("SELECT COUNT(*) FROM product_variants WHERE product_id = ?");
        $checkVar->execute([$burgerId]);
        if ($checkVar->fetchColumn() == 0) {
            $pdo->prepare("INSERT INTO product_variants (product_id, name, price) VALUES (?, ?, ?)")->execute([$burgerId, 'Single', 5.99]);
            $pdo->prepare("INSERT INTO product_variants (product_id, name, price) VALUES (?, ?, ?)")->execute([$burgerId, 'Double', 7.99]);
            echo "Seeded Burger Variants.<br>";
        }

        // Pizza
        $check->execute(['Pizza']);
        $pizzaId = $check->fetchColumn();
        
        if (!$pizzaId) {
            $pdo->prepare("INSERT INTO products (category_id, name, description, price, is_available, image_path) VALUES (?, ?, ?, ?, 1, ?)")
                ->execute([$foodsId, 'Pizza', 'Authentic Italian pizza with mozzarella and basil.', 8.99, 'https://placehold.co/400x300/c0392b/ffffff?text=Pizza']);
            $pizzaId = $pdo->lastInsertId();
            echo "Seeded Pizza.<br>";
        } else {
             $pdo->prepare("UPDATE products SET image_path = ? WHERE id = ?")
                ->execute(['https://placehold.co/400x300/c0392b/ffffff?text=Pizza', $pizzaId]);
             echo "Updated Pizza Image.<br>";
        }

        // Pizza Variants
        $checkVar->execute([$pizzaId]);
        if ($checkVar->fetchColumn() == 0) {
            $pdo->prepare("INSERT INTO product_variants (product_id, name, price) VALUES (?, ?, ?)")->execute([$pizzaId, 'Small (10")', 8.99]);
            $pdo->prepare("INSERT INTO product_variants (product_id, name, price) VALUES (?, ?, ?)")->execute([$pizzaId, 'Medium (12")', 11.99]);
            $pdo->prepare("INSERT INTO product_variants (product_id, name, price) VALUES (?, ?, ?)")->execute([$pizzaId, 'Large (14")', 14.99]);
            echo "Seeded Pizza Variants.<br>";
        }
    }

    // Seed/Update Existing Products from User DB
    // Cheeseburger
    if ($foodsId) {
         $check->execute(['Cheeseburger']);
         $cbId = $check->fetchColumn();
         if ($cbId) {
             $pdo->prepare("UPDATE products SET image_path = ? WHERE id = ?")
                ->execute(['https://placehold.co/400x300/f39c12/ffffff?text=Cheeseburger', $cbId]);
             
             // Variants
             // Check if variants exist to avoid dupes
             $checkVar = $pdo->prepare("SELECT COUNT(*) FROM product_variants WHERE product_id = ?");
             $checkVar->execute([$cbId]);
             if ($checkVar->fetchColumn() == 0) {
                 $pdo->prepare("INSERT INTO product_variants (product_id, name, price) VALUES (?, ?, ?)")->execute([$cbId, 'Single', 9.99]);
                 $pdo->prepare("INSERT INTO product_variants (product_id, name, price) VALUES (?, ?, ?)")->execute([$cbId, 'Double', 12.99]);
                 echo "Added Variants to Cheeseburger.<br>";
             }
         }

         $check->execute(['Fried Chicken']);
         $fcId = $check->fetchColumn();
         if ($fcId) {
             $pdo->prepare("UPDATE products SET image_path = ? WHERE id = ?")
                ->execute(['https://placehold.co/400x300/d35400/ffffff?text=Chicken', $fcId]);
         }
         
         $check->execute(['Nasi Goreng']);
         $ngId = $check->fetchColumn();
         if ($ngId) {
             $pdo->prepare("UPDATE products SET image_path = ? WHERE id = ?")
                ->execute(['https://placehold.co/400x300/27ae60/ffffff?text=Nasi+Goreng', $ngId]);
             
             // Variants
             $checkVar->execute([$ngId]);
             if ($checkVar->fetchColumn() == 0) {
                 $pdo->prepare("INSERT INTO product_variants (product_id, name, price) VALUES (?, ?, ?)")->execute([$ngId, 'Regular', 8.00]);
                 $pdo->prepare("INSERT INTO product_variants (product_id, name, price) VALUES (?, ?, ?)")->execute([$ngId, 'Extra Spicy', 8.50]);
                 echo "Added Variants to Nasi Goreng.<br>";
             }
         }
    }

    // Seed Drinks
    if ($drinksId) {
        $check->execute(['Coke']);
        $cokeId = $check->fetchColumn();

        if (!$cokeId) {
            $pdo->prepare("INSERT INTO products (category_id, name, description, price, is_available, image_path) VALUES (?, ?, ?, ?, 1, ?)")
                ->execute([$drinksId, 'Coke', 'Ice cold classic cola.', 1.99, 'https://placehold.co/400x300/2c3e50/ffffff?text=Coke']);
             $cokeId = $pdo->lastInsertId();
            echo "Seeded Coke.<br>";
        } else {
            $pdo->prepare("UPDATE products SET image_path = ? WHERE id = ?")
                ->execute(['https://placehold.co/400x300/2c3e50/ffffff?text=Coke', $cokeId]);
            echo "Updated Coke Image.<br>";
        }

        $check->execute(['Iced Tea']);
        $itId = $check->fetchColumn();
        if ($itId) {
             $pdo->prepare("UPDATE products SET image_path = ? WHERE id = ?")
                ->execute(['https://placehold.co/400x300/f1c40f/ffffff?text=Iced+Tea', $itId]);
             echo "Updated Iced Tea Image.<br>";
        }

        $check->execute(['Coffee']);
        $cfId = $check->fetchColumn();
        if ($cfId) {
             $pdo->prepare("UPDATE products SET image_path = ? WHERE id = ?")
                ->execute(['https://placehold.co/400x300/34495e/ffffff?text=Coffee', $cfId]);
             echo "Updated Coffee Image.<br>";
        }
    }

    echo "Product Seeding Complete. <a href='index.php?url=menu'>Go to Menu</a>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
