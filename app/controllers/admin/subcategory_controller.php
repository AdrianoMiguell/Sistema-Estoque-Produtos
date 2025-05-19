<?php

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../config/database.php';

use PDOException;

class SubcategoryController
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function control()
    {
        $error = "";
        $categories = [];
        $subcategory = [];
        $subcategory_id = $_GET['subcategory_id'] ?? null;
        $category_id = $_GET['category_id'] ?? null;

        try {
            $stmt = $this->pdo->query("SELECT * FROM categories");
            $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // echo $e->getMessage();
            $error = "Erro ao carregar categorias";
        }

        if (!empty($subcategory_id)) {
            try {
                $stmt = $this->pdo->prepare("SELECT * FROM subcategories WHERE id = ?");
                $stmt->execute([$subcategory_id]);
                $subcategory = $stmt->fetch();
            } catch (\PDOException $e) {
                // echo $e->getMessage();
                $error = "Subcategoria requerida não encontrada";
            } finally {
                $error = empty($subcategory) ? "Subcategoria requerida não encontrada" : "";
            }
        }

        if (!empty($subcategory)) {
            $category_id = $subcategory['category_id'] ?? null;
        }

        if (!empty($error)) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $_SESSION['error_message'] = $error;
        }

        renderView('subcategory_forms', ['category_id' => $category_id, 'categories' => $categories, 'subcategory' => $subcategory]);
    }

    public function store()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? null;
            $category_id = $_POST['category_id'] ?? '';

            if (empty($name)) $errors[] = "O campo 'Nome' é obrigatório";
            if (empty($category_id)) $errors[] = "O campo obrigatório de categoria não foi encontrado";

            if (empty($errors)) {
                try {
                    $stmt = $this->pdo->prepare("INSERT INTO subcategories (name,  description, category_id) VALUES (?, ?, ?)");
                    $stmt->execute([
                        $name,
                        !empty($description) ? $description : null,
                        $category_id
                    ]);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    $errors[] = "Erro no banco de dados ao tentar criar registros.";
                }
            }
        }

        $get_var_final = !empty($category_id) ? "?category_id=$category_id" : "";

        if (!empty($errors)) {
            var_dump([
                'name' => $name,
                'description' => $description,
                'category_id' => $category_id,
            ]);
            var_dump($errors);
            exit;

            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $_SESSION['error_message'] = $errors[0];
            $_SESSION['form_data'] = [
                'name' => $name,
                'description' => $description,
                'category_id' => $category_id,
            ];

            header("Location: " . BASE_URL . "/dashboard/subcategories/control" . $get_var_final);
            exit;
        }

        header("Location: " . BASE_URL . "/dashboard/categories/control" . $get_var_final);
    }

    public function edit()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? null;
            $category_id = $_POST['category_id'] ?? '';
            $subcategory_id = $_POST['subcategory_id'] ?? '';

            if (empty($name)) $errors[] = "O campo 'Nome' é obrigatório";
            if (empty($category_id)) $errors[] = "O campo obrigatório de categoria não foi encontrado";
            if (empty($subcategory_id)) $errors[] = "O campo obrigatório de subcategoria não foi encontrado";

            if (empty($errors)) {
                try {
                    $stmt = $this->pdo->prepare("UPDATE subcategories SET name = ?,  description = ?, category_id = ? WHERE id = ?");
                    $stmt->execute([
                        $name,
                        !empty($description) ? $description : null,
                        $category_id,
                        $subcategory_id,
                    ]);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    $errors[] = "Erro no banco de dados ao tentar criar registros.";
                }
            }
        }

        $get_var_final = !empty($category_id) ? "?category_id=$category_id" : "";

        if (!empty($errors)) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $_SESSION['error_message'] = $errors[0];
            $_SESSION['form_data'] = [
                'name' => $name,
                'description' => $description,
                'category_id' => $category_id,
                'subcategory_id' => $subcategory_id,
            ];

            header("Location: " . BASE_URL . "/dashboard/subcategories/control" . $get_var_final);
            exit;
        }

        header("Location: " . BASE_URL . "/dashboard/categories/control" . $get_var_final);
    }

    public function delete()
    {
        $error = "";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $subcategory_id = $_POST['subcategory_id'];

            if (empty($subcategory_id)) {
                $error = "Erro ao deletar registro.";
            } else {
                try {
                    $stmt = $this->pdo->prepare("DELETE FROM subcategories WHERE id = ?");
                    $stmt->execute([$subcategory_id]);
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

        header("Location: " . BASE_URL . "/dashboard/subcategories");
    }
}
