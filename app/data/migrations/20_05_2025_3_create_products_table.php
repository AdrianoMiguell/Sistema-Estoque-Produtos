<?php

return new class {
    public function up(PDO $pdo)
    {
        $pdo->query(" CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                name VARCHAR(200) NOT NULL UNIQUE,
                category_id INT NULL,
                subcategory_id INT NULL,
                price INT NOT NULL,
                rating_average DECIMAL(3,2) DEFAULT 0.00,
                description VARCHAR(500),
                brand VARCHAR(200),
                manufacturer VARCHAR(150),
                size VARCHAR(50),
                quantity INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_products_category_id FOREIGN KEY (category_id) REFERENCES categories(id),
                CONSTRAINT fk_products_subcategory_id FOREIGN KEY (subcategory_id) REFERENCES subcategories(id)
            )");
    }

    public function down(PDO $pdo)
    {
        $pdo->query("DROP TABLE IF EXISTS products");
    }
};
