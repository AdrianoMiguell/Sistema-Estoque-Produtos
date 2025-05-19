<?php

return new class {
    public function up(PDO $pdo)
    {
        $pdo->query("CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            description VARCHAR(500), 
            icon TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }

    public function down(PDO $pdo)
    {
        $pdo->query("DROP TABLE IF EXISTS categories");
    }
};
