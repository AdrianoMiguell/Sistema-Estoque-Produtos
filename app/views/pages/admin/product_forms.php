<?php
require_once __DIR__ . '/../../../helpers/view_helpers.php';

if (!isset($product)) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $product = $_SESSION['form_data'] ?? [];
}

unset($_SESSION['form_data']);
pushCss(PUBLIC_BASE_URL . '/css/forms.css');
pushJs(PUBLIC_BASE_URL . '/js/get_subcategory_options.js');

// require_once __DIR__ . '/product_info.php';
?>

<section style="margin: 1rem; padding: 1.5rem;">

    <form action="<?php echo !empty($product) ? BASE_URL . '/dashboard/products/edit' : BASE_URL . '/dashboard/products/store' ?>" method="post" enctype="multipart/form-data">

        <?php if (!empty($product)): ?>
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id'] ?? '') ?>">
        <?php endif; ?>

        <legend> <?= !empty($product) ? 'Editar' : 'Registrar'; ?> Produto</legend>
        <div>
            <label class="label-form" for="name">Nome</label>
            <input class="input-form" type="text" name="name" id="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" maxlength="149" required>
        </div>

        <div style="display: flex; gap: 1rem;">
            <div>
                <label class="label-form" for="category_id">Categoria</label>
                <select class="input-form" name="category_id" id="category_id">
                    <option value="">Selecione</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (!empty($product['category_id']) && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="label-form" for="subcategory_id">SubCategoria</label>
                <select class="input-form" name="subcategory_id" id="subcategory_id">
                    <option value="">Selecione a categoria primeiro</option>
                </select>
            </div>
        </div>

        <div>
            <label class="label-form" for="price">Preço (R$) </label>
            <input class="input-form" type="number" name="price" id="price" value="<?= isset($product['price']) ? number_format($product['price'] / 100, 2, '.', '') : '' ?>" step="0.01" required>
        </div>

        <div>
            <label class="label-form" for="promotion"> Promoção (%) </label>
            <input class="input-form" type="number" name="promotion" id="promotion" value="<?= isset($product['promotion']) ? intval($product['promotion']) : '' ?>" step="1" min="0" max="100">
        </div>

        <div>
            <label class="label-form" for="description">Descrição</label>
            <textarea class="input-form" type="text" name="description" id="description"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
        </div>
        <div>
            <label class="label-form" for="brand">Marca</label>
            <input class="input-form" type="text" name="brand" id="brand" value="<?= htmlspecialchars($product['brand'] ?? '') ?>">
        </div>
        <div>
            <label class="label-form" for="manufacturer">Fabricante</label>
            <input class="input-form" type="text" name="manufacturer" id="manufacturer" value="<?= htmlspecialchars($product['manufacturer'] ?? '') ?>">
        </div>
        <div>
            <label class="label-form" for="size">Descrição de Tamanho </label>
            <input class="input-form" type="text" name="size" id="size" value="<?= htmlspecialchars($product['size'] ?? '') ?>">
        </div>
        <div>
            <label class="label-form" for="quantity">Quantidade em Estoque</label>
            <input class="input-form" type="number" name="quantity" id="quantity" value="<?= htmlspecialchars($product['quantity'] ?? '') ?>">
        </div>

        <div style="display: flex; justify-content: center; margin-top: 1rem;">
            <button class="btn btn-main">
                <?=
                !empty($product) ? "Editar" : "Cadastrar"
                ?>
            </button>
        </div>

    </form>

    <?php if (!empty($product) && isset($product['id'])): ?>
        <div>
            <style>
                .session-image {
                    display: flex;
                    justify-content: flex-start;
                    flex-wrap: wrap;
                    gap: 1rem;
                }

                .session-image .div-image {
                    flex: 1 1 calc(28% - 20px);
                    min-width: 100px;
                    max-width: 380px;
                }

                .session-image .div-image img {
                    width: 100%;
                    height: auto;
                }
            </style>

            <div style="margin: 1rem 0; display: grid; gap: 1rem;">
                <h2 style="text-align: center;"> Imagens cadastradas </h2>

                <form action="<?= BASE_URL ?>/products/save-image" method="post" class="form-image" enctype="multipart/form-data">
                    <label class="label-form" for="image" style="flex: 0.2;"> <i class="bi bi-plus-circle-fill"></i> Imagem do Produto</label>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <input type="hidden" name="product_id" id="product_id" value="<?= htmlspecialchars($product['id'] ?? '') ?>">
                        <input type="hidden" name="product_name" id="product_name" value="<?= htmlspecialchars($product['name'] ?? '') ?>">
                        <input style="flex: 0.7;" class="input-form" type="file" name="image" id="image">
                        <button class="btn btn-main">
                            Enviar
                        </button>
                    </div>
                </form>

                <div class="session-image">
                    <?php if (!empty($product_images)): ?>
                        <?php foreach ($product_images as $image): ?>
                            <div class="div-image">
                                <img src="<?= PUBLIC_BASE_URL . $image['image_path'] ?>" alt="<?= htmlspecialchars($image['alt_image'] ?? '') ?>">
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span style="text-align: center;">Sem imagens salvas do produto</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

</section>