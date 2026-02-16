<?php

require_once __DIR__ . '/../src/bootstrap.php';

use Core\Router;

$router = new Router();

// Define Routes
// Home
$router->add('', ['controller' => 'HomeController', 'action' => 'index']);
$router->add('menu', ['controller' => 'MenuController', 'action' => 'index']);
$router->add('kitchen', ['controller' => 'KitchenController', 'action' => 'index']);

// Admin
$router->add('admin', ['controller' => 'AdminController', 'action' => 'index']);
$router->add('admin/login', ['controller' => 'AdminController', 'action' => 'login']);
$router->add('admin/logout', ['controller' => 'AdminController', 'action' => 'logout']);
$router->add('admin/settings', ['controller' => 'AdminController', 'action' => 'settings']);

// Admin Products
$router->add('admin/products', ['controller' => 'AdminProductController', 'action' => 'index']);
$router->add('admin/products/create', ['controller' => 'AdminProductController', 'action' => 'create']);
$router->add('admin/products/store', ['controller' => 'AdminProductController', 'action' => 'store']);
$router->add('admin/products/edit', ['controller' => 'AdminProductController', 'action' => 'edit']);
$router->add('admin/products/update', ['controller' => 'AdminProductController', 'action' => 'update']);

// Admin Orders
$router->add('admin/orders', ['controller' => 'AdminOrderController', 'action' => 'index']);

// API
$router->add('api/order', ['controller' => 'ApiController', 'action' => 'order']);
$router->add('api/orders', ['controller' => 'ApiController', 'action' => 'getOrders']); // For Kitchen
$router->add('api/order/update', ['controller' => 'ApiController', 'action' => 'updateStatus']); // For Kitchen
$router->add('api/order/status', ['controller' => 'ApiController', 'action' => 'getStatus']); // For Customer

// Dispatch
$url = $_SERVER['QUERY_STRING']; // Simple query string routing for now, or assume rewrite
if (isset($_GET['url'])) {
    $url = $_GET['url'];
} else {
    // Fallback if .htaccess not present/working, use QUERY_STRING as 'url=...'
    // But for now, let's assume we might use a simple param or path info.
    // Let's use PATH_INFO if available, or just empty for home.
    $url = $_SERVER['PATH_INFO'] ?? '';
    $url = trim($url, '/');
}

$router->dispatch($url);
