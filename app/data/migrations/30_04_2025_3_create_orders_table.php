<?php

return new class {
    public function up(PDO $pdo)
    {
        $pdo->query(" CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                total INT NOT NULL,
                status VARCHAR(25) NOT NULL,
                order_date DATETIME NOT NULL,
                address VARCHAR(500) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");
    }

    public function down(PDO $pdo)
    {
        $pdo->query("DROP TABLE IF EXISTS orders");
    }
};
