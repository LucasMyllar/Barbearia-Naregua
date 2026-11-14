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
$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$precoTxt = str_replace(',', '.', trim($_POST['preco'] ?? ''));
$duracao = (int) ($_POST['duracao'] ?? 0);

if ($nome === '' || mb_strlen($nome) > 100) {
    responder(400, ['erro' => 'Informe o nome (até 100 caracteres).']);
}
if (mb_strlen($descricao) > 1000) {
    responder(400, ['erro' => 'A descrição deve ter no máximo 1000 caracteres.']);
}
if (!preg_match('/^\d{1,6}(\.\d{1,2})?$/', $precoTxt) || (float) $precoTxt <= 0) {
    responder(400, ['erro' => 'Informe um preço válido, como 45,00.']);
}
if ($duracao < 5 || $duracao > 480) {
    responder(400, ['erro' => 'A duração deve ficar entre 5 e 480 minutos.']);
}

try {
    $stmt = $pdo->prepare(
        'SELECT app_privado.admin_salvar_servico(
            CAST(:id AS integer), CAST(:nome AS text), CAST(:descricao AS text),
            CAST(:preco AS numeric), CAST(:duracao AS integer))'
    );
    $stmt->execute([
        ':id'        => $id > 0 ? $id : null,
        ':nome'      => $nome,
        ':descricao' => $descricao === '' ? null : $descricao,
        ':preco'     => number_format((float) $precoTxt, 2, '.', ''),
        ':duracao'   => $duracao,
    ]);
    $resultado = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log($e->getMessage());
    responder(500, ['erro' => 'Não foi possível salvar o serviço.']);
}

if (!$resultado) {
    responder(404, ['erro' => 'Serviço não encontrado.']);
}

responder(200, ['id' => (int) $resultado]);