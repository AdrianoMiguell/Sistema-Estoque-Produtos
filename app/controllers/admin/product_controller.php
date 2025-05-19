<?php

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/image_controller.php';

use App\Controllers\Admin\ImageController;
use PDO;
use PDOException;

class ProductController
{
    protected $pdo;
    protected $products;

    public function __construct()
    {
        $this->pdo = getPDO();
        $this->products = [];
    }

    public function index()
    {
        $error = '';
        $allowedFields = [
            'name' => 'p.name',
            'description' => 'p.description',
            'brand' => 'p.brand',
            'price' => 'p.price',
            'promotion' => 'p.promotion',
            'quantity' => 'p.quantity',
            'manufacturer' => 'p.manufacturer',
            'category' => 'c.name',
            'subcategory' => 's.name'
        ];

        $fields = [
            'name' => 'Nome',
            'description' => 'Descrição',
            'brand' => 'Marca',
            'price' => 'Preço',
            'promotion' => 'Promoção',
            'quantity' => 'Quantidade',
            'manufacturer' => 'Fabricante',
            'category' => 'Categoria',
            'subcategory' => 'Subcategoria'
        ];

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $field_selected = $_GET['field_selected'] ?? 'name';

            if ($field_selected && array_key_exists($field_selected, $allowedFields)) {
                $sqlField = $allowedFields[$field_selected];
                try {
                    $stmt = $this->pdo->prepare(
                        "SELECT 
                            p.*, 
                            c.name AS category_name, 
                            s.name AS subcategory_name
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        LEFT JOIN subcategories s ON p.subcategory_id = s.id
                        WHERE $sqlField LIKE :search
                        ORDER BY :order ASC;"
                    );

                    $searchTerm = "%$search%";
                    $stmt->bindValue(':search', $searchTerm);
                    $stmt->bindValue(':order', $sqlField);
                    $stmt->execute();

                    $this->products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    echo $e->getMessage() .
                        $error = "Erro ao carregar dados.";
                }
            } else {
                echo "Campo de busca inválido.";
            }
        } else {
            try {
                $stmt = $this->pdo->query(
                    "SELECT 
                    p.*, 
                    c.name AS category_name, 
                    s.name AS subcategory_name
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    LEFT JOIN subcategories s ON p.subcategory_id = s.id
                    ORDER BY p.created_at DESC;
                    "
                );
                $this->products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage() .
                    $error = "Erro ao carregar dados.";
            }
        }

