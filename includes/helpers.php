<?php
function formatarDuracao(int $minutos): string
{
    $h = intdiv($minutos, 60);
    $m = $minutos % 60;
    if ($h === 0) return "$m min";
    if ($m === 0) return "{$h}h";
    return "{$h}h {$m}min";
}

function iniciais(string $nome): string
{
    $partes = preg_split('/\s+/', trim($nome));
    $ini = mb_substr($partes[0], 0, 1);
    if (count($partes) > 1) {
        $ini .= mb_substr(end($partes), 0, 1);
    }
    return mb_strtoupper($ini);
}

function linkWhatsapp(?string $telefone): ?string
{
    $d = preg_replace('/\D/', '', (string) $telefone);
    if (strlen($d) === 10 || strlen($d) === 11) return 'https://wa.me/55' . $d;
    if ((strlen($d) === 12 || strlen($d) === 13) && str_starts_with($d, '55')) return 'https://wa.me/' . $d;
    return null;
}

function statusAdmin(string $status): array
{
    $mapa = [
        'PENDENTE'   => ['pending', 'Pendente', 'fa-regular fa-hourglass-half'],
        'CONFIRMADO' => ['confirmed', 'Confirmado', 'fa-regular fa-clock'],
        'CONCLUIDO'  => ['completed', 'Concluído', 'fa-solid fa-check'],
        'CANCELADO'  => ['canceled', 'Cancelado', 'fa-solid fa-xmark'],
    ];
    return $mapa[$status] ?? ['pending', $status, 'fa-regular fa-clock'];
}