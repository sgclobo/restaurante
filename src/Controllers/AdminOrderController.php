<?php

namespace Controllers;

use Core\Auth;
use Core\Config;
use Models\Order; // We need to check if Order model exists and has necessary methods

class AdminOrderController {

    public function __construct() {
        if (!Auth::check()) {
            header('Location: index.php?url=admin/login');
            exit;
        }
    }

    public function index() {
        // Fetch orders. We might need to add getAll to Order model.
        // For now, let's assume Order::getAll() exists or we need to create it.
        // Checking Order model in next step. For now, empty array or try fetch.
        
        $orders = \Models\Order::getAll(); 

        $appName = Config::get('app_name', 'Restaurant App');
        $logo = Config::get('logo');
        $favicon = Config::get('favicon');
        
        require __DIR__ . '/../../views/admin/orders/index.php';
    }
}
