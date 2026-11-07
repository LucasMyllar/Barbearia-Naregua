<?php
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();
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
                    <span class="admin-name">Gestor Na Régua</span>
                    <span class="admin-role">Administrador Master</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main>
        <!-- Topo da Página -->
        <div class="page-header-row">
            <div>
                <h1 class="page-title">Base de Clientes & CRM</h1>
                <p class="page-subtitle">Consulte a frequência de cortes, histórico de visitas e contato direto com seus
                    clientes.</p>
            </div>
            <button type="button" class="btn-primary-admin" onclick="openModal('modalNewAppointment')">
                <i class="fa-solid fa-calendar-plus"></i> Agendar para Cliente
            </button>
        </div>

        <!-- Métricas Rápidas de Clientes -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon-box">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Total de Clientes</span>
                    <h3 class="kpi-value">248</h3>
                    <span class="kpi-badge positive"><i class="fa-solid fa-arrow-trend-up"></i> +18 este mês</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon-box">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Clientes Ativos</span>
                    <h3 class="kpi-value">215</h3>
                    <span class="kpi-badge positive"><i class="fa-solid fa-circle-check"></i> Visitas recorrentes</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon-box">
                    <i class="fa-solid fa-repeat"></i>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Retorno Médio</span>
                    <h3 class="kpi-value">16 dias</h3>
                    <span class="kpi-badge positive"><i class="fa-solid fa-circle-check"></i> Alto engajamento</span>
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
                Exibindo clientes ordenados por atividade recente
            </div>
        </div>

        <!-- Tabela de Clientes -->
        <div class="admin-table-card">
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Contato WhatsApp</th>
                            <th>Status</th>
                            <th>Total de Cortes</th>
                            <th>Última Visita</th>
                            <th>Estilo Mais Frequente</th>
                            <th style="text-align: right;">Ações de Contato</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="client-table-row">
                            <td>
                                <div class="table-client-cell">
                                    <div class="table-client-avatar">LS</div>
                                    <div class="table-client-info">
                                        <strong>Lucas Silva</strong>
                                        <span>lucas.silva@email.com</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong style="color: var(--text-white);">(11) 98765-4321</strong>
                            </td>
                            <td>
                                <span class="badge-status active">
                                    <i class="fa-solid fa-circle-check"></i> Ativo
                                </span>
                            </td>
                            <td><strong>6 cortes</strong></td>
                            <td>Hoje às 16:00</td>
                            <td>Combo Cabelo + Barba</td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511987654321" target="_blank"
                                        class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <button type="button" class="btn-icon-action gold"
                                        onclick="openModal('modalNewAppointment')" title="Agendar Novo Corte">
                                        <i class="fa-regular fa-calendar-plus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="client-table-row">
                            <td>
                                <div class="table-client-cell">
                                    <div class="table-client-avatar">MO</div>
                                    <div class="table-client-info">
                                        <strong>Matheus Oliveira</strong>
                                        <span>matheus.o@email.com</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong style="color: var(--text-white);">(11) 97123-8899</strong>
                            </td>
                            <td>
                                <span class="badge-status confirmed">Frequente</span>
                            </td>
                            <td><strong>4 cortes</strong></td>
                            <td>Hoje às 17:00</td>
                            <td>Degradê / Fade Navalhado</td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511971238899" target="_blank"
                                        class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <button type="button" class="btn-icon-action gold"
                                        onclick="openModal('modalNewAppointment')" title="Agendar Novo Corte">
                                        <i class="fa-regular fa-calendar-plus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="client-table-row">
                            <td>
                                <div class="table-client-cell">
                                    <div class="table-client-avatar">RA</div>
                                    <div class="table-client-info">
                                        <strong>Rafael Andrade</strong>
                                        <span>rafael.andrade@email.com</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong style="color: var(--text-white);">(11) 96544-2211</strong>
                            </td>
                            <td>
                                <span class="badge-status active">
                                    <i class="fa-solid fa-circle-check"></i> Ativo
                                </span>
                            </td>
                            <td><strong>9 cortes</strong></td>
                            <td>Hoje às 17:45</td>
                            <td>Barba Terapia Completa</td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511965442211" target="_blank"
                                        class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <button type="button" class="btn-icon-action gold"
                                        onclick="openModal('modalNewAppointment')" title="Agendar Novo Corte">
                                        <i class="fa-regular fa-calendar-plus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="client-table-row">
                            <td>
                                <div class="table-client-cell">
                                    <div class="table-client-avatar">GC</div>
                                    <div class="table-client-info">
                                        <strong>Gabriel Costa</strong>
                                        <span>costa.gabriel@email.com</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong style="color: var(--text-white);">(11) 95555-1234</strong>
                            </td>
                            <td>
                                <span class="badge-status confirmed">Frequente</span>
                            </td>
                            <td><strong>5 cortes</strong></td>
                            <td>Hoje às 14:30</td>
                            <td>Corte Degradê + Acabamento</td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511955551234" target="_blank"
                                        class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <button type="button" class="btn-icon-action gold"
                                        onclick="openModal('modalNewAppointment')" title="Agendar Novo Corte">
                                        <i class="fa-regular fa-calendar-plus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="client-table-row">
                            <td>
                                <div class="table-client-cell">
                                    <div class="table-client-avatar">BM</div>
                                    <div class="table-client-info">
                                        <strong>Bruno Martins</strong>
                                        <span>bruno.m@email.com</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong style="color: var(--text-white);">(11) 94444-9988</strong>
                            </td>
                            <td>
                                <span class="badge-status completed">Novo</span>
                            </td>
                            <td><strong>2 cortes</strong></td>
                            <td>Hoje às 11:15</td>
                            <td>Barba Completa</td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511944449988" target="_blank"
                                        class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <button type="button" class="btn-icon-action gold"
                                        onclick="openModal('modalNewAppointment')" title="Agendar Novo Corte">
                                        <i class="fa-regular fa-calendar-plus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal: Novo Agendamento Presencial / Manual -->
    <div class="modal-overlay" id="modalNewAppointment">
        <div class="admin-modal">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fa-solid fa-calendar-plus"></i> Agendar para Cliente</h3>
                <button type="button" class="btn-close-modal" onclick="closeModal('modalNewAppointment')">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form id="formNewAppointment">
                <div class="modal-body">
                    <div class="form-grid-2">
                        <div class="form-group full-width">
                            <label class="form-label">Nome do Cliente *</label>
                            <input type="text" class="form-input" placeholder="Ex: Felipe Santos" required>
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">WhatsApp / Telefone *</label>
                            <input type="tel" class="form-input" placeholder="(11) 90000-0000" required>
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Serviço Desejado *</label>
                            <select class="form-select" required>
                                <option value="">Selecione o serviço</option>
                                <option>Combo Cabelo + Barba Na Régua (R$ 70,00)</option>
                                <option>Corte Masculino Degradê / Fade (R$ 45,00)</option>
                                <option>Barba Completa & Toalha Quente (R$ 35,00)</option>
                                <option>Acabamento & Sobrancelha (R$ 25,00)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Data *</label>
                            <input type="date" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Horário *</label>
                            <input type="time" class="form-input" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-admin"
                        onclick="closeModal('modalNewAppointment')">Cancelar</button>
                    <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-check"></i> Confirmar
                        Agendamento</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Geral do Administrador -->
    <script src="../assets/js/admin.js" defer></script>
</body>

</html>