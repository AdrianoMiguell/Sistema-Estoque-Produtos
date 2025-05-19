<?php
function renderView(string $viewPath, array $data = [])
{
    // Extrai as variáveis (como compact() no Laravel)
    extract($data);

    // Armazena o conteúdo da view (output buffer)
    ob_start();

    $path_type_user = isAdmin() ? 'admin/' : '';
    $full_path = __DIR__ . '/pages/' . $path_type_user . $viewPath . '.php';
    $normal_full_path = __DIR__ . '/pages/' . $viewPath . '.php';

    if (file_exists($full_path)) {
        require $full_path;
    } elseif (file_exists($normal_full_path)) {
        require $normal_full_path;
    } else {
        error404Page();
        return;
    }

    $content = ob_get_clean();

    // Insere no layout
    require __DIR__ . '/layouts/main.php';
}
