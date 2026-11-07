<?php
require_once __DIR__ . '/../includes/auth.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tokenEnviado = $_POST['csrf_token'] ?? '';

    if(hash_equals($_SESSION['csrf_token'] ?? '', $tokenEnviado)) {
        $_SESSION = [];

        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);

        session_destroy();
    }
}

header('Location: login.php');
exit;