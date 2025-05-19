<style>
    main {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1.25rem;
    }
</style>

<main>

    <h2>Faça suas compras</h2>
    <div style="display: flex; gap: 1rem">
        <a href="/sistema_produtos/users/register" class="btn btn-main">Register</a>
        <a href="/sistema_produtos/users/login" class="btn btn-sec">Login</a>
    </div>

    <style>
        .container {
            /* width: calc(100vw - 2rem); */
            width: 100%;
            margin: 10px 25px;
            padding: 5px;
            display: grid;
            gap: 1rem;
        }

        .container-card {
            overflow-x: auto;
            padding: 10px 15px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 1rem;
        }

        .container-card::-webkit-scrollbar,
        .container-card::-webkit-scrollbar-thumb {
            border-radius: 5px;
            height: 5px;
        }

        .container-card::-webkit-scrollbar-thumb {
            background-color: rgb(var(--color-sec));
        }

        .container-card .card {
            display: flex;
            width: 200px;
            height: calc((100vh / 4) + 75px);
            flex-shrink: 0;
            flex-direction: column;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(var(--color-main), .25);
            color: black;
            cursor: pointer;
        }

        .container-card .card .image {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: calc(70% - 10px);
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            overflow: hidden;
        }

        .container-card .card .image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .container-card .card .items {
            display: grid;
            gap: .5rem;
            padding: 10px 15px;
            font-size: 13pt;
        }

        .container-card .card .items .item-name {
            font-weight: 500;
        }

        .container-card .card .items .item-price {
            font-size: 11pt;
            color: rgb(var(--color-main));
        }
    </style>

    <?php if (isset($groupedProducts) && !empty($groupedProducts)): ?>
        <?php foreach ($groupedProducts as $key => $products): ?>
            <section class="container">
                <h2 class="title"> <?= $key ?> </h2>
                <div class="container-card">
                    <?php foreach ($products as $key => $prod): ?>
                        <a href="<?= BASE_URL . '/view/product?id=' . $prod['id'] ?>" style="text-decoration: none;">
                            <div class="card">
                                <div class="image">
                                    <img src="<?= PUBLIC_BASE_URL . $prod['image_path'] ?? PUBLIC_BASE_URL . "/assets/default_image_product.jpeg"; ?>" onerror="this.onerror=null;this.src='<?= PUBLIC_BASE_URL ?>/assets/default_image_product.jpeg';" alt="Imagem do produto">
                                </div>
                                <div class="items">
                                    <span class="item-name"><?= $prod['name'] ?></span>
                                    <?php
                                    if ($prod['promotion'] != 0):
                                    ?>
                                        <div style="display: flex; justify-content: space-between;">
                                            <span class="item-price" style="text-decoration: line-through;">R$ <?= number_format($prod['price'] / 100, 2, ',', '.'); ?></span>
                                            <span class="item-price"><?= number_format($prod['promotion'], 2, ',', '.'); ?> % </span>
                                        </div>
                                    <?php
                                    endif;
                                    ?>
                                    <span class="item-price" style="">R$ <?= number_format($prod['promotional_price'] / 100, 2, ',', '.'); ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>

</main>