<?php

function saveImage(string $dirItemId, string $pathMain, $typeItem): string
{
    $relativePath = '';
    $uploadDir = __DIR__ . $pathMain . $dirItemId . '/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }


    if ($typeItem == 'products') {
        $files_inside = glob($uploadDir . '*');

        if (count($files_inside) > 6) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['error_message'] = "Por segurança, é permitido criar até 6 imagens por produto.";
            return false;
        }
    }


    $tmpPath = $_FILES['image']['tmp_name'];

    if (isset($tmpPath)) {
        if (is_uploaded_file($tmpPath)) {
            $originalName = $_FILES['image']['name'];
            $ext = pathinfo($originalName, PATHINFO_EXTENSION);

            // Nome seguro/único
            $newName = uniqid('img_', true) . '.' . $ext;
            $destination = $uploadDir . $newName;

            if (move_uploaded_file($tmpPath, $destination)) {
                $relativePath = '/uploads/' . $typeItem . '/' . $dirItemId . '/' . $newName;
            }
        }
    }

    return $relativePath;
}
