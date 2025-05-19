<?php

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/image_controller.php';

use PDOException;

class CategoryController
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function index()
    {
        $error = '';
        $categories = [];

        try {
            $stmt = $this->pdo->query("SELECT * FROM categories");
            $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            $error = "Erro ao carregar dados.";
        }

        if (!empty($error)) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $_SESSION['error_message'] = "Erro ao carregar dados.";
        }

        renderView('category_dashboard', ['categories' => $categories]);
    }

    public function control()
    {
        $category = null;

        if (isset($_GET['category_id'])) {
            $category_id = $_GET['category_id'] ?? '';

            if (empty($category_id)) {
                if (session_status() !== PHP_SESSION_ACTIVE) {
                    session_start();
                }
                $_SESSION['error_message'] = "Erro ao carregar os dados. Tente novamente.";
            }

            try {
                $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
                $stmt->execute([$category_id]);
                $category = $stmt->fetch();

                $stmt = $this->pdo->prepare("SELECT * FROM subcategories WHERE category_id = ?");
                $stmt->execute([$category_id]);
                $subcategories = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // echo $e->getMessage();
                $_SESSION['error_message'] = "Erro ao carregar os dados. Tente novamente.";
            }

            if (empty($category)) {
                $_SESSION['error_message'] = "Erro ao carregar os dados. Tente novamente.";
            }
        }

        renderView('category_forms', ['category' => $category, 'subcategories' => $subcategories ?? null]);
    }

    /* @method='POST' */
    public function store()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? null;

            if (empty($name)) $errors[] = "O campo 'Nome' é obrigatório";

            $imageController = new ImageController();
            $path_image = $imageController->saveImageCategory($name);

            if (empty($path_image)) $errors[] = "Erro ao salvar imagem de icone. Tente novamente.";

            if (empty($errors)) {
                try {
                    $stmt = $this->pdo->prepare("INSERT INTO categories (name,  description, icon) VALUES (?, ?, ?)");
                    $stmt->execute([
                        $name,
                        !empty($description) ? $description : null,
                        $path_image
                    ]);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    $errors[] = "Erro no banco de dados ao tentar criar registros.";
                }
            }
        }

        if (!empty($errors)) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $_SESSION['error_message'] = $errors[0];
            $_SESSION['form_data'] = [
                'name' => $name,
                'description' => $description,
            ];

            header("Location: " . BASE_URL . "/dashboard/categories/control");
            exit;
        }

        header("Location: " . BASE_URL . "/dashboard/categories");
    }

    public function edit()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->handleError("Erro ao executar esta ação. Tente novamente.");
        }

        $category_id = $_POST['category_id'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Validação básica
        if (empty($category_id)) $errors[] = "O dado obrigatório 'ID' não foi encontrado";
        if (empty($name)) $errors[] = "O campo 'Nome' é obrigatório";

        // Inicializa variável de imagem
        $path_image = null;

        // Se nova imagem foi enviada, processa
        if (!empty($_FILES['image']['tmp_name'])) {
            $path_old = $this->getOldImagePath($category_id);
            $this->deleteOldImage($path_old);

            $imageController = new ImageController();
            $path_image = $imageController->saveImageCategory($name);

            if (empty($path_image)) {
                $errors[] = "Erro ao salvar nova imagem de ícone. Tente novamente.";
            }
        }

        // Se não há erros, atualiza no banco
        if (empty($errors)) {
            try {
                $this->pdo->beginTransaction();

                $stmt = $this->pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
                $stmt->execute([$name, $description ?: null, $category_id]);

                if ($path_image) {
                    $stmt = $this->pdo->prepare("UPDATE categories SET icon = ? WHERE id = ?");
                    $stmt->execute([$path_image, $category_id]);
                }

                $this->pdo->commit();
            } catch (PDOException $e) {
                $this->pdo->rollBack();
                $this->handleError("Erro no banco de dados: " . $e->getMessage(), $name, $description, $category_id);
            }
        } else {
            $this->handleError($errors[0], $name, $description, $category_id);
        }

        // Sucesso
        header("Location: " . BASE_URL . "/dashboard/categories");
        exit;
    }

    private function getOldImagePath($category_id)
    {
        $stmt = $this->pdo->prepare("SELECT icon FROM categories WHERE id = ?");
        $stmt->execute([$category_id]);
        return $stmt->fetchColumn();
    }

    private function deleteOldImage($relative_path)
    {
        if (!$relative_path) return;

        $full_path = __DIR__ . '/../../public' . $relative_path;
        if (file_exists($full_path)) {
            unlink($full_path);
        }
    }

    private function handleError($message, $name = '', $description = '', $category_id = '')
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();

        $_SESSION['error_message'] = $message;
        $_SESSION['form_data'] = compact('name', 'description');

        $query = $category_id ? "?category_id=$category_id" : "";
        header("Location: " . BASE_URL . "/dashboard/categories/control" . $query);
        exit;
    }


    public function delete()
    {
        $error = "";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $category_id = $_POST['category_id'];

            if (empty($category_id)) {
                $error = "Erro ao deletar registro.";
            } else {
                try {
                    $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
                    $stmt->execute([$category_id]);
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

        header("Location: " . BASE_URL . "/dashboard/categories");
    }

    public function getProductsCountByCategory()
    {
        try {
            $stmt = $this->pdo->query("SELECT c.name AS cat_name, COUNT(p.id) as total_products FROM categories c LEFT JOIN products p ON p.category_id = c.id GROUP BY c.id, c.name ORDER BY total_products DESC LIMIT 5");
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Lide com o erro ou retorne um array vazio
            return [];
        }
    }


    public function countProductsByCategory()
    {
        header('Content-Type: application/json');
        echo json_encode($this->getProductsCountByCategory());
    }
}
