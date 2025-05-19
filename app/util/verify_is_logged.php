<?php
function isLogged(): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $is_logged = $_SESSION['user_data']['is_logged'] ?? false;

    return isset($is_logged) && $is_logged;
}
