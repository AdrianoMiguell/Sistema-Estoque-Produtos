<?php

return new class {
    public function up(PDO $pdo)
    {
        $pdo->query(" CREATE TABLE IF NOT EXISTS cart_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                cart_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT DEFAULT 1,
                unit_price INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            ) ");
    }

    public function down(PDO $pdo)
    {
        $pdo->query("DROP TABLE IF EXISTS cart_items");
    }
};
