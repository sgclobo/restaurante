<?php

namespace Controllers;

use Models\Category;
use Models\Product;
use Core\Config;

class MenuController {
    public function index() {
        $categories = Category::all();
        $menu = [];
        
        foreach ($categories as $cat) {
            $menu[$cat['id']] = [
                'name' => $cat['name'],
                'products' => Product::getByCategory($cat['id'])
            ];
        }

        $appName = Config::get('app_name'); // Or fetch from settings DB
        $currency = Config::get('currency', '$'); // Should fetch from DB settings
        $logo = Config::get('logo');
        $favicon = Config::get('favicon');
        
        // Load View
        require __DIR__ . '/../../views/customer/menu.php';
    }
}
