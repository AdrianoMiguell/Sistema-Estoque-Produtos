<?php
require_once __DIR__ . '/../../helpers/view_helpers.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$formData = $_SESSION['form_data'] ?? [];

unset($_SESSION['form_data']);

pushCss(PUBLIC_BASE_URL . '/css/forms.css');
?>

<form action="<?php echo BASE_URL . '/users/register' ?>" method="post" style="margin: 1rem; padding: 1.5rem;">
    <legend>Cadastra-se</legend>
    <div>
        <label class="label-form" for="name">Nome</label>
        <input class="input-form" type="text" name="name" id="name" value="<?php echo htmlspecialchars($formData['name'] ?? '') ?>">
    </div>
    <div>
        <label class="label-form" for="email">Email</label>
        <input class="input-form" type="text" name="email" id="email" value="<?php echo htmlspecialchars($formData['email'] ?? '') ?>">
    </div>

    <div style="display: flex; gap: 1rem;">
        <div>
            <label class="label-form" for="birthdate">Data de Nascimento</label>
            <input class="input-form" type="date" name="birthdate" id="birthdate" value="<?php echo htmlspecialchars($formData['birthdate'] ?? '') ?>">
        </div>
        <div>
            <label class="label-form" for="phone">Telefone</label>
            <input class="input-form" type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($formData['phone'] ?? '') ?>">
        </div>
    </div>

    <div style=" display: flex; gap: 1rem;">
        <div>
            <label class="label-form" for="password">Senha</label>
            <input class="input-form" type="password" name="password" id="password">
        </div>
        <div>
            <label class="label-form" for="confirm_password">Confirmar Senha</label>
            <input class="input-form" type="password" name="confirm_password" id="confirm_password">
        </div>
    </div>

    <div style="display: flex; justify-content: center; margin-top: 1rem;">
        <button class="btn btn-main">Cadastrar</button>
    </div>

</form>