<?php

return new class {
    public function up(PDO $pdo)
    {
        $pdo->query(" CREATE TABLE IF NOT EXISTS payments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                payment_method INT NOT NULL,
                value INT NOT NULL,
                stats VARCHAR(25) NOT NULL,
                payment_date DATETIME NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
            )");
    }

    public function down(PDO $pdo)
    {
        $pdo->query("DROP TABLE IF EXISTS payments");
    }
};
