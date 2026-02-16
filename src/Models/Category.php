<?php

namespace Models;

use Core\Database;
use PDO;

class Category {
    public static function all() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
