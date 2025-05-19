<?php

return new class {
    public function up(PDO $pdo)
    {
        $pdo->query("CREATE TABLE IF NOT EXISTS users(
                id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                birthdate DATE NOT NULL,
                phone VARCHAR(11),
                type ENUM('0', '1') DEFAULT '0',
                password TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);");
    }

    public function down(PDO $pdo)
    {
        $pdo->query("DROP TABLE IF EXISTS users");
    }
};
