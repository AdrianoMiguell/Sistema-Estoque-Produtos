<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\CategoryController;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/category_controller.php';

class DashboardController
{
    protected $pdo;

    function __construct()
    {
        $this->pdo = getPDO();
    }

    public function index()
    {
        $products = [];
        $categories = [];
        $subcategories = [];

        $stmt = $this->pdo->query("SELECT p.*, c.name AS category_name, s.name AS subcategory_name FROM products p LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN subcategories s ON p.subcategory_id = s.id
        ORDER BY created_at DESC LIMIT 5");
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // var_dump($products);
        // exit;

        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY created_at DESC LIMIT 5");
        $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $stmt = $this->pdo->query("SELECT * FROM subcategories ORDER BY created_at DESC LIMIT 5");
        $subcategories = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // $stmt = $this->pdo->query("SELECT c.name AS cat_name, COUNT(p.id) AS total_products FROM categories c LEFT JOIN products p ON p.category_id = c.id GROUP BY c.id, c.name ORDER BY total_products DESC LIMIT 5");
        // $products_by_categories = $stmt->fetchALL(\PDO::FETCH_ASSOC);

        $categoryController = new CategoryController();
        $products_by_categories = $categoryController->getProductsCountByCategory();

        // var_dump($products_by_categories);
        // exit;

        return renderView('dashboard', ['products' => $products, 'categories' => $categories, 'subcategories' => $subcategories, 'products_by_categories' => $products_by_categories]);
    }
}
