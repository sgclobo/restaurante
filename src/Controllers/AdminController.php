<?php

namespace Controllers;

use Core\Auth;
use Core\Config; // Added Config usage
use Core\Database;

class AdminController {
    
    public function __construct() {
        // Middleware-like check
        // If action is login, skip check
        // Ideally Router handles this via params, but for now manual check in methods or ctor if we can know action
    }

    public function index() {
        if (!Auth::check()) {
            header('Location: index.php?url=admin/login');
            exit;
        }
        
        $appName = Config::get('app_name', 'Restaurant App'); // Retrieve app name
        $logo = Config::get('logo');
        $favicon = Config::get('favicon');
        require __DIR__ . '/../../views/admin/dashboard.php';
    }

    public function login() {
        if (Auth::check()) {
            header('Location: index.php?url=admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (Auth::login($username, $password)) {
                \Models\Audit::log('login_success', "User $username logged in");
                header('Location: index.php?url=admin');
                exit;
            } else {
                \Models\Audit::log('login_failed', "Failed login attempt for $username");
                $error = "Invalid credentials";
            }
        }
        
        $appName = Config::get('app_name', 'Restaurant App');
        $logo = Config::get('logo');
        $favicon = Config::get('favicon');
        require __DIR__ . '/../../views/admin/login.php';
    }
    
    public function settings() {
        if (!Auth::isAdmin()) {
             // Basic RBAC check
             die("Access Denied: Admin only");
        }
        
        $db = Database::getInstance(); // Or use a Settings Model
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['restaurant_name'] ?? '';
            // Update DB
            $db->query("UPDATE settings SET value = ? WHERE `key` = 'restaurant_name'", [$name]);
            \Models\Audit::log('settings_update', "Updated restaurant name to $name");
            $success = "Settings Saved";
        }
        
        $stmt = $db->query("SELECT value FROM settings WHERE `key` = 'restaurant_name'");
        $currentName = $stmt->fetchColumn();
        
        $appName = Config::get('app_name');
        $favicon = Config::get('favicon');
        require __DIR__ . '/../../views/admin/settings.php';
    }

    public function logout() {
        if (Auth::check()) {
            \Models\Audit::log('logout', "User " . $_SESSION['user_name'] . " logged out");
        }
        Auth::logout();
        header('Location: index.php?url=admin/login');
    }
}
