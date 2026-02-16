<?php

namespace Controllers;

class HomeController {
    public function index() {
        // In a real app, we might check for table_number in $_GET and store in session
        if (isset($_GET['table'])) {
            $_SESSION['table_number'] = $_GET['table'];
            header('Location: index.php?url=menu');
            exit;
        }
        
        // If no table, show landing page or redirect to menu anyway
        // For MVP, simple redirect to menu
        header('Location: index.php?url=menu');
        exit;
    }
}
