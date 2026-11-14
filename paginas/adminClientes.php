<?php
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';

$clientes = $pdo->query('SELECT * FROM app_privado.admin_listar_clientes()')->fetchAll();

$totalClientes = count($clientes);
$comAgendamento = 0;
$somaAgendamentos = 0;

foreach ($clientes as $c) {
    $qtd = (int) $c['total_agendamentos'];
    $somaAgendamentos += $qtd;
    if ($qtd > 0) {
        $comAgendamento++;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Base de Clientes (Admin)</title>

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
        <a href="adminAgendamentos.php" class="sidebar-nav-btn">
            <i class="fa-regular fa-calendar-days"></i>
            <span>Agendamentos</span>
        </a>
        <a href="adminServicos.php" class="sidebar-nav-btn">
            <i class="fa-solid fa-scissors"></i>
            <span>Serviços & Preços</span>
        </a>
        <a href="adminClientes.php" class="sidebar-nav-btn active">
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
            <span class="current">Base de Clientes</span>
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
                <h1 class="page-title">Base de Clientes</h1>
                <p class="page-subtitle">Consulte os clientes cadastrados, o histórico de agendamentos e o contato direto.</p>
            </div>
        </div>

        <!-- Métricas Rápidas de Clientes -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon-box">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Total de Clientes</span>
                    <h3 class="kpi-value"><?= $totalClientes ?></h3>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon-box">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Já Agendaram</span>
                    <h3 class="kpi-value"><?= $comAgendamento ?></h3>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon-box">
                    <i class="fa-regular fa-calendar-days"></i>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Total de Agendamentos</span>
                    <h3 class="kpi-value"><?= $somaAgendamentos ?></h3>
                </div>
            </div>
        </div>

        <!-- Barra de Busca -->
        <div class="admin-toolbar">
            <div class="search-box" style="width: 320px;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" class="search-input" placeholder="Buscar por nome, e-mail ou telefone..."
                    oninput="filterTableBySearch(this, '.client-table-row')">
            </div>

            <div style="font-size: 0.88rem; color: var(--text-gray-muted);">
                Clientes em ordem alfabética
            </div>
        </div>

        <!-- Tabela de Clientes -->
        <div class="admin-table-card">
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Telefone</th>
                            <th>Agendamentos</th>
                            <th>Último Agendamento</th>
                            <th style="text-align: right;">Contato</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $c): ?>
                            <?php
                                $zap = linkWhatsapp($c['telefone']);
                                $ultimo = $c['ultimo_agendamento'] ? (new DateTime($c['ultimo_agendamento']))->format('d/m/Y \à\s H:i') : 'Nenhum';
                            ?>
                            <tr class="client-table-row">
                                <td>
                                    <div class="table-client-cell">
                                        <div class="table-client-avatar"><?= htmlspecialchars(iniciais($c['nome'])) ?></div>
                                        <div class="table-client-info">
                                            <strong><?= htmlspecialchars($c['nome']) ?></strong>
                                            <span><?= htmlspecialchars($c['email']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><strong style="color: var(--text-white);"><?= htmlspecialchars($c['telefone'] ?? 'Não informado') ?></strong></td>
                                <td><strong><?= (int) $c['total_agendamentos'] ?></strong></td>
                                <td><?= htmlspecialchars($ultimo) ?></td>
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

                        <?php if ($totalClientes === 0): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px;">
                                    <p style="color: var(--text-white); font-weight: 600;">Nenhum cliente cadastrado ainda.</p>
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