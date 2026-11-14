<?php
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';

$todos = $pdo->query('SELECT * FROM app_privado.admin_listar_agendamentos()')->fetchAll();

$hoje = (new DateTime('today'))->format('Y-m-d');

$deHoje = array_values(array_filter($todos, fn($a) => substr($a['inicio'], 0, 10) === $hoje));
usort($deHoje, fn($a, $b) => strcmp($a['inicio'], $b['inicio']));

$ativosHoje = 0;
$concluidosHoje = 0;
$faturamentoHoje = 0.0;

foreach ($deHoje as $a) {
    if ($a['status'] !== 'CANCELADO') {
        $ativosHoje++;
    }
    if ($a['status'] === 'CONCLUIDO') {
        $concluidosHoje++;
        $faturamentoHoje += (float) $a['total'];
    }
}

$pendentesGeral = count(array_filter($todos, fn($a) => $a['status'] === 'PENDENTE'));

$diasSemana = [1 => 'Segunda-feira', 2 => 'Terça-feira', 3 => 'Quarta-feira', 4 => 'Quinta-feira',
               5 => 'Sexta-feira', 6 => 'Sábado', 7 => 'Domingo'];
$nomeDia = $diasSemana[(int) (new DateTime('today'))->format('N')];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Painel do Administrador</title>

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

        <a href="adminDashboard.php" class="sidebar-nav-btn active">
            <i class="fa-solid fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
        <a href="adminAgendamentos.php" class="sidebar-nav-btn">
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
            <span class="current">Dashboard Geral</span>
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
                <h1 class="page-title">Visão Geral da Barbearia</h1>
                <p class="page-subtitle">Acompanhe os atendimentos do dia e o que precisa da sua atenção.</p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="adminAgendamentos.php" class="btn-primary-admin">
                    <i class="fa-regular fa-calendar-days"></i> Gerenciar Agendamentos
                </a>
                <a href="adminServicos.php" class="btn-secondary-admin">
                    <i class="fa-solid fa-scissors"></i> Gerenciar Serviços
                </a>
            </div>
        </div>

        <!-- Grade de Indicadores (KPIs) -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon-box">
                    <i class="fa-regular fa-calendar-check"></i>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Agendamentos Hoje</span>
                    <h3 class="kpi-value"><?= $ativosHoje ?></h3>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon-box">
                    <i class="fa-solid fa-brazilian-real-sign"></i>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Faturamento Hoje (concluídos)</span>
                    <h3 class="kpi-value">R$ <?= number_format($faturamentoHoje, 2, ',', '.') ?></h3>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon-box">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Atendimentos Finalizados</span>
                    <h3 class="kpi-value"><?= $concluidosHoje ?> / <?= $ativosHoje ?></h3>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon-box">
                    <i class="fa-regular fa-hourglass-half"></i>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Aguardando Confirmação</span>
                    <h3 class="kpi-value"><?= $pendentesGeral ?></h3>
                </div>
            </div>
        </div>

        <!-- Tabela de Atendimentos de Hoje -->
        <div class="admin-table-card">
            <div class="table-card-header">
                <h2 class="table-card-title">
                    <i class="fa-solid fa-clock-rotate-left"></i> Agenda de Hoje (<?= $nomeDia ?>)
                </h2>
                <a href="adminAgendamentos.php" class="btn-secondary-admin"
                    style="font-size: 0.82rem; padding: 6px 16px;">
                    Ver Todos os Agendamentos <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Serviço</th>
                            <th>Horário</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th style="text-align: right;">Contato</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deHoje as $ag): ?>
                            <?php
                                [$classe, $rotulo, $icone] = statusAdmin($ag['status']);
                                $inicio = new DateTime($ag['inicio']);
                                $zap = linkWhatsapp($ag['telefone']);
                            ?>
                            <tr class="appointment-row" data-status="<?= $classe ?>">
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
                                <td><strong style="color: var(--gold);"><?= $inicio->format('H:i') ?></strong> (<?= formatarDuracao((int) $ag['duracao']) ?>)</td>
                                <td><strong>R$ <?= number_format((float) $ag['total'], 2, ',', '.') ?></strong></td>
                                <td><span class="badge-status <?= $classe ?>"><i class="<?= $icone ?>"></i> <?= htmlspecialchars($rotulo) ?></span></td>
                                <td style="text-align: right;">
                                    <div class="table-actions-group" style="justify-content: flex-end;">
                                        <?php if ($zap): ?>
                                            <a href="<?= htmlspecialchars($zap) ?>" target="_blank" rel="noopener"
                                                class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                                <i class="fa-brands fa-whatsapp"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (count($deHoje) === 0): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px;">
                                    <p style="color: var(--text-white); font-weight: 600;">Nenhum agendamento para hoje.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Script Geral do Administrador -->
    <script src="../assets/js/admin.js" defer></script>
</body>

</html>