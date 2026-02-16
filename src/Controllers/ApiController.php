<?php

namespace Controllers;

use Models\Order;
use Exception;

class ApiController {
    public function order() {
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Debug Logging
            file_put_contents(__DIR__ . '/../../public/api_debug.log', date('Y-m-d H:i:s') . " Order Request: " . print_r($input, true) . "\n", FILE_APPEND);

            if (!$input || empty($input['cart'])) {
                throw new Exception('Empty Cart');
            }
            
            $table = $input['table'] ?? null;
            $customerName = $input['customerName'] ?? 'Guest';
            $orderType = $input['orderType'] ?? 'dine_in'; // dine_in | takeaway
            $cart = $input['cart'];
            
            $total = 0;
            $items = [];
            
            foreach ($cart as $key => $item) {
                $qty = (int)$item['qty'];
                $price = (float)$item['price'];
                $total += $qty * $price;
                
                $items[] = [
                    'id' => $item['productId'],
                    'variant_id' => $item['variantId'] ?? null,
                    'name' => $item['name'],
                    'price' => $price,
                    'qty' => $qty
                ];
            }
            
            $orderId = Order::create($customerName, $orderType, $table, $items, $total);
            
            echo json_encode(['success' => true, 'order_id' => $orderId]);
            
        } catch (Exception $e) {
            file_put_contents(__DIR__ . '/../../public/api_debug.log', date('Y-m-d H:i:s') . " Order Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getOrders() {
        header('Content-Type: application/json');
        try {
            $orders = Order::getActiveOrders();
            // Fetch items for each order
            foreach ($orders as &$order) {
                $order['items'] = Order::getItems($order['id']);
            }
            echo json_encode(['success' => true, 'orders' => $orders]);
        } catch (Exception $e) {
             echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function updateStatus() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || empty($input['id']) || empty($input['status'])) {
                throw new Exception('Invalid Input');
            }
            
            Order::updateStatus($input['id'], $input['status']);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
             echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function getStatus() {
        header('Content-Type: application/json');
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                throw new Exception('Order ID required');
            }
            
            // Allow checking "completed" orders too, so we need a method that doesn't filter by status
            // Order::findById($id) ??
            // Let's use a direct query here for simplicity or add findById to Order model.
            // Using Order::getAll() is inefficient.
            // Let's assume we can fetch by ID. 
            // I'll add findById to Order model in a moment.
            
            $order = Order::findById($id);
            if (!$order) {
                throw new Exception('Order not found');
            }
            
            echo json_encode(['success' => true, 'status' => $order['status']]);
        } catch (Exception $e) {
             echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
