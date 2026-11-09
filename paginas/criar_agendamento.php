<?php
require_once __DIR__ . '/../includes/auth.php';
exigirLogin();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/agenda.php';

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

// 1. Token CSRF
$tokenEnviado = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'] ?? '', $tokenEnviado)) {
    responder(403, ['erro' => 'Requisição inválida. Recarregue a página e tente novamente.']);
}

// 2. Data e hora
$data = $_POST['data'] ?? '';
$hora = $_POST['hora'] ?? '';
$dataObj = DateTime::createFromFormat('!Y-m-d', $data);

if (!$dataObj || $dataObj->format('Y-m-d') !== $data) {
    responder(400, ['erro' => 'Data inválida.']);
}
if (!preg_match('/^\d{2}:\d{2}$/', $hora)) {
    responder(400, ['erro' => 'Horário inválido.']);
}

// 3. Ids dos serviços
$ids = array_unique(array_filter(array_map('intval', explode(',', $_POST['servicos'] ?? ''))));

if (count($ids) === 0 || count($ids) > 10) {
    responder(400, ['erro' => 'Escolha ao menos um serviço.']);
}

// 4. Duração lida do BANCO
$stmt = $pdo->prepare(
    'SELECT COUNT(*) AS qtd, COALESCE(SUM(duracao_minutos), 0) AS total
     FROM servico
     WHERE ativo = true AND id_servico = ANY(CAST(:ids AS integer[]))'
);
$stmt->execute([':ids' => '{' . implode(',', $ids) . '}']);
$res = $stmt->fetch();

if ((int) $res['qtd'] !== count($ids)) {
    responder(400, ['erro' => 'Serviço inválido.']);
}

$duracao = (int) $res['total'];

// 5. O horário pedido realmente está livre?
if (!in_array($hora, horariosLivres($pdo, $dataObj, $duracao), true)) {
    responder(409, ['erro' => 'Esse horário não está mais disponível.']);
}

// 6. Gravar
try {
    $stmt = $pdo->prepare('SELECT app_privado.criar_agendamento(CAST(:cliente AS integer), CAST(:inicio AS timestamp), CAST(:servicos AS integer[]))');
    $stmt->execute([
        ':cliente'  => (int) $_SESSION['id_usuario'],
        ':inicio'   => $data . ' ' . $hora . ':00',
        ':servicos' => '{' . implode(',', $ids) . '}',
    ]);
    $idAgendamento = (int) $stmt->fetchColumn();
} catch (PDOException $e) {
    if ($e->getCode() === '23P01') {
        responder(409, ['erro' => 'Esse horário acabou de ser ocupado por outra pessoa.']);
    }
    error_log($e->getMessage());
    responder(500, ['erro' => 'Não foi possível concluir o agendamento.']);
}

responder(201, ['id' => $idAgendamento]);