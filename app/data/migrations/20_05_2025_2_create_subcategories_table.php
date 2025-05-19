<?php

return new class {
    public function up(PDO $pdo)
    {
        $pdo->query("CREATE TABLE IF NOT EXISTS subcategories (
            id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            description VARCHAR(500), 
            icon TEXT NOT NULL,
            category_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_category_id FOREIGN KEY (category_id) REFERENCES categories(id)
        )");
    }

    public function down(PDO $pdo)
    {
        $pdo->query("DROP TABLE IF EXISTS subcategories");
    }
};
