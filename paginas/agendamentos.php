<?php
require_once __DIR__ . '/../includes/auth.php';
exigirLogin();

require_once __DIR__ . '/../config/database.php';

$stmt = $pdo->prepare('SELECT * FROM app_privado.meus_agendamentos(CAST(:cliente AS integer))');
$stmt->execute([':cliente' => (int) $_SESSION['id_usuario']]);
$agendamentos = $stmt->fetchAll();

$abas = [
    'PENDENTE'   => 'active',
    'CONFIRMADO' => 'active',
    'CONCLUIDO'  => 'history',
    'CANCELADO'  => 'canceled',
];
$rotulos = [
    'PENDENTE'   => 'Pendente',
    'CONFIRMADO' => 'Confirmado',
    'CONCLUIDO'  => 'Concluído',
    'CANCELADO'  => 'Cancelado',
];
$classesStatus = [
    'PENDENTE'   => 'pending',
    'CONFIRMADO' => 'confirmed',
    'CONCLUIDO'  => 'done',
    'CANCELADO'  => 'canceled',
];
$meses = [
    1 => 'Janeiro',
    2 => 'Fevereiro',
    3 => 'Março',
    4 => 'Abril',
    5 => 'Maio',
    6 => 'Junho',
    7 => 'Julho',
    8 => 'Agosto',
    9 => 'Setembro',
    10 => 'Outubro',
    11 => 'Novembro',
    12 => 'Dezembro'
];

$contagem = ['active' => 0, 'history' => 0, 'canceled' => 0];

foreach ($agendamentos as $ag) {
    $tipo = $abas[$ag['status']] ?? 'active';
    $contagem[$tipo]++;
}

function formatarDuracao(int $minutos): string
{
    $h = intdiv($minutos, 60);
    $m = $minutos % 60;
    if ($h === 0) return "$m min";
    if ($m === 0) return "{$h}h";
    return "{$h}h {$m}min";
}

$agora = new DateTime();
$proximo = null;
$concluidos = 0;

foreach ($agendamentos as $ag) {
    if ($ag['status'] === 'CONCLUIDO') {
        $concluidos++;
    }

    $ativo = in_array($ag['status'], ['PENDENTE', 'CONFIRMADO'], true);
    $dataAg = new DateTime($ag['inicio']);

    if ($proximo === null && $ativo && $dataAg >= $agora) {
        $proximo = $dataAg;
    }
}

$total = count($agendamentos);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Meus Agendamentos</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS Customizado de Agendamentos -->
    <link rel="stylesheet" href="../assets/css/agendamentos.css">
</head>

<body>
    <!-- Barra Lateral -->
    <section class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon-wrapper">
                <i class="fa-solid fa-scissors brand-icon"></i>
            </div>
            <h1>Na Régua</h1>
            <span>Estilo • Confiança • Você</span>
        </div>

        <button type="button" class="sidebar-nav-btn" onclick="window.location.href='inicioCliente.php'">
            <i class="fa-solid fa-house"></i>
            <span>Início</span>
        </button>
        <button type="button" class="sidebar-nav-btn active" onclick="window.location.href='agendamentos.php'">
            <i class="fa-regular fa-calendar-days"></i>
            <span>Agendamentos</span>
        </button>
        <button type="button" class="sidebar-nav-btn" onclick="window.location.href='perfil.php'">
            <i class="fa-regular fa-user"></i>
            <span>Perfil</span>
        </button>

        <form method="POST" action="logout.php" class="form-logout">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <button type="submit" class="sidebar-logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Sair</span>
            </button>
        </form>
    </section>

    <!-- Cabeçalho Superior -->
    <header>
        <div class="header-breadcrumb">
            <a href="inicioCliente.php"><i class="fa-solid fa-house"></i> Início</a>
            <span class="separator"><i class="fa-solid fa-chevron-right"></i></span>
            <span class="current"><i class="fa-regular fa-calendar-days"></i> Meus Agendamentos</span>
        </div>

        <div class="header-user-info">
            <div class="header-user-badge">
                <i class="fa-solid fa-circle-user"></i>
                <span>Minha Conta</span>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main>
        <!-- Topo da Página -->
        <div class="agendamentos-top-row">
            <div>
                <h1 class="page-title">Meus Agendamentos</h1>
                <p class="page-subtitle">Acompanhe seus horários marcados e consulte o histórico.</p>
            </div>
            <a href="agendar.php" class="btn-new-appointment">
                <i class="fa-solid fa-calendar-plus"></i> Novo Agendamento
            </a>
        </div>

        <!-- Estatísticas (ainda fixas: serão calculadas no próximo passo) -->
        <div class="stats-cards-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-regular fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Próximo Horário</span>
                    <h3 class="stat-number"><?= $contagem['active'] ?> <?= $contagem['active'] === 1 ? 'Ativo' : 'Ativos' ?></h3>
                    <span class="stat-detail"><i class="fa-solid fa-clock"></i>
                        <?php if ($proximo): ?>
                            <?= $proximo->format('Y-m-d') === $agora->format('Y-m-d') ? 'Hoje' : $proximo->format('d/m') ?> às <?= $proximo->format('H:i') ?>
                        <?php else: ?>
                            Nenhum horário marcado
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-scissors"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Atendimentos Realizados</span>
                    <h3 class="stat-number"><?= $concluidos ?> <?= $concluidos === 1 ? 'Atendimento' : 'Atendimentos' ?></h3>
                    <span class="stat-detail"><i class="fa-solid fa-check"></i> Já concluídos</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-regular fa-calendar-days"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Total de Agendamentos</span>
                    <h3 class="stat-number"><?= $total ?></h3>
                    <span class="stat-detail"><i class="fa-solid fa-list"></i> Contando todos os status</span>
                </div>
            </div>
        </div>

        <!-- Abas de Filtro (os números ainda são fixos) -->
        <div class="appointments-tabs-nav">
            <button type="button" class="tab-btn active" onclick="filterAppointments('active', this)">
                <i class="fa-regular fa-calendar-check"></i> Próximos Horários <span class="tab-counter"><?= $contagem['active'] ?></span>
            </button>
            <button type="button" class="tab-btn" onclick="filterAppointments('history', this)">
                <i class="fa-solid fa-clock-rotate-left"></i> Histórico de Cortes <span class="tab-counter"><?= $contagem['history'] ?></span>
            </button>
            <button type="button" class="tab-btn" onclick="filterAppointments('canceled', this)">
                <i class="fa-regular fa-calendar-xmark"></i> Cancelados <span class="tab-counter"><?= $contagem['canceled'] ?></span>
            </button>
        </div>

        <!-- Listagem de Agendamentos -->
        <div class="appointments-list" id="appointmentsList">
            <?php foreach ($agendamentos as $ag): ?>
                <?php
                $inicio = new DateTime($ag['inicio']);
                $tipo = $abas[$ag['status']] ?? 'active';
                ?>
                <div class="appointment-item-card appt-item" data-type="<?= $tipo ?>"
                    style="<?= $tipo !== 'active' ? 'display: none;' : '' ?>">
                    <div class="appointment-date-badge">
                        <span class="date-badge-day"><?= $inicio->format('d') ?></span>
                        <span class="date-badge-month"><?= $meses[(int) $inicio->format('n')] ?></span>
                        <span class="date-badge-time"><i class="fa-regular fa-clock"></i> <?= $inicio->format('H:i') ?></span>
                    </div>

                    <div class="appointment-details">
                        <div class="appointment-header-row">
                            <h2 class="appointment-service-title"><?= htmlspecialchars($ag['servicos']) ?></h2>
                            <span class="status-badge status-<?= $classesStatus[$ag['status']] ?? 'unknown' ?>"><?= $rotulos[$ag['status']] ?? htmlspecialchars($ag['status']) ?></span>
                        </div>

                        <div class="appointment-meta-info">
                            <span class="meta-item"><i class="fa-regular fa-hourglass-half"></i> Duração: <?= formatarDuracao((int) $ag['duracao']) ?></span>
                        </div>

                        <span class="appointment-price-tag">R$ <?= number_format((float) $ag['total'], 2, ',', '.') ?></span>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Mensagem para quando a aba está vazia -->
            <div id="emptyMessage" class="empty-message">
                <p>Nenhum agendamento nesta categoria.</p>
            </div>
        </div>
    </main>

    <!-- Script de Agendamentos -->
    <script src="../assets/js/agendamentos.js" defer></script>
</body>

</html>