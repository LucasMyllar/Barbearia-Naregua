<?php
require_once __DIR__ . '/../includes/auth.php';
exigirLogin();

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

function responderErro(string $mensagem): void
{
    http_response_code(400);
    echo json_encode(['erro' => $mensagem]);
    exit;
}

$passo = 30;

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

// 4. Calcular os horários (o mesmo cálculo de antes)
$diaSemana = (int) $dataObj->format('N');

$stmt = $pdo->prepare('SELECT hora_inicio, hora_fim FROM horario_funcionamento WHERE dia_semana = :dia AND ativo = true ORDER BY hora_inicio');
$stmt->execute([':dia' => $diaSemana]);
$faixas = $stmt->fetchAll();

$horarios = [];

$agora = new DateTime();

foreach ($faixas as $faixa) {
    $inicio = new DateTime($data . ' ' . $faixa['hora_inicio']);
    $fim = new DateTime($data . ' ' . $faixa['hora_fim']);

    $ultimoInicio = (clone $fim)->modify("-$duracao minutes");
    $atual = clone $inicio;

    while ($atual <= $ultimoInicio) {
        if ($atual > $agora) {
            $horarios[] = $atual->format('H:i');
        }
        $atual->modify("+$passo minutes");
    }
}

echo json_encode(['duracao' => $duracao, 'horarios' => $horarios]);