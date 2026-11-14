<?php

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']),
    ]);
    session_start();
}

function exigirLogin(): void
{
    if (empty($_SESSION['id_usuario'])) {
        header('Location: login.php');
        exit;
    }
}

function exigirAdmin(): void
{
    exigirLogin();

    if ($_SESSION['tipo_usuario'] !== 'ADM') {
        http_response_code(403);
        exit('Acesso negado.');
    }
}