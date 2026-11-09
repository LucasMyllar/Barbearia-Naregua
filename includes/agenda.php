<?php

function horariosLivres(PDO $pdo, DateTime $dataObj, int $duracao): array
{

    $passo = 30;
    $data = $dataObj->format('Y-m-d');

    $diaSemana = (int) $dataObj->format('N');

    $stmt = $pdo->prepare('SELECT hora_inicio, hora_fim FROM horario_funcionamento WHERE dia_semana = :dia AND ativo = true ORDER BY hora_inicio');
    $stmt->execute([':dia' => $diaSemana]);
    $faixas = $stmt->fetchAll();

    $diaSeguinte = (clone $dataObj)->modify('+1 day');

    $stmt = $pdo->prepare('SELECT inicio, fim FROM app_privado.intervalos_ocupados(CAST(:ini AS timestamp), CAST(:fim AS timestamp))');
    $stmt->execute([
        ':ini' => $dataObj->format('Y-m-d H:i:s'),
        ':fim' => $diaSeguinte->format('Y-m-d H:i:s'),
    ]);

    $ocupados = $stmt->fetchAll();

    $horarios = [];

    $agora = new DateTime();

    foreach ($faixas as $faixa) {
        $inicio = new DateTime($data . ' ' . $faixa['hora_inicio']);
        $fim = new DateTime($data . ' ' . $faixa['hora_fim']);

        $ultimoInicio = (clone $fim)->modify("-$duracao minutes");
        $atual = clone $inicio;

        while ($atual <= $ultimoInicio) {
            $fimAtendimento = (clone $atual)->modify("+$duracao minutes");
            $livre = true;

            foreach ($ocupados as $ocupado) {
                $ocupadoInicio = new DateTime($ocupado['inicio']);
                $ocupadoFim = new DateTime($ocupado['fim']);

                if ($atual < $ocupadoFim && $fimAtendimento > $ocupadoInicio) {
                    $livre = false;
                    break;
                }
            }

            if ($livre && $atual > $agora) {
                $horarios[] = $atual->format('H:i');
            }

            $atual->modify("+$passo minutes");
        }
    }

    return $horarios;
}

