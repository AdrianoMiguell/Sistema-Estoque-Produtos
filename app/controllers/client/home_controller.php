<?php

namespace App\Controllers\Client;

require_once __DIR__ . "/../../config/database.php";

class HomeController
{

    protected $pdo;

    function __construct()
    {
        $this->pdo = getPDO();
    }


    public function index()
    {
        if (isAdmin()) {
            header('Location: ' . BASE_URL . '/dashboad');
            exit;
        }

        $stmt = $this->pdo->query("SELECT p.*, c.name as category_name, pi.image_path as image_path FROM products p LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN (SELECT product_id, MIN(image_path) AS image_path FROM product_images GROUP BY product_id) pi ON p.id = pi.product_id");
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $stmt = $this->pdo->query("SELECT * FROM product_images pi WHERE pi.id IN (SELECT MIN(id) FROM product_images GROUP BY product_id) GROUP BY pi.product_id");
        $product_images = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $groupedProducts = [];

        foreach ($products as $product) {
            $category = $product['category_name'] ?? 'Sem categoria';
            $groupedProducts[$category][] = $product;
        }

        return renderView("home", ['groupedProducts' => $groupedProducts, 'product_images' => $product_images]);
    }
}
