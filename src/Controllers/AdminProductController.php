<?php

namespace Controllers;

use Core\Auth;
use Models\Product;
use Models\Category;
use Core\Config;

class AdminProductController {

    public function __construct() {
        if (!Auth::check()) {
            header('Location: index.php?url=admin/login');
            exit;
        }
    }

    public function index() {
        $products = Product::getAll();
        $appName = Config::get('app_name', 'Restaurant App');
        $logo = Config::get('logo');
        $favicon = Config::get('favicon');
        require __DIR__ . '/../../views/admin/products/index.php';
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?url=admin/products');
            exit;
        }

        $product = Product::find($id);
        if (!$product) {
            die("Product not found");
        }

        $categories = Category::all();
        $appName = Config::get('app_name', 'Restaurant App');
        $logo = Config::get('logo');
        $favicon = Config::get('favicon');
        require __DIR__ . '/../../views/admin/products/edit.php';
    }

    public function create() {
        $categories = Category::all();
        $appName = Config::get('app_name', 'Restaurant App');
        $logo = Config::get('logo');
        $favicon = Config::get('favicon');
        require __DIR__ . '/../../views/admin/products/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=admin/products');
            exit;
        }

        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'category_id' => $_POST['category_id'],
            'is_available' => isset($_POST['is_available']) ? 1 : 0
        ];

        // Handle Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/assets/images/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $data['image_path'] = 'assets/images/products/' . $fileName;
            }
        } elseif (isset($_POST['image_url_text']) && !empty($_POST['image_url_text'])) {
             $data['image_path'] = $_POST['image_url_text'];
        }

        $productId = Product::create($data);

        // Handle Variants
        if (isset($_POST['variants']) && is_array($_POST['variants'])) {
            Product::saveVariants($productId, $_POST['variants']);
        }

        header('Location: index.php?url=admin/products');
        exit;
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=admin/products');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) die("Invalid ID");

        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'category_id' => $_POST['category_id'],
            'is_available' => isset($_POST['is_available']) ? 1 : 0
        ];

        // Handle Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/assets/images/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $data['image_path'] = 'assets/images/products/' . $fileName;
            }
        } elseif (isset($_POST['image_url_text']) && !empty($_POST['image_url_text'])) {
             $data['image_path'] = $_POST['image_url_text'];
        }

        Product::update($id, $data);

        // Update Variants (Replace all)
        Product::deleteVariants($id);
        if (isset($_POST['variants']) && is_array($_POST['variants'])) {
            Product::saveVariants($id, $_POST['variants']);
        }

        header('Location: index.php?url=admin/products');
        exit;
    }
}
