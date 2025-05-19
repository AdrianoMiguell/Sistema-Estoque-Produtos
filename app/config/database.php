<?php

function getPDO(): PDO
{
    static $pdo;

    if (!$pdo) {
        $host = 'localhost';
        $dbname = 'store_system';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Erro de conexão: " . $e->getMessage();
            http_response_code(404);
            exit;
        }
    }

    return $pdo;
}
