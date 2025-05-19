<?php
function isAdmin(): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $type = $_SESSION['user_data']['type'] ?? '';

    return !empty($type) && $_SESSION['user_data']['type'] == '1';
}
