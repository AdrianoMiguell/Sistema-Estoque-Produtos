<?php
require_once __DIR__ . '/../../../helpers/view_helpers.php';
pushCss(PUBLIC_BASE_URL . '/css/dashboard.css');
?>

<section class="section-dashboard">
    <div class="header-section">
        <h1>Categorias</h1>
        <div class="header-options">
            <a class="btn btn-icon btn-sec" style="--size-btn: 35px;" href="<?= BASE_URL ?>/dashboard/categories/control">
                <i class="bi bi-plus"></i>
            </a>
        </div>
    </div>

    <?php if (!empty($categories)): ?>
        <div class="container-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Opções</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <th><?= htmlspecialchars($category['id']) ?></th>
                            <th>
                                <img src="<?= PUBLIC_BASE_URL . $category['icon'] ?>" alt="" height="50px">
                            </th>
                            <td><?= htmlspecialchars($category['name'] ?? '-') ?></td>
                            <td style="min-width: 200px;"><?= htmlspecialchars($category['description'] ?? '-') ?></td>
                            <td>
                                <div style="display: flex; justify-content: center;  gap: .5rem; padding: .5rem;">
                                    <a class="btn btn-icon btn-sec" style="--size-btn: 22px;" href="<?= BASE_URL ?>/dashboard/categories/control?category_id=<?= $category['id'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="<?= BASE_URL ?>/dashboard/categories/delete" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar esse registro?');">
                                        <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
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
        <p style="text-align: center;">Nenhuma categoria encontrada.</p>
    <?php endif; ?>


</section>