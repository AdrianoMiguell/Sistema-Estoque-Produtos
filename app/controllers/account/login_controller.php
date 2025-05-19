<?php
require_once __DIR__ . '/../../config/database.php';

$pdo = getPDO();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$errors = [];

ob_clean();

function validateNotNull($value, $name, &$errors)
{
    if (empty($value)) {
        $errors[] = "O campo '$name' é obrigatório.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Valida campos obrigatórios
    validateNotNull($email, "Email", $errors);
    validateNotNull($password, "Senha", $errors);

    // Validação de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email inválido.");
    }

    // Senha mínima
    if (strlen($password) < 6) {
        array_push($errors, "A senha deve ter pelo menos 6 caracteres.");
    }

    try {
        // Verifica se já existe usuário com o email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $value_user = $stmt->fetch();
    } catch (\Throwable $th) {
        throw $th;
        array_push($errors, "Erro interno na conexão com o banco. Tente novamente!");
    }

    if (!$value_user) {
        array_push($errors, "Usuário não encontrado.");
    }

    if (!password_verify($password, $value_user['password'])) {
        array_push($errors, "Usuário não encontrado.");
    }

    // Se existir erros
    if (!empty($errors)) {
        $_SESSION['form_data'] = [
            'email' => $email,
        ];

        $_SESSION['error_message'] = $errors[0];

        header("location: " . BASE_URL . "/users/login");
        exit;
    }

    // Se não houver erros, registra o usuário
    if (empty($errors)) {
        $_SESSION['user_data'] = [
            'is_logged' => true,
            'id' => $value_user['id'],
            'name' => $value_user['name'],
            'email' => $value_user['email'],
            'birthdate' => $value_user['birthdate'],
            'phone' => $value_user['phone'],
            'type' => $value_user['type']
        ];

        header("Location: " . BASE_URL . "/");
        exit;
    }
}
