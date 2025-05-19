<nav>
    <a href="<?php echo BASE_URL ?>/" class="logo">
        <i class="bi bi-shop"></i>
        Shooper
    </a>
    <ul>
        <?php if (isAdmin()): ?>
            <a href="<?php echo BASE_URL ?>/dashboard/products">
                <li>
                    <i class="bi bi-cart-fill"></i>
                    <span> Produtos</span>
                </li>
            </a>
            <a href="<?php echo BASE_URL ?>/dashboard/categories">
                <li>
                    <i class="bi bi-tag-fill"></i>
                    <span> Categorias</span>
                </li>
            </a>
        <?php else: ?>
            <a href="<?php echo BASE_URL ?>/">
                <li>
                    <i class="bi bi-bag-fill"></i>
                    <span> Explorar</span>
                </li>
            </a>
            <a href="./">
                <li>
                    <i class="bi bi-telephone-fill"></i>
                    <span>Fale Conosco</span>
                </li>
            </a>
        <?php endif; ?>

        <?php if (isLogged()): ?>
            <a href="<?php echo BASE_URL; ?>/users/profile">
                <li>
                    <i class="bi bi-person-circle"></i>
                    <span>Conta</span>
                </li>
            </a>
        <?php else: ?>
            <a href="/sistema_produtos/users/login">
                <li>
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Entrar</span>
                </li>
            </a>
            <a href="/sistema_produtos/users/register">
                <li>
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Registrar</span>
                </li>
            </a>
        <?php endif; ?>

    </ul>
</nav>