        if (!empty($error)) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $_SESSION['error_message'] = "Erro ao carregar dados.";
        }

        renderView('product_dashboard', ['products' => $this->products, 'search' => $search ?? '', 'fields' => $fields, 'field_selected' => $field_selected ?? '']);
    }

    public function control()
    {
        $product = null;

        if (isset($_GET['product_id'])) {
            $id = $_GET['product_id'] ?? '';

            if (empty($id)) {
                if (session_status() !== PHP_SESSION_ACTIVE) {
                    session_start();
                }
                $_SESSION['error_message'] = "Erro ao carregar dados para edição. Tente novamente.";
            }

            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();

            $stmt = $this->pdo->prepare("SELECT * FROM product_images WHERE product_id = ?");
            $stmt->execute([$id]);
            $product_images = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (empty($product)) {
                $_SESSION['error_message'] = "Erro ao carregar dados para edição. Tente novamente.";
            }
        }

        $stmt = $this->pdo->query("SELECT id, name FROM categories ORDER BY name");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        renderView('product_forms', ['product' => $product, 'product_images' => $product_images ?? null, 'categories' => $categories]);
    }

    public function getSubcategories()
    {
        header('Content-Type: application/json');

        $category_id = $_GET['category_id'] ?? null;

        if (!$category_id) {
            echo json_encode([]);
            return;
        }

        $stmt = $this->pdo->prepare("SELECT id, name FROM subcategories WHERE category_id = ?");
        $stmt->execute([$category_id]);
        $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($subcategories);
    }

    /* @method='POST' */
    public function store()
    {
        require_once __DIR__ . '/../../util/save_image.php';
        $errors = [];

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? null;
            $category_id = $_POST['category_id'] ?? null;
            $subcategory_id = $_POST['subcategory_id'] ?? null;
            $price = $this->convertMoneyToCents($_POST['price']);
            $promotion = $_POST['promotion'];
            $promotional_price = $this->calcPromotionalPrice($_POST['price'], $_POST['promotion']);
            $description = $_POST['description'] ?? null;
            $brand = $_POST['brand'] ?? null;
            $manufacturer = $_POST['manufacturer'] ?? null;
            $size = $_POST['size'] ?? null;
            $quantity = $_POST['quantity'] ?? null;

            if (empty($name)) $errors[] = "O campo 'Nome' é obrigatório";
            if (empty($price)) $errors[] = "O campo 'Preço' é obrigatório";

            if (empty($errors)) {
                try {

                    $stmt = $this->pdo->prepare("INSERT INTO products (name, category_id, subcategory_id, price, promotion, promotional_price, description, brand, manufacturer, size, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                    $stmt->execute([
                        $name,
                        !empty($category_id) ? $category_id : null,
                        !empty($subcategory_id) ? $subcategory_id : null,
                        $price,
                        $promotion,
                        $promotional_price,
                        !empty($description) ? $description : null,
                        !empty($brand) ? $brand : null,
                        !empty($manufacturer) ? $manufacturer : null,
                        !empty($size) ? $size : null,
                        $quantity,
                    ]);

                    $product_id = $this->pdo->lastInsertId();

                    if (isset($_FILES['image']['tmp_name'])) {
                        $dataImage = [
                            'product_name' => substr(strtolower($name), 0, 50),
                            'product_id' => $product_id,
                        ];

                        if (!class_exists('App\Controllers\Admin\ImageController')) {
                            die('Classe ImageController não encontrada');
                            $errors[] = "Erro ao salvar imagem de produto. Tente novamente";
                        }

                        $imageController = new ImageController();
                        $result = $imageController->setImageProduct($dataImage);

                        if (!$result && empty($_SESSION['errors'])) {
                            $errors[] = "Erro ao salvar imagem de produto. Tente novamente";
                            //code...
                        }
                    }
                } catch (\PDOException $e) {
                    var_dump($e->getMessage());
                    $errors[] = "Erro ao salvar produto. Tente novamente.";
                }
            }
        } else {
            $errors[] = "O metodo de requisição não é suportado.";
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = $errors[0];
            $_SESSION['form_data'] = [
                'name' => $name,
                'category_id' => $category_id,
                'subcategory_id' => $subcategory_id,
                'price' => $price,
                'promotion' => $promotion,
                'description' => $description,
                'brand' => $brand,
                'manufacturer' => $manufacturer,
                'size' => $size,
                'quantity' => $quantity,
            ];

            header("Location: " . BASE_URL . "/dashboard/products/control");
            exit;
        }

        header("Location: " . BASE_URL . "/dashboard/products");
    }

    /* @method='POST' */
    public function edit()
    {
        $product_id = "";
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'];
            $name = $_POST['name'] ?? null;
            $category_id = $_POST['category_id'] ?? null;
            $subcategory_id = $_POST['subcategory_id'] ?? null;
            $price = $this->convertMoneyToCents($_POST['price']);
            $promotion = $_POST['promotion'];
            $promotional_price = $this->calcPromotionalPrice($_POST['price'], $_POST['promotion']);
            $description = $_POST['description'] ?? null;
            $brand = $_POST['brand'] ?? null;
            $manufacturer = $_POST['manufacturer'] ?? null;
            $size = $_POST['size'] ?? null;
            $quantity = $_POST['quantity'] ?? null;

            if (!empty($product_id)) {
                // var_dump($price);
                // var_dump($promotion);
                // var_dump($promotional_price);
                // exit;

                if (empty($name)) $errors[] = "O campo 'Nome' é obrigatório";
                if (empty($price)) $errors[] = "O campo 'Preço' é obrigatório";

                if (empty($errors)) {
                    $stmt = $this->pdo->prepare("UPDATE products SET name = ?, category_id = ?, subcategory_id = ?, price = ?, promotion = ?, promotional_price = ?, description = ?, brand = ?, manufacturer = ?, size = ?, quantity = ? WHERE id = ?");
                    $stmt->execute([
                        $name,
                        !empty($category_id) ? $category_id : null,
                        !empty($subcategory_id) ? $subcategory_id : null,
                        $price,
                        $promotion,
                        $promotional_price,
                        !empty($description) ? $description : null,
                        !empty($brand) ? $brand : null,
                        !empty($manufacturer) ? $manufacturer : null,
                        !empty($size) ? $size : null,
                        $quantity,
                        $product_id
                    ]);
                }
            } else {
                $errors[] = "Campo obrigatório 'ID' não foi encontrado";
            }
        } else {
            $errors[] = "Erro ao executar esta ação. Tente novamente";
        }

        $get_var_final = !empty($product_id) ? "?product_id=$product_id" : "";

        if (!empty($errors)) {
            var_dump("Entrando no erron ");
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $_SESSION['error_message'] = $errors[0];
            $_SESSION['form_data'] = [
                'name' => $name,
                'category_id' => $category_id,
                'subcategory_id' => $subcategory_id,
                'price' => $price,
                'promotion' => $promotion,
                'description' => $description,
                'brand' => $brand,
                'manufacturer' => $manufacturer,
                'size' => $size,
                'quantity' => $quantity,
            ];

            header("Location: " . BASE_URL . "/dashboard/products/control" . $get_var_final);
            exit;
        }

        header("Location: " . BASE_URL . "/dashboard/products" . $get_var_final);
        exit;
    }

    public function delete()
    {
        $error = "";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['product_id'];

            if (empty($id)) {
                $error = "Erro ao deletar registro.";
            } else {
                try {
                    $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
                    $stmt->execute([$id]);
                } catch (PDOException $e) {
                    echo $e->getMessage() .
                        $error = "Erro ao deletar registro.";
                }
            }
        } else {
            $error = "Erro ao deletar registro.";
        }

        if (!empty($error)) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $_SESSION['error_message'] = "Erro ao deletar registro.";
        }

        header("Location: " . BASE_URL . "/dashboard/products");
    }

    function convertMoneyToCents($value)
    {
        return (int) ($value * 100);
    }

    function calcPromotionalPrice($price, $promotion)
    {
        return (int) (($price * ($promotion / 100)) * 100);
    }
}
