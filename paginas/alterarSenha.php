<?php
require_once __DIR__ . '/../includes/auth.php';
exigirLogin();

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

function responder(int $codigo, array $corpo): void
{
    http_response_code($codigo);
    echo json_encode($corpo);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(405, ['erro' => 'Método não permitido.']);
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    responder(403, ['erro' => 'Requisição inválida. Recarregue a página e tente novamente.']);
}

$atual = $_POST['atual'] ?? '';
$nova = $_POST['nova'] ?? '';
$confirmar = $_POST['confirmar'] ?? '';

if ($atual === '' || $nova === '' || $confirmar === '') {
    responder(400, ['erro' => 'Preencha todos os campos.']);
}
if (mb_strlen($nova) < 8) {
    responder(400, ['erro' => 'A nova senha deve ter no mínimo 8 caracteres.']);
}
if ($nova !== $confirmar) {
    responder(400, ['erro' => 'A confirmação não é igual à nova senha.']);
}
if ($nova === $atual) {
    responder(400, ['erro' => 'A nova senha deve ser diferente da atual.']);
}

try {
    $idUsuario = (int) $_SESSION['id_usuario'];

    $stmt = $pdo->prepare('SELECT email FROM app_privado.meu_perfil(CAST(:id AS integer))');
    $stmt->execute([':id' => $idUsuario]);
    $email = $stmt->fetchColumn();

    $stmt = $pdo->prepare('SELECT * FROM app_privado.buscar_usuario_login(:email)');
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    if (!$usuario || !password_verify($atual, $usuario['senha'])) {
        responder(400, ['erro' => 'Senha atual incorreta.']);
    }

    $stmt = $pdo->prepare('SELECT app_privado.alterar_senha(CAST(:id AS integer), CAST(:hash AS text))');
    $stmt->execute([
        ':id'   => $idUsuario,
        ':hash' => password_hash($nova, PASSWORD_DEFAULT),
    ]);
    $ok = in_array($stmt->fetchColumn(), [true, 't', 1, '1'], true);
} catch (PDOException $e) {
    error_log($e->getMessage());
    responder(500, ['erro' => 'Não foi possível alterar a senha.']);
}

if (!$ok) {
    responder(500, ['erro' => 'Não foi possível alterar a senha.']);
}

session_regenerate_id(true);

responder(200, ['ok' => true]);