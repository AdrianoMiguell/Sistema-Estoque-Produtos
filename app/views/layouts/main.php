<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$errorMessage = $_SESSION['error_message'] ?? null;

unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de html</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja Online</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Birthstone&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Simonetta:ital,wght@0,400;0,900;1,400;1,900&display=swap" rel="stylesheet">

    <!-- font-family: "Birthstone", cursive; -->

    <!-- font-family: "Simonetta", serif; -->

    <link rel="stylesheet" href="<?php echo PUBLIC_BASE_URL; ?>/css/main.css">
    <link rel="stylesheet" href="<?php echo PUBLIC_BASE_URL; ?>/css/components.css">

    <?php if (isset($globalCssExtras)): ?>
        <?php foreach (getCssExtras() as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>

</head>

<body>
    <div id="loading-spinner">
        <div class="spinner"></div>
    </div>

    <?php include_once __DIR__ . '/../components/navbar.php'; ?>

    <main>
        <?= $content ?? '<p>Nenhum conteúdo carregado</p>' ?>
    </main>

    <?php
    if (!empty($errorMessage)) {
        echo " <div class='alert alert-danger'> $errorMessage </div>";
    }
    ?>
</body>

<script>
    window.addEventListener("load", () => {
        const loader = document.getElementById("loading-spinner");
        if (loader) {
            loader.style.display = "none";
        }
    });
</script>

<?php if (isset($globalJsExtras)): ?>
    <?php foreach (getJsExtras() as $js): ?>
        <script src="<?= $js ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</html>