<?php

namespace Models;

use Core\Database;
use PDO;

class Product {
    public static function getByCategory($categoryId) {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM products WHERE category_id = ? AND is_available = 1", [$categoryId]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            $stmtVar = $db->query("SELECT * FROM product_variants WHERE product_id = ? ORDER BY price ASC", [$product['id']]);
            $product['variants'] = $stmtVar->fetchAll(PDO::FETCH_ASSOC);
        }

        return $products;
    }
    
    public static function find($id) {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM products WHERE id = ?", [$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $stmtVar = $db->query("SELECT * FROM product_variants WHERE product_id = ? ORDER BY price ASC", [$id]);
            $product['variants'] = $stmtVar->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $product;
    }

    public static function getVariant($variantId) {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM product_variants WHERE id = ?", [$variantId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function getAll() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.category_id, p.name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function create($data) {
        $db = Database::getInstance();
        $fields = [];
        $placeholders = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = $key;
            $placeholders[] = "?";
            $values[] = $value;
        }
        
        $sql = "INSERT INTO products (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $db->query($sql, $values);
        return $db->lastInsertId();
    }

    public static function deleteVariants($productId) {
        $db = Database::getInstance();
        $db->query("DELETE FROM product_variants WHERE product_id = ?", [$productId]);
    }

    public static function saveVariants($productId, $variants) {
        $db = Database::getInstance();
        $stmt = $db->getConnection()->prepare("INSERT INTO product_variants (product_id, name, price) VALUES (?, ?, ?)");
        
        foreach ($variants as $variant) {
            if (!empty($variant['name']) && isset($variant['price'])) {
                $stmt->execute([$productId, $variant['name'], $variant['price']]);
            }
        }
    }

    public static function update($id, $data) {
        $db = Database::getInstance();
        // Dynamic update query
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        
        $values[] = $id;
        $sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE id = ?";
        
        $db->query($sql, $values);
    }
}
