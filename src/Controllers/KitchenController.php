<?php

namespace Controllers;

use Core\Config;

class KitchenController {
    public function index() {
        // Security check: typically restrict to admin/staff session? 
        // For MVP, open or check session exist
        // if (!isset($_SESSION['user_role'])) { header('Location: index.php?url=admin/login'); exit; }
        
        $appName = Config::get('app_name');
        $favicon = Config::get('favicon');
        $logo = Config::get('logo');
        require __DIR__ . '/../../views/kitchen/index.php';
    }
}
