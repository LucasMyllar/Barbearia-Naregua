<?php
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

require_once __DIR__ . '/../config/database.php';

$agendamentos = $pdo->query('SELECT * FROM app_privado.admin_listar_agendamentos()')->fetchAll();
$total = count($agendamentos);
$hoje = (new DateTime('today'))->format('Y-m-d');

$mapa = [
    'PENDENTE'   => ['pending', 'Pendente', 'fa-regular fa-hourglass-half'],
    'CONFIRMADO' => ['confirmed', 'Confirmado', 'fa-regular fa-clock'],
    'CONCLUIDO'  => ['completed', 'Concluído', 'fa-solid fa-check'],
    'CANCELADO'  => ['canceled', 'Cancelado', 'fa-solid fa-xmark'],
];

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
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Gestão de Agendamentos (Admin)</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS do Painel Administrativo -->
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <!-- Barra Lateral do Admin -->
    <section class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon-wrapper">
                <i class="fa-solid fa-scissors brand-icon"></i>
            </div>
            <h1>Na Régua</h1>
            <span class="sidebar-badge-admin"><i class="fa-solid fa-shield-halved"></i> Painel Admin</span>
        </div>

        <span class="sidebar-section-title">Gerenciamento</span>

        <a href="adminDashboard.php" class="sidebar-nav-btn">
            <i class="fa-solid fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
        <a href="adminAgendamentos.php" class="sidebar-nav-btn active">
            <i class="fa-regular fa-calendar-days"></i>
            <span>Agendamentos</span>
        </a>
        <a href="adminServicos.php" class="sidebar-nav-btn">
            <i class="fa-solid fa-scissors"></i>
            <span>Serviços & Preços</span>
        </a>
        <a href="adminClientes.php" class="sidebar-nav-btn">
            <i class="fa-solid fa-user-group"></i>
            <span>Base de Clientes</span>
        </a>

        <!-- Ações do Rodapé da Barra Lateral -->
        <div class="sidebar-bottom-actions">
            <a href="inicioCliente.php" class="sidebar-switch-view">
                <i class="fa-solid fa-store"></i>
                <span>Ver Visão do Cliente</span>
            </a>
            <form method="POST" action="logout.php" class="form-logout">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <button type="submit" class="sidebar-logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Sair</span>
                </button>
            </form>
        </div>
    </section>

    <!-- Cabeçalho Superior -->
    <header>
        <div class="header-breadcrumb">
            <a href="adminDashboard.php"><i class="fa-solid fa-chart-line"></i> Admin</a>
            <span class="separator"><i class="fa-solid fa-chevron-right"></i></span>
            <span class="current">Gestão de Agendamentos</span>
        </div>

        <div class="header-user-info">
            <div class="admin-header-pill">
                <div class="admin-avatar">AD</div>
                <div class="admin-details">
                    <span class="admin-name"><?= htmlspecialchars($_SESSION['nome'] ?? 'Administrador') ?></span>
                    <span class="admin-role">Administrador</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main>
        <!-- Topo da Página -->
        <div class="page-header-row">
            <div>
                <h1 class="page-title">Gestão de Agendamentos</h1>
                <p class="page-subtitle">Confirme, conclua ou cancele os agendamentos feitos pelos clientes.</p>
            </div>
        </div>

        <!-- Barra de Ferramentas / Filtros e Busca -->
        <div class="admin-toolbar">
            <div class="toolbar-left">
                <div class="filter-tabs">
                    <button type="button" class="filter-tab-btn active"
                        onclick="filterAdminAppointments('all', this)">Todos</button>
                    <button type="button" class="filter-tab-btn"
                        onclick="filterAdminAppointments('pending', this)">Pendentes</button>
                    <button type="button" class="filter-tab-btn"
                        onclick="filterAdminAppointments('confirmed', this)">Confirmados</button>
                    <button type="button" class="filter-tab-btn"
                        onclick="filterAdminAppointments('completed', this)">Concluídos</button>
                    <button type="button" class="filter-tab-btn"
                        onclick="filterAdminAppointments('canceled', this)">Cancelados</button>
                </div>
            </div>

            <div class="toolbar-right">
                <div class="search-box" style="width: 320px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="search-input" placeholder="Buscar por cliente, serviço ou fone..."
                        oninput="filterTableBySearch(this, '.appointment-row')">
                </div>
            </div>
        </div>

        <!-- Tabela Completa de Agendamentos -->
        <div class="admin-table-card">
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Data & Hora</th>
                            <th>Cliente</th>
                            <th>Serviço Contratado</th>
                            <th>Duração</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th style="text-align: right;">Ações de Gestão</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agendamentos as $ag): ?>
                            <?php
                                [$classe, $rotulo, $icone] = $mapa[$ag['status']] ?? ['pending', $ag['status'], 'fa-regular fa-clock'];
                                $inicio = new DateTime($ag['inicio']);
                                $ehHoje = $inicio->format('Y-m-d') === $hoje;
                                $zap = linkWhatsapp($ag['telefone']);
                            ?>
                            <tr class="appointment-row" data-status="<?= $classe ?>">
                                <td>
                                    <strong style="color: var(--gold); display: block;">
                                        <?= $ehHoje ? 'Hoje' : $inicio->format('d/m/Y') ?> às <?= $inicio->format('H:i') ?>
                                    </strong>
                                    <span style="font-size: 0.75rem; color: var(--text-dim);"><?= $inicio->format('d/m/Y') ?></span>
                                </td>
                                <td>
                                    <div class="table-client-cell">
                                        <div class="table-client-avatar"><?= htmlspecialchars(iniciais($ag['cliente'])) ?></div>
                                        <div class="table-client-info">
                                            <strong><?= htmlspecialchars($ag['cliente']) ?></strong>
                                            <span><?= htmlspecialchars($ag['telefone'] ?? $ag['email']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($ag['servicos']) ?></td>
                                <td><?= formatarDuracao((int) $ag['duracao']) ?></td>
                                <td><strong style="color: var(--text-white);">R$ <?= number_format((float) $ag['total'], 2, ',', '.') ?></strong></td>
                                <td>
                                    <span class="badge-status <?= $classe ?>"><i class="<?= $icone ?>"></i> <?= htmlspecialchars($rotulo) ?></span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="table-actions-group" style="justify-content: flex-end;">
                                        <?php if ($zap): ?>
                                            <a href="<?= htmlspecialchars($zap) ?>" target="_blank" rel="noopener"
                                                class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                                <i class="fa-brands fa-whatsapp"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($ag['status'] === 'PENDENTE'): ?>
                                            <button type="button" class="btn-icon-action success" data-id="<?= (int) $ag['id_agendamento'] ?>"
                                                onclick="alterarStatus(this, 'CONFIRMADO')" title="Confirmar agendamento">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($ag['status'] === 'CONFIRMADO'): ?>
                                            <button type="button" class="btn-icon-action success" data-id="<?= (int) $ag['id_agendamento'] ?>"
                                                onclick="alterarStatus(this, 'CONCLUIDO')" title="Concluir atendimento">
                                                <i class="fa-solid fa-check-double"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if (in_array($ag['status'], ['PENDENTE', 'CONFIRMADO'], true)): ?>
                                            <button type="button" class="btn-icon-action danger" data-id="<?= (int) $ag['id_agendamento'] ?>"
                                                onclick="alterarStatus(this, 'CANCELADO')" title="Cancelar agendamento">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Mensagem quando nenhum resultado for encontrado -->
                        <tr id="emptyTableMessage" style="display: <?= $total === 0 ? '' : 'none' ?>;">
                            <td colspan="7" style="text-align: center; padding: 40px;">
                                <i class="fa-regular fa-calendar-xmark"
                                    style="font-size: 2.4rem; color: var(--gold); margin-bottom: 12px; display: inline-block;"></i>
                                <p style="color: var(--text-white); font-weight: 600;">Nenhum agendamento encontrado.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Scripts do Administrador -->
    <script src="../assets/js/admin.js" defer></script>
    <script src="../assets/js/adminAgendamentos.js" defer></script>
</body>

</html>