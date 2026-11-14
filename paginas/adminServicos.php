<?php
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

require_once __DIR__ . '/../config/database.php';

$servicos = $pdo->query('SELECT * FROM app_privado.admin_listar_servicos()')->fetchAll();
$total = count($servicos);

function formatarDuracao(int $minutos): string
{
    $h = intdiv($minutos, 60);
    $m = $minutos % 60;
    if ($h === 0) return "$m min";
    if ($m === 0) return "{$h}h";
    return "{$h}h {$m}min";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Catálogo de Serviços & Preços (Admin)</title>

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
        <a href="adminServicos.php" class="sidebar-nav-btn active">
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
            <span class="current">Catálogo de Serviços</span>
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
                <h1 class="page-title">Serviços & Tabela de Preços</h1>
                <p class="page-subtitle">Configure os serviços disponíveis para agendamento, durações estimadas e preços
                    vigentes.</p>
            </div>
            <button type="button" class="btn-primary-admin" onclick="novoServico()">
                <i class="fa-solid fa-plus"></i> Novo Serviço
            </button>
        </div>

        <!-- Barra de Busca -->
        <div class="admin-toolbar">
            <div class="search-box" style="width: 320px;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" class="search-input" placeholder="Buscar serviço por nome..."
                    oninput="filterTableBySearch(this, '.admin-item-card')">
            </div>

            <div style="font-size: 0.88rem; color: var(--text-gray-muted);">
                Exibindo <strong><?= $total ?> <?= $total === 1 ? 'serviço' : 'serviços' ?></strong> no catálogo
            </div>
        </div>

        <!-- Grade de Cards de Serviços -->
        <div class="admin-cards-grid">
            <?php foreach ($servicos as $s): ?>
                <?php $ativo = in_array($s['ativo'], [true, 't', 1, '1'], true); ?>
                <div class="admin-item-card"
                     data-id="<?= (int) $s['id_servico'] ?>"
                     data-nome="<?= htmlspecialchars($s['nome'], ENT_QUOTES) ?>"
                     data-descricao="<?= htmlspecialchars((string) ($s['descricao'] ?? ''), ENT_QUOTES) ?>"
                     data-preco="<?= htmlspecialchars((string) $s['preco'], ENT_QUOTES) ?>"
                     data-duracao="<?= (int) $s['duracao_minutos'] ?>"
                     data-ativo="<?= $ativo ? '1' : '0' ?>"
                     style="<?= $ativo ? '' : 'opacity: 0.6;' ?>">
                    <div>
                        <div class="card-item-top">
                            <div class="item-icon-avatar">
                                <i class="fa-solid fa-scissors"></i>
                            </div>
                            <?php if ($ativo): ?>
                                <span class="badge-status active"><i class="fa-solid fa-circle-check"></i> Ativo</span>
                            <?php else: ?>
                                <span class="badge-status inactive"><i class="fa-solid fa-circle-pause"></i> Desativado</span>
                            <?php endif; ?>
                        </div>

                        <div class="item-title-group">
                            <h3><?= htmlspecialchars($s['nome']) ?></h3>
                        </div>

                        <p class="item-desc"><?= htmlspecialchars((string) ($s['descricao'] ?? '')) ?></p>
                    </div>

                    <div>
                        <div class="item-meta-row">
                            <div>
                                <span class="item-duration"><i class="fa-regular fa-clock"></i>
                                    <?= formatarDuracao((int) $s['duracao_minutos']) ?> de atendimento</span>
                                <div class="item-price">R$ <?= number_format((float) $s['preco'], 2, ',', '.') ?></div>
                            </div>
                            <div class="table-actions-group">
                                <button type="button" class="btn-icon-action" onclick="editarServico(this)" title="Editar serviço">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button type="button" class="btn-icon-action gold" onclick="alternarServico(this)"
                                    title="<?= $ativo ? 'Desativar serviço' : 'Reativar serviço' ?>">
                                    <i class="fa-solid <?= $ativo ? 'fa-pause' : 'fa-play' ?>"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Modal: Novo / Editar Serviço -->
    <div class="modal-overlay" id="modalNewService">
        <div class="admin-modal">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fa-solid fa-scissors"></i> <span id="tituloModalServico">Cadastrar Novo Serviço</span></h3>
                <button type="button" class="btn-close-modal" onclick="closeModal('modalNewService')">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form id="formServico">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="id" value="">
                <div class="modal-body">
                    <div class="form-grid-2">
                        <div class="form-group full-width">
                            <label class="form-label">Nome do Serviço *</label>
                            <input type="text" name="nome" class="form-input" maxlength="100" placeholder="Ex: Pigmentação de Barba" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Preço (R$) *</label>
                            <input type="text" name="preco" class="form-input" inputmode="decimal" placeholder="Ex: 50,00" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Duração (minutos) *</label>
                            <input type="number" name="duracao" class="form-input" min="5" max="480" step="5" placeholder="Ex: 45" required>
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Descrição Detalhada</label>
                            <textarea name="descricao" class="form-textarea" maxlength="1000"
                                placeholder="Descreva os diferenciais e o que inclui o serviço..."></textarea>
                        </div>
                    </div>
                    <p id="erroServico"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-admin"
                        onclick="closeModal('modalNewService')">Cancelar</button>
                    <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-floppy-disk"></i> Salvar
                        Serviço</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts do Administrador -->
    <script src="../assets/js/admin.js" defer></script>
    <script src="../assets/js/adminServicos.js" defer></script>
</body>

</html>