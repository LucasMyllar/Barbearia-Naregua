<?php

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

function exigirLogin(): void {
    if(empty($_SESSION['id_usuario'])) {
        header('Location: login.php');
        exit;
    }
}

function exigirAdmin(): void {
    exigirLogin();

    if($_SESSION['tipo_usuario'] !== 'ADM') {
        http_response_code(403);
        exit('Acesso negado.');
    }
}