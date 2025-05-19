<?php
require_once __DIR__ . '/../../../helpers/view_helpers.php';

pushCss(PUBLIC_BASE_URL . '/css/dashboard.css');
pushCss(PUBLIC_BASE_URL . '/css/forms.css');

?>

<section class="section-dashboard">
    <div class="header-section">
        <h1>Produtos</h1>
        <form action="<?= BASE_URL ?>/dashboard/products" method="get" style="display: flex; align-items: center; padding: 0;">
            <input type="search" name="search" id="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Pesquisar" class="input-form" style="min-width: 100px;">
            <button class="btn" style="background-color: white; padding: 0.35rem 0.6rem;">
                <i class="bi bi-search"></i>
            </button>
            <select class="input-form" name="field_selected" id="field_selected" style="max-width: 100px;">
                <?php foreach ($fields as $key => $fd): ?>
                    <option value="<?= $key ?>" <?= (!empty($field_selected) && $field_selected == $key) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($fd) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <div class="header-options">
            <a class="btn btn-icon btn-sec" style="--size-btn: 35px;" href="<?= BASE_URL ?>/dashboard/products/control">
                <i class="bi bi-plus"></i>
            </a>
            <a class="btn btn-icon btn-sec" style="--size-btn: 25px;" href="<?= BASE_URL ?>/dashboard/categories">
                <i class="bi bi-tag-fill"></i>
            </a>
        </div>
    </div>

    <?php if (!empty($products)): ?>
        <div class="container-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Promoção</th>
                        <th>Quantidade</th>
                        <th>Avaliação</th>
                        <th>Tamanho</th>
                        <th>Marca</th>
                        <th>Fabricante</th>
                        <th>Categoria</th>
                        <th>Subcategoria</th>
                        <th>Opções</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <th><?= htmlspecialchars($product['id']) ?></th>
                            <td><?= htmlspecialchars($product['name'] ?? '-') ?></td>
                            <td style="min-width: 200px;"><?= htmlspecialchars($product['description'] ?? '-') ?></td>
                            <td> R$ <?= number_format($product['price'] / 100, 2, ',', '.');
                                    ?> </td>
                            <td> <?= number_format($product['promotion'], 2, ',', '.');
                                    ?>% </td>
                            <td><?= htmlspecialchars($product['quantity'] ?? '0') ?></td>
                            <td><?= htmlspecialchars($product['rating_average'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($product['size'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($product['brand'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($product['manufacturer'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($product['category_name'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($product['subcategory_name'] ?? '-') ?></td>
                            <td>
                                <div style="display: flex; justify-content: center; align-items: center; gap: .5rem; padding: .5rem;">
                                    <a class="btn btn-icon btn-sec" style="--size-btn: 25px;" href="<?= BASE_URL ?>/dashboard/products/control?product_id=<?= $product['id'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?= BASE_URL ?>/dashboard/products/delete" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar esse registro?');">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

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
        <p style="text-align: center;">Nenhum produto encontrado.</p>
    <?php endif; ?>


</section>