<?php

require_once __DIR__ . '/../includes/auth.php';
exigirLogin();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/agenda.php';

header('Content-Type: application/json; charset=utf-8');

function responderErro(string $mensagem): void
{
    http_response_code(400);
    echo json_encode(['erro' => $mensagem]);
    exit;
}

// 1. Validar a data
$data = $_GET['data'] ?? '';
$dataObj = DateTime::createFromFormat('!Y-m-d', $data);

if (!$dataObj || $dataObj->format('Y-m-d') !== $data) {
    responderErro('Data inválida.');
}
if ($dataObj < new DateTime('today')) {
    responderErro('Não é possível agendar no passado.');
}

// 2. Validar os ids dos serviços
$ids = array_unique(array_filter(array_map('intval', explode(',', $_GET['servicos'] ?? ''))));

if (count($ids) === 0 || count($ids) > 10) {
    responderErro('Escolha ao menos um serviço.');
}

// 3. Somar a duração lendo o BANCO
$stmt = $pdo->prepare(
    'SELECT COUNT(*) AS qtd, COALESCE(SUM(duracao_minutos), 0) AS total
     FROM servico
     WHERE ativo = true AND id_servico = ANY(CAST(:ids AS integer[]))'
);
$stmt->execute([':ids' => '{' . implode(',', $ids) . '}']);
$res = $stmt->fetch();

if ((int) $res['qtd'] !== count($ids)) {
    responderErro('Serviço inválido.');
}

$duracao = (int) $res['total'];

$horarios = horariosLivres($pdo, $dataObj, $duracao);

echo json_encode(['duracao' => $duracao, 'horarios' => $horarios]);