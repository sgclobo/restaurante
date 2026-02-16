<?php

namespace Models;

use Core\Database;
use Core\Auth;

class Audit {
    public static function log($action, $details = '') {
        $db = Database::getInstance();
        $user = Auth::user();
        $userId = $user ? $user['id'] : null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        
        $sql = "INSERT INTO audit_logs (user_id, action, details, ip_address, created_at) VALUES (?, ?, ?, ?, datetime('now'))";
        // Fixing datetime for generic SQL if needed, but sqlite ok with datetime('now')
        // Let's use PHP date for safety again
        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO audit_logs (user_id, action, details, ip_address, created_at) VALUES (?, ?, ?, ?, ?)";
        
        $db->query($sql, [$userId, $action, $details, $ip, $now]);
    }
}
