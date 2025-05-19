<?php

return new class {
    public function up(PDO $pdo)
    {
        $pdo->query("CREATE TABLE IF NOT EXISTS product_images (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                image_path TEXT NOT NULL,
                alt_image VARCHAR(200),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            )");
    }

    public function down(PDO $pdo)
    {
        $pdo->query("DROP TABLE IF EXISTS products");
    }
};
