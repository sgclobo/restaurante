<?php

namespace Models;

use Core\Database;
use PDO;
use Exception;

class Order {
    public static function create($customerName, $orderType, $tableNumber, $items, $total) {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        
        try {
            $pdo->beginTransaction();
            
            // Insert Order
            $now = date('Y-m-d H:i:s');
            $stmt = $pdo->prepare("INSERT INTO orders (customer_name, order_type, table_number, total, status, created_at) VALUES (?, ?, ?, ?, 'new', ?)");
            $stmt->execute([$customerName, $orderType, $tableNumber, $total, $now]);
            $orderId = $pdo->lastInsertId();
            
            // Insert Items
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, variant_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?, ?)");
            
            foreach ($items as $item) {
                $stmt->execute([$orderId, $item['id'], $item['variant_id'], $item['name'], $item['price'], $item['qty']]);
            }
            
            $pdo->commit();
            return $orderId;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function getActiveOrders() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM orders WHERE status IN ('new', 'preparing', 'ready') ORDER BY created_at ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getItems($orderId) {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM order_items WHERE order_id = ?", [$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateStatus($id, $status) {
        $db = Database::getInstance();
        $db->query("UPDATE orders SET status = ? WHERE id = ?", [$status, $id]);
    }

    public static function getAll() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM orders ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM orders WHERE id = ?", [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
