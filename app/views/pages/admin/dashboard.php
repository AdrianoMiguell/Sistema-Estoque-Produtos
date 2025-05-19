<?php
require_once __DIR__ . '/../../../helpers/view_helpers.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


$username = explode(" ", $_SESSION['user_data']['name'])[0];

pushCss(PUBLIC_BASE_URL . '/css/dashboard.css');

pushJs(PUBLIC_BASE_URL . '/js/count_product_by_category.js');

// var_dump(CONNECTION_NORMAL);
// exit;

?>

<section class="section-dashboard" style="margin-bottom: 2rem;">
    <h1 style="font-size: 18pt;">
        Bem vindo <?= $username ?>
    </h1>
</section>

<section class="section-dashboard">
    <h2 style="font-size: medium; font-size: 15pt; font-weight: 500;">Produtos lançados recentemente</h2>
    <?php if (!empty($products)): ?>
        <div class="container-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Promoção</th>
                        <th>Quantidade</th>
                        <th>Categoria</th>
                        <th>Subcategoria</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['name'] ?? '-' ?></td>
                            <td style="min-width: 200px;"><?= $product['description'] ?? '-' ?></td>
                            <td> R$ <?= number_format($product['promotional_price'] / 100, 2, ',', '.') ?> </td>
                            <td> <?= number_format($product['promotion'] / 100, 2, ',', '.') ?>% </td>
                            <td><?= $product['quantity'] ?? '0' ?></td>
                            <td><?= $product['category_name'] ?? '-' ?></td>
                            <td><?= $product['subcategory_name'] ?? '-' ?></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div style="text-align: center;">
            <img src="<?= PUBLIC_BASE_URL ?>/assets/make_your_categories.svg" alt="">
            <span>Sem Produtos cadastrados</span>
        </div>
    <?php
    endif;
    ?>
</section>

<section class="section-flex-2">

    <!-- products_by_categories -->

    <section class="section-dashboard">
        <section class="section-dashboard">
            <h2 style="font-size: medium; font-size: 15pt; font-weight: 500;">Categorias mais usadas</h2>
            <?php if (!empty($products_by_categories)): ?>
                <div class="container-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Categoria</th>
                                <?php foreach ($products_by_categories as $cat): ?>
                                    <td><?= $cat['cat_name'] ?? '-' ?></td>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Total</th>
                                <?php foreach ($products_by_categories as $cat): ?>
                                    <td><?= $cat['total_products'] ?? '-' ?></td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="text-align: center;">
                    <img src="<?= PUBLIC_BASE_URL ?>/assets/make_your_categories.svg" alt="">
                    <span>Sem Categorias cadastrados</span>
                </div>
            <?php
            endif;
            ?>
        </section>


        <section class="section-card-dashboard" id="section-products-by-category">
        </section>

    </section>

    <section class="section-dashboard">
        <section class="section-dashboard">
            <h2 style="font-size: medium; font-size: 15pt; font-weight: 500;">Produtos lançados recentemente</h2>
            <?php if (!empty($products)): ?>
                <div class="container-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Preço</th>
                                <th>Quantidade</th>
                                <th>Categoria</th>
                                <th>Subcategoria</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= $product['name'] ?? '-' ?></td>
                                    <td style="min-width: 200px;"><?= $product['description'] ?? '-' ?></td>
                                    <td> R$ <?= number_format($product['price'] / 100, 2, ',', '.') ?> </td>
                                    <td> R$ <?= number_format($product['promotion'] / 100, 2, ',', '.') ?> </td>
                                    <td><?= $product['quantity'] ?? '0' ?></td>
                                    <td><?= $product['category_name'] ?? '-' ?></td>
                                    <td><?= $product['subcategory_name'] ?? '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="text-align: center;">
                    <img src="<?= PUBLIC_BASE_URL ?>/assets/make_your_categories.svg" alt="">
                    <span>Sem Produtos cadastrados</span>
                </div>
            <?php
            endif;
            ?>
        </section>
    </section>

</section>

<!-- 

<section>
    Total de vendas - Receita total
</section> -->
<!-- 
<div>
    <canvas id="myChart"></canvas>
</div> -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>