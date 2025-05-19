<?php
require_once __DIR__ . '/../routes/routes.php';

function error404Page()
{
    http_response_code(404);
    exit;
}

function dispatch($handler)
{

    if (is_callable($handler)) {
        return $handler();
    }

    if (is_array($handler) && count($handler) === 2) {
        [$controller, $method] = $handler;

        if (!class_exists($controller)) {
            $controllerPath = str_replace('App\\Controllers\\', '', $controller); // Remove namespace
            $controllerPath = strtolower(str_replace('\\', '/', $controllerPath)); // Converte para path e lowercase

            // Separa em partes
            $parts = explode('/', $controllerPath);
            $last = array_pop($parts); // Ex: productcontroller

            // Adiciona "_" antes de "controller"
            $last = preg_replace('/controller$/', '_controller', $last);

            // Junta o caminho final
            $finalPath = implode('/', $parts) . '/' . $last;
            $fullPath = __DIR__ . '/../controllers/' . $finalPath . '.php';

            if (file_exists($fullPath)) {
                require_once $fullPath;
            } else {
                var_dump("Caminho não encontrado");
                throw new Exception("Controller não encontrado: $fullPath");
            }
        }

        $instance = new $controller();

        if (!method_exists($instance, $method)) {
            throw new Exception("Método {$method} não existe no controller {$controller}");
        }

        return $instance->$method();
    }

    error404Page();
}

$basePath = '/sistema_produtos';

// Captura a URI acessada
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove a basePath da URI (caso tenha)
$cleanUri = str_replace($basePath, '', $uri);
$baseUrl = '/' . trim($cleanUri, '/');

// Método HTTP atual
$method = $_SERVER['REQUEST_METHOD'];

// Define as rotas
$routes = [
    'GET' => defineGetRoutes(),
    'POST' => definePostRoutes(),
];

// Caminho completo do arquivo requisitado
$requestedFile = __DIR__ . '/../../' . $baseUrl;

// Se for um arquivo real (ex: CSS, JS), o PHP não deve lidar com ele
if (file_exists($requestedFile) && is_file($requestedFile)) {
    error404Page();
}

// Checagem com middleware
if (isset($routes[$method][$baseUrl])) {
    $routeHit = $routes[$method][$baseUrl];
} else if (isset($routes[$method]['is_client'][$baseUrl])) {
    if (isLogged()) {
        $routeHit = $routes[$method]['is_client'][$baseUrl];
    } else {
        header('Location: ' . BASE_URL . '/users/login');
        exit;
    }
} else if (isset($routes[$method]['is_admin'][$baseUrl])) {
    if (isAdmin()) {
        $routeHit = $routes[$method]['is_admin'][$baseUrl];
    } else {
        if (!isLogged()) {
            header('Location: ' . BASE_URL . '/users/login');
            exit;
        }
    }
}


if (isset($routeHit) && $routeHit !== null) {
    dispatch($routeHit);
} else {
    error404Page();
}
