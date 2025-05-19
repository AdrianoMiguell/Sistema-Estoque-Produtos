<?php

return new class {
    public function up(PDO $pdo)
    {
        $pdo->query("CREATE TABLE IF NOT EXISTS carts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                status VARCHAR(25) DEFAULT 'ativo',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");
    }

    public function down(PDO $pdo)
    {
        $pdo->query("DROP TABLE IF EXISTS carts");
    }
};
