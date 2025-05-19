<?php

namespace App\Controllers\Client;

require_once __DIR__ . "/../../config/database.php";

class ViewproductController
{

    protected $pdo;

    function __construct()
    {
        $this->pdo = getPDO();
    }

    public function index()
    {
        $id = $_GET['id'];

        if ($id != null) {
            $product = $this->pdo->query("SELECT * FROM products WHERE id = $id")->fetch();

            $product_image = $this->pdo->query("SELECT * FROM product_images WHERE product_id = $id")->fetch();

            // var_dump($product);
        }

        return renderView("product", ['product' => $product, 'product_image' => $product_image]);
    }
}
