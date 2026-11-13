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

$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) {
    responder(400, ['erro' => 'Agendamento inválido.']);
}

try {
    $stmt = $pdo->prepare('SELECT app_privado.cancelar_agendamento(CAST(:cliente AS integer), CAST(:id AS bigint))');
    $stmt->execute([
        ':cliente' => (int) $_SESSION['id_usuario'],
        ':id'      => $id,
    ]);
    $ok = in_array($stmt->fetchColumn(), [true, 't', 1, '1'], true);
} catch (PDOException $e) {
    error_log($e->getMessage());
    responder(500, ['erro' => 'Não foi possível cancelar.']);
}

if (!$ok) {
    responder(409, ['erro' => 'Não foi possível cancelar. O cancelamento só é permitido até 2 horas antes do horário.']);
}

responder(200, ['ok' => true]);