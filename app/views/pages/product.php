<?php

$image_path = !empty($product_image['image_path']) ? PUBLIC_BASE_URL . $product_image['image_path'] : PUBLIC_BASE_URL . "/../public/assets/default_image_product.jpeg" ?>

<style>
    .section-product {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .section-product .image {
        max-width: 400px;
        flex: 1 1 calc(30% - 20px);
    }

    .section-product .image img {
        width: 100%;
        height: auto;
        object-fit: contain;
    }

    .section-product .info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        flex: 1 1 calc(40% - 20px);
    }

    /* .section-product .items {
        display: grid;
        gap: .5rem;
        padding: 10px 15px;
        font-size: 13pt;
    }

    .section-product .items .item-name {
        font-weight: 500;
    }

    */
    .section-product .items .item-price {
        font-size: 11pt;
        color: rgb(var(--color-main));
    }

    .section-product .items .item-button {}
</style>


<section class="section-product">
    <div class="image">
        <img src="<?= $image_path ?? PUBLIC_BASE_URL . "/assets\default_image_product.jpeg" ?>" onerror="this.onerror=null;this.src='<?= PUBLIC_BASE_URL ?>/assets\default_image_product.jpeg';" alt="Imagem do produto">
    </div>
    <div class="info">
        <div style="display: flex; justify-content: space-between;">
            <span class="item-name"><?= $product['name'] ?></span>
            <button class="item-button btn" style="background-color: transparent;">
                <i class="bi bi-cart2"></i>
            </button>
        </div>
        <?php
        if ($product['promotion'] != 0):
        ?>
            <div style="display: flex; justify-content: space-between;">
                <span class="item-price" style="text-decoration: line-through;">R$ <?= number_format($product['price'] / 100, 2, ',', '.'); ?></span>
                <span class="item-price"><?= number_format($product['promotion'], 2, ',', '.'); ?> % </span>
            </div>
        <?php
        endif;
        ?>
        <span class="item-price" style="">R$ <?= number_format($product['promotional_price'] / 100, 2, ',', '.'); ?></span>
        <button class="item-button btn btn-main">
            Comprar
        </button>
    </div>
</section>
<!-- 
<a href="<?= BASE_URL . '/view/product?id=' . $product['id'] ?>" style="text-decoration: none;">
    <div class="card">
    </div>
</a> -->