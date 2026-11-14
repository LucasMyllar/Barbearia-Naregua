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
$ativo = ($_POST['ativo'] ?? '') === '1';

if ($id <= 0) {
    responder(400, ['erro' => 'Serviço inválido.']);
}

try {
    $stmt = $pdo->prepare('SELECT app_privado.admin_alterar_ativo_servico(CAST(:id AS integer), CAST(:ativo AS boolean))');
    $stmt->execute([':id' => $id, ':ativo' => $ativo ? 'true' : 'false']);
    $ok = in_array($stmt->fetchColumn(), [true, 't', 1, '1'], true);
} catch (PDOException $e) {
    error_log($e->getMessage());
    responder(500, ['erro' => 'Não foi possível alterar o serviço.']);
}

if (!$ok) {
    responder(404, ['erro' => 'Serviço não encontrado.']);
}

responder(200, ['ok' => true]);