<?php
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

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
$status = $_POST['status'] ?? '';

if ($id <= 0 || !in_array($status, ['CONFIRMADO', 'CONCLUIDO', 'CANCELADO'], true)) {
    responder(400, ['erro' => 'Pedido inválido.']);
}

try {
    $stmt = $pdo->prepare('SELECT app_privado.admin_alterar_status_agendamento(CAST(:id AS bigint), CAST(:status AS text))');
    $stmt->execute([':id' => $id, ':status' => $status]);
    $ok = in_array($stmt->fetchColumn(), [true, 't', 1, '1'], true);
} catch (PDOException $e) {
    error_log($e->getMessage());
    responder(500, ['erro' => 'Não foi possível alterar o status.']);
}

if (!$ok) {
    responder(409, ['erro' => 'Essa mudança de status não é permitida para este agendamento.']);
}

responder(200, ['ok' => true]);