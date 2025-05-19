<?php

namespace App\Controllers\Admin;

use PDOException;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../util/save_image.php';

class ImageController
{
    protected $pdo;

    function __construct()
    {
        $this->pdo = getPDO();
    }

    function drawImage() {}

    function getImage() {}

    function getImageFromProduct($productId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM product_images WHERE product_id = ?");
        $stmt->execute([$productId]);
        $dataImage = $stmt->fetch();

        if (isset($dataImage)) {
            return $dataImage;
        }
    }


    public function setImageProduct($dataImage): bool
    {
        $status = false;
        $image_path = saveImage("{$dataImage['product_name']}_{$dataImage['product_id']}", '/../public/uploads/products/', 'products');

        if (empty($image_path)) return $status;

        $dataImage['image_path'] = $image_path;

        try {
            $stmt = $this->pdo->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
            $stmt->execute([$dataImage['product_id'], $dataImage['image_path']]);
            $status = true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $status;
    }

    public function saveImageProduct()
    {
        $product_id = "";
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'];
            $product_name = $_POST['product_name'];

            if (!empty($product_id) && !empty($product_name)) {
                if (isset($_FILES['image']['tmp_name'])) {
                    $product_name_replace = str_replace(" ", "_", preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($product_name))));

                    $dataImage = [
                        'product_name' => substr(strtolower($product_name_replace), 0, 50),
                        'product_id' => $product_id,
                    ];

                    $result = $this->setImageProduct($dataImage);

                    if (!$result) {
                        $errors[] = "Erro ao salvar imagem de produto. Tente novamente";
                    }
                } else {
                    $errors[] = "Erro ao carregar imagem de produto para salvamento";
                }
            } else {
                $errors[] = "Campo obrigatório 'Nome' faltando.";
            }
        } else {
            $errors[] = "Problema ao salvar a imagem. Requisição não reconhecida!";
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = $errors[0];
        }

        $get_var_final = !empty($product_id) ? "?product_id=$product_id" : "";

        header("Location: " . BASE_URL . "/dashboard/products/control" . $get_var_final);
        exit;
    }

    public function saveImageCategory($category_name): string
    {
        $image_path = "";
        $errors = [];

        if (!empty($category_name)) {
            if (isset($_FILES['image']['tmp_name'])) {
                $category_name_replace = str_replace(" ", "_", preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($category_name))));

                $image_path = saveImage("{$category_name_replace}", '/../public/uploads/categories/', 'categories');
                
                $dataImage['image_path'] = $image_path;
            } else {
                $errors[] = "Erro ao carregar icone de cadastro para salvamento";
            }
        } else {
            $errors[] = "Problema ao salvar a imagem. Requisição não reconhecida!";
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = $errors[0];
        }

        return $image_path;
    }
}
