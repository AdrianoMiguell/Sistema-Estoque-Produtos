<?php
require_once __DIR__ . '/../../helpers/view_helpers.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$formData = $_SESSION['form_data'] ?? [];

unset($_SESSION['form_data']);

pushCss(PUBLIC_BASE_URL . '/css/forms.css');
?>

<form action="<?php echo BASE_URL . '/users/login' ?>" method="post">
    <legend>Entrar</legend>
    <div>
        <span class="error_message"><?php $_GET['error_email'] ?? '' ?></span>
        <label class="label-form" for="email">Email</label>
        <input class="input-form" type="text" name="email" id="email" value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
    </div>
    <div>
        <span class="error_message"><?php $_GET['error_password'] ?? '' ?></span>
        <label class="label-form" for="password">Senha</label>
        <input class="input-form" type="password" name="password" id="password">
    </div>
    <div style="display: flex; justify-content: center;">
        <button class="btn btn-main">Entrar</button>
    </div>
</form>