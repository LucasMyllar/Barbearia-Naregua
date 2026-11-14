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

$nome = trim($_POST['nome'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');

if ($nome === '' || mb_strlen($nome) > 100) {
    responder(400, ['erro' => 'Informe seu nome (até 100 caracteres).']);
}
if ($telefone !== '' && !preg_match('/^[0-9()+\-\s]{8,20}$/', $telefone)) {
    responder(400, ['erro' => 'Informe um telefone válido (até 20 caracteres).']);
}

try {
    $stmt = $pdo->prepare('SELECT app_privado.atualizar_perfil(CAST(:id AS integer), CAST(:nome AS text), CAST(:tel AS text))');
    $stmt->execute([
        ':id'   => (int) $_SESSION['id_usuario'],
        ':nome' => $nome,
        ':tel'  => $telefone === '' ? null : $telefone,
    ]);
    $ok = in_array($stmt->fetchColumn(), [true, 't', 1, '1'], true);
} catch (PDOException $e) {
    error_log($e->getMessage());
    responder(500, ['erro' => 'Não foi possível salvar seus dados.']);
}

if (!$ok) {
    responder(404, ['erro' => 'Perfil não encontrado.']);
}

$_SESSION['nome'] = $nome;

responder(200, ['ok' => true]);