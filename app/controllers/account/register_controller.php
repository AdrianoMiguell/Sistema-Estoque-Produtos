<?php
require __DIR__ . '/../../config/database.php';

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
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Valida campos obrigatórios
    validateNotNull($name, "Nome", $errors);
    validateNotNull($email, "Email", $errors);
    validateNotNull($birthdate, "Data de Nascimento", $errors);
    validateNotNull($password, "Senha", $errors);
    validateNotNull($confirmPassword, "Confirmar Senha", $errors);

    // Validação de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email inválido.");
    }

    // Senha mínima
    if (strlen($password) < 6) {
        array_push($errors, "A senha deve ter pelo menos 6 caracteres.");
    }

    // Confirmação de senha
    if ($password !== $confirmPassword) {
        array_push($errors, "As senhas não coincidem.");
    }

    // Verifica se já existe usuário com o email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        array_push($errors, "Este email já está cadastrado.");
    }

    // Se existir erros
    if (!empty($errors)) {
        $_SESSION['form_data'] = [
            'name' => $name,
            'email' => $email,
            'birthdate' => $birthdate,
            'phone' => $phone
        ];

        $_SESSION['error_message'] = $errors[0];

        header("location: " . BASE_URL . "/users/register");
        exit;
    }

    try {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, birthdate, phone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $birthdate, $phone, $hash]);
    } catch (\Throwable $th) {
        array_push($errors, "Erro interno na conexão com o banco. Tente novamente!");
        throw $th;

        $_SESSION['form_data'] = [
            'name' => $name,
            'email' => $email,
            'birthdate' => $birthdate,
            'phone' => $phone,
        ];

        $_SESSION['error_message'] = $errors[0];

        header("location: " . BASE_URL . "/users/register");
        exit;
    }

    // Se não houver erros, registra o usuário
    if (empty($errors)) {
        header("Location: " . BASE_URL . "/users/login");
        exit;
    }
}
