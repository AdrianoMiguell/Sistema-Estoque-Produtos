<?php
require_once __DIR__ . '/../../../helpers/view_helpers.php';

if (!isset($category)) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $category = $_SESSION['form_data'] ?? [];
}

unset($_SESSION['form_data']);

pushCss(PUBLIC_BASE_URL . '/css/forms.css');
pushCss(PUBLIC_BASE_URL . '/css/dashboard.css');

?>

<section class="section-dashboard">

    <form action="<?php echo !empty($category) ? BASE_URL . '/dashboard/categories/edit' : BASE_URL . '/dashboard/categories/store' ?>" method="post" style="margin: 1rem 0;" enctype="multipart/form-data">
        <?php if (!empty($category)): ?>
            <input type="hidden" name="category_id" value="<?= htmlspecialchars($category['id'] ?? '') ?>">
        <?php endif; ?>

        <legend> <?= !empty($category) ? 'Editar' : 'Registrar'; ?> Categoria</legend>

        <div>
            <label class="label-form" for="name">Nome *</label>
            <input class="input-form" type="text" name="name" id="name" value="<?= htmlspecialchars($category['name'] ?? '') ?>" maxlength="149" required>
        </div>

        <div>
            <label class="label-form" for="description">Descrição </label>
            <input class="input-form" type="text" name="description" id="description" value="<?= htmlspecialchars($category['description'] ?? '') ?>" maxlength="499">
        </div>

        <div>
            <label class="label-form" for="image"><?= !empty($category['id']) ? 'Nova ' : '' ?> Imagem </label>
            <input class="input-form" type="file" name="image" id="image" <?php if (empty($category['id'])): ?> required <?php endif; ?>>
        </div>

        <div style="display: flex; justify-content: center; margin-top: 1rem;">
            <button class="btn btn-main">
                <?=
                !empty($category) ? "Editar" : "Cadastrar"
                ?>
            </button>
        </div>
    </form>

    <?php if (!empty($category['id'])): ?>
        <div class="header-section">
            <h1>Subcategorias</h1>
            <div class="header-options">
                <a class="btn btn-icon btn-sec" style="--size-btn: 35px;" href="<?= BASE_URL ?>/dashboard/subcategories/control?category_id=<?= $category['id'] ?>">
                    <i class="bi bi-plus"></i>
                </a>
            </div>
        </div>
        <?php if (!empty($subcategories)): ?>
            <div class="container-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subcategories as $subcategory): ?>
                            <tr>
                                <th><?= htmlspecialchars($subcategory['id']) ?></th>
                                <td><?= htmlspecialchars($subcategory['name'] ?? '-') ?></td>
                                <td style="min-width: 200px;"><?= htmlspecialchars($subcategory['description'] ?? '-') ?></td>
                                <td>
                                    <div style="display: flex; justify-content: center; align-items:center; gap: .5rem; ">
                                        <a class="btn btn-icon btn-sec" style="--size-btn: 25px;" href="<?= BASE_URL ?>/dashboard/subcategories/control?subcategory_id=<?= $subcategory['id'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= BASE_URL ?>/dashboard/subcategories/delete" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar esse registro?');">
                                            <input type="hidden" name="subcategory_id" value="<?= $subcategory['id'] ?>">
                                            <button class="btn btn-icon btn-danger" style="--size-btn: 25px;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p style="text-align: center;">Nenhuma subcategoria encontrada.</p>
        <?php endif; ?>
    <?php endif; ?>


</section>