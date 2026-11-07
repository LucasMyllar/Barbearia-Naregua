<?php
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();
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
                <h1 class="page-title">Gestão de Agendamentos</h1>
                <p class="page-subtitle">Controle total da agenda da barbearia, status de atendimento e agendamento
                    manual.</p>
            </div>
            <button type="button" class="btn-primary-admin" onclick="openModal('modalNewAppointment')">
                <i class="fa-solid fa-calendar-plus"></i> Novo Agendamento
            </button>
        </div>

        <!-- Barra de Ferramentas / Filtros e Busca -->
        <div class="admin-toolbar">
            <div class="toolbar-left">
                <!-- Abas de Filtro de Status -->
                <div class="filter-tabs">
                    <button type="button" class="filter-tab-btn active"
                        onclick="filterAdminAppointments('all', this)">Todos</button>
                    <button type="button" class="filter-tab-btn"
                        onclick="filterAdminAppointments('in-progress', this)">Em Cadeira</button>
                    <button type="button" class="filter-tab-btn"
                        onclick="filterAdminAppointments('confirmed', this)">Confirmados</button>
                    <button type="button" class="filter-tab-btn"
                        onclick="filterAdminAppointments('completed', this)">Concluídos</button>
                    <button type="button" class="filter-tab-btn"
                        onclick="filterAdminAppointments('canceled', this)">Cancelados</button>
                </div>
            </div>

            <div class="toolbar-right">
                <!-- Campo de Busca em Tempo Real -->
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
                            <th>Barbeiro</th>
                            <th>Duração</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th style="text-align: right;">Ações de Gestão</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="appointment-row" data-status="in-progress">
                            <td>
                                <strong style="color: var(--gold); display: block;">Hoje às 16:00</strong>
                                <span style="font-size: 0.75rem; color: var(--text-dim);">29/10/2026</span>
                            </td>
                            <td>
                                <div class="table-client-cell">
                                    <div class="table-client-avatar">LS</div>
                                    <div class="table-client-info">
                                        <strong>Lucas Silva</strong>
                                        <span>(11) 98765-4321</span>
                                    </div>
                                </div>
                            </td>
                            <td>Combo Cabelo + Barba Na Régua</td>
                            <td>Carlos Navalha</td>
                            <td>1h 15min</td>
                            <td><strong style="color: var(--text-white);">R$ 70,00</strong></td>
                            <td><span class="badge-status in-progress"><i class="fa-solid fa-spinner fa-spin"></i> Em
                                    Cadeira</span></td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511987654321" target="_blank"
                                        class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <button type="button" class="btn-icon-action success"
                                        onclick="concludeAppointment(this)" title="Concluir Atendimento">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button type="button" class="btn-icon-action danger"
                                        onclick="cancelAdminAppointment(this)" title="Cancelar Agendamento">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="appointment-row" data-status="confirmed">
                            <td>
                                <strong style="color: var(--text-white); display: block;">Hoje às 17:00</strong>
                                <span style="font-size: 0.75rem; color: var(--text-dim);">29/10/2026</span>
                            </td>
                            <td>
                                <div class="table-client-cell">
                                    <div class="table-client-avatar">MO</div>
                                    <div class="table-client-info">
                                        <strong>Matheus Oliveira</strong>
                                        <span>(11) 97123-8899</span>
                                    </div>
                                </div>
                            </td>
                            <td>Corte Masculino (Fade Navalhado)</td>
                            <td>Lucas Silva</td>
                            <td>45 min</td>
                            <td><strong style="color: var(--text-white);">R$ 45,00</strong></td>
                            <td><span class="badge-status confirmed"><i class="fa-regular fa-clock"></i>
                                    Confirmado</span></td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511971238899" target="_blank"
                                        class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <button type="button" class="btn-icon-action success"
                                        onclick="concludeAppointment(this)" title="Concluir Atendimento">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button type="button" class="btn-icon-action danger"
                                        onclick="cancelAdminAppointment(this)" title="Cancelar Agendamento">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="appointment-row" data-status="confirmed">
                            <td>
                                <strong style="color: var(--text-white); display: block;">Hoje às 17:45</strong>
                                <span style="font-size: 0.75rem; color: var(--text-dim);">29/10/2026</span>
                            </td>
                            <td>
                                <div class="table-client-cell">
                                    <div class="table-client-avatar">RA</div>
                                    <div class="table-client-info">
                                        <strong>Rafael Andrade</strong>
                                        <span>(11) 96544-2211</span>
                                    </div>
                                </div>
                            </td>
                            <td>Barba Terapia Completa & Toalha Quente</td>
                            <td>Carlos Navalha</td>
                            <td>30 min</td>
                            <td><strong style="color: var(--text-white);">R$ 35,00</strong></td>
                            <td><span class="badge-status confirmed"><i class="fa-regular fa-clock"></i>
                                    Confirmado</span></td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511965442211" target="_blank"
                                        class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <button type="button" class="btn-icon-action success"
                                        onclick="concludeAppointment(this)" title="Concluir Atendimento">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button type="button" class="btn-icon-action danger"
                                        onclick="cancelAdminAppointment(this)" title="Cancelar Agendamento">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="appointment-row" data-status="confirmed">
                            <td>
                                <strong style="color: var(--text-white); display: block;">Hoje às 18:30</strong>
                                <span style="font-size: 0.75rem; color: var(--text-dim);">29/10/2026</span>
                            </td>
                            <td>
                                <div class="table-client-cell">
                                    <div class="table-client-avatar">TB</div>
                                    <div class="table-client-info">
                                        <strong>Thiago Barbosa</strong>
                                        <span>(11) 98888-7766</span>
                                    </div>
                                </div>
                            </td>
                            <td>Acabamento de Pezinho + Sobrancelha</td>
                            <td>André Barbieri</td>
                            <td>20 min</td>
                            <td><strong style="color: var(--text-white);">R$ 25,00</strong></td>
                            <td><span class="badge-status confirmed"><i class="fa-regular fa-clock"></i>
                                    Confirmado</span></td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511988887766" target="_blank"
                                        class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <button type="button" class="btn-icon-action success"
                                        onclick="concludeAppointment(this)" title="Concluir Atendimento">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button type="button" class="btn-icon-action danger"
                                        onclick="cancelAdminAppointment(this)" title="Cancelar Agendamento">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="appointment-row" data-status="completed">
                            <td>
                                <strong style="color: var(--text-dim); display: block;">Hoje às 14:30</strong>
                                <span style="font-size: 0.75rem; color: var(--text-dim);">29/10/2026</span>
                            </td>
                            <td>
                                <div class="table-client-cell">
                                    <div class="table-client-avatar">GC</div>
                                    <div class="table-client-info">
                                        <strong>Gabriel Costa</strong>
                                        <span>(11) 95555-1234</span>
                                    </div>
                                </div>
                            </td>
                            <td>Corte Degradê + Acabamento</td>
                            <td>Lucas Silva</td>
                            <td>45 min</td>
                            <td><strong style="color: var(--text-white);">R$ 45,00</strong></td>
                            <td><span class="badge-status completed"><i class="fa-solid fa-check"></i> Concluído</span>
                            </td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511955551234" target="_blank"
                                        class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <span style="font-size: 0.8rem; color: var(--success);"><i
                                            class="fa-solid fa-check-double"></i> Pago</span>
                                </div>
                            </td>
                        </tr>

                        <tr class="appointment-row" data-status="completed">
                            <td>
                                <strong style="color: var(--text-dim); display: block;">Hoje às 11:15</strong>
                                <span style="font-size: 0.75rem; color: var(--text-dim);">29/10/2026</span>
                            </td>
                            <td>
                                <div class="table-client-cell">
                                    <div class="table-client-avatar">BM</div>
                                    <div class="table-client-info">
                                        <strong>Bruno Martins</strong>
                                        <span>(11) 94444-9988</span>
                                    </div>
                                </div>
                            </td>
                            <td>Barba Completa</td>
                            <td>Carlos Navalha</td>
                            <td>30 min</td>
                            <td><strong style="color: var(--text-white);">R$ 35,00</strong></td>
                            <td><span class="badge-status completed"><i class="fa-solid fa-check"></i> Concluído</span>
                            </td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511944449988" target="_blank"
                                        class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <span style="font-size: 0.8rem; color: var(--success);"><i
                                            class="fa-solid fa-check-double"></i> Pago</span>
                                </div>
                            </td>
                        </tr>

                        <tr class="appointment-row" data-status="canceled">
                            <td>
                                <strong style="color: var(--text-dim); display: block;">Hoje às 10:00</strong>
                                <span style="font-size: 0.75rem; color: var(--text-dim);">29/10/2026</span>
                            </td>
                            <td>
                                <div class="table-client-cell">
                                    <div class="table-client-avatar">JP</div>
                                    <div class="table-client-info">
                                        <strong>João Pedro</strong>
                                        <span>(11) 93333-2211</span>
                                    </div>
                                </div>
                            </td>
                            <td>Corte Clássico</td>
                            <td>Lucas Silva</td>
                            <td>40 min</td>
                            <td><strong style="color: var(--text-dim);">R$ 40,00</strong></td>
                            <td><span class="badge-status canceled"><i class="fa-solid fa-xmark"></i> Cancelado</span>
                            </td>
                            <td style="text-align: right;">
                                <span style="font-size: 0.8rem; color: var(--danger);"><i class="fa-solid fa-ban"></i>
                                    Desistência</span>
                            </td>
                        </tr>

                        <!-- Mensagem quando nenhum resultado for encontrado na busca -->
                        <tr id="emptyTableMessage" style="display: none;">
                            <td colspan="8" style="text-align: center; padding: 40px;">
                                <i class="fa-regular fa-calendar-xmark"
                                    style="font-size: 2.4rem; color: var(--gold); margin-bottom: 12px; display: inline-block;"></i>
                                <p style="color: var(--text-white); font-weight: 600;">Nenhum agendamento encontrado com
                                    esses critérios.</p>
                                <span style="color: var(--text-dim); font-size: 0.85rem;">Tente ajustar as abas de
                                    status ou o termo de pesquisa.</span>
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
                <h3 class="modal-title"><i class="fa-solid fa-calendar-plus"></i> Novo Agendamento</h3>
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
                    <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-check"></i> Agendar
                        Cliente</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Geral do Administrador -->
    <script src="../assets/js/admin.js" defer></script>
</body>

</html>