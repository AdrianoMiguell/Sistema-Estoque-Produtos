<?php
// Inicia sessão
session_start();

define('BASE_URL', '/sistema_produtos');
define('PUBLIC_BASE_URL', '/sistema_produtos/app/public');
define('ASSET_BASE_URL', '/sistema_produtos/app/public/uploads');
// app\public\uploads\products

// Redireciona tudo pro sistema de rotas
require_once __DIR__ . '/app/util/verify_is_logged.php';
require_once __DIR__ . '/app/util/verify_is_admin.php';
require_once __DIR__ . '/app/routes/url_config.php';
