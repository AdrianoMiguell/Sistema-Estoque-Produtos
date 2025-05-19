<?php
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $request = $_POST['request_logout'] ?? false;

    if (!$request) {
        $_SESSION['error_message'] = "Erro ao executar esta ação.";
        $previousPage = $_SERVER['HTTP_REFERER'] ?? '/';

        header("Location: $previousPage");
        exit;
    }

    unset($_SESSION['user_data']);
    header("Location: " . BASE_URL . '/');
}
