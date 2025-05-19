<?php
require_once __DIR__ . '/../../../helpers/view_helpers.php';

if (!isset($subcategory) || empty($subcategory)) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $subcategory = $_SESSION['form_data'] ?? [];
} else {
    $category_id = $subcategory['category_id'];
}

unset($_SESSION['form_data']);

pushCss(PUBLIC_BASE_URL . '/css/forms.css');
pushJs(PUBLIC_BASE_URL . '/js/get_subcategory_options.js');

?>

<section class="section-dashboard">
    <form action="<?php echo !empty($subcategory) ? BASE_URL . '/dashboard/subcategories/edit' : BASE_URL . '/dashboard/subcategories/store' ?>" method="post" style="margin: 1rem 0;" enctype="multipart/form-data">

        <?php if (!empty($subcategory)): ?>
            <input type="hidden" name="subcategory_id" value="<?= htmlspecialchars($subcategory['id'] ?? '') ?>">
        <?php endif; ?>

        <legend> <?= !empty($subcategory) ? 'Editar' : 'Registrar'; ?> Subategoria</legend>

        <div style="display: flex; gap: 1rem;">
            <div style="flex: .8;">
                <label class="label-form" for="name">Nome *</label>
                <input class="input-form" type="text" name="name" id="name" value="<?= htmlspecialchars($subcategory['name'] ?? '') ?>" maxlength="149" required>
            </div>
            <div style="flex: .2;">
                <style>
                    select {
                        margin: 5px 0;
                        padding: 5px;
                        border-radius: 5px;
                        border: 1px solid rgb(109, 109, 109);
                    }
                </style>

                <label class="label-form" for="category_id">Categoria *</label>
                <select name="category_id" id="category_id">
                    <?php if (count($categories) > 0): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id'] ?? '') ?>" <?php if (isset($category_id) && $category['id'] == $category_id): ?> selected <?php endif; ?>> <?= $category['name'] ?> </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">Nenhuma Categoria</option>
                    <?php endif; ?>
                </select>

            </div>
        </div>

        <div>
            <label class="label-form" for="description">Descrição </label>
            <input class="input-form" type="text" name="description" id="description" value="<?= htmlspecialchars($subcategory['description'] ?? '') ?>" maxlength="499">
        </div>

        <div style="display: flex; justify-content: center; margin-top: 1rem;">
            <button class="btn btn-main">
                <?=
                !empty($subcategory) ? "Editar" : "Cadastrar"
                ?>
            </button>
        </div>
    </form>
</section>