<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Painel do Administrador</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
            <a href="login.php" class="sidebar-logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Sair do Sistema</span>
            </a>
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
                <h1 class="page-title">Visão Geral da Barbearia</h1>
                <p class="page-subtitle">Acompanhe métricas em tempo real, atendimentos do dia e performance do salão.</p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <button type="button" class="btn-primary-admin" onclick="openModal('modalNewAppointment')">
                    <i class="fa-solid fa-plus"></i> Novo Agendamento
                </button>
                <button type="button" class="btn-secondary-admin" onclick="openModal('modalNewService')">
                    <i class="fa-solid fa-scissors"></i> Novo Serviço
                </button>
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
                    <h3 class="kpi-value">14</h3>
                    <span class="kpi-badge positive"><i class="fa-solid fa-arrow-trend-up"></i> +25% vs média</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon-box">
                    <i class="fa-solid fa-brazilian-real-sign"></i>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Faturamento Hoje</span>
                    <h3 class="kpi-value">R$ 890,00</h3>
                    <span class="kpi-badge positive"><i class="fa-solid fa-chart-pie"></i> 90% da meta diária</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon-box">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Atendimentos Finalizados</span>
                    <h3 class="kpi-value">8 / 14</h3>
                    <span class="kpi-badge info"><i class="fa-solid fa-clock"></i> 6 restantes hoje</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon-box">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Taxa de Ocupação</span>
                    <h3 class="kpi-value">85%</h3>
                    <span class="kpi-badge positive"><i class="fa-solid fa-fire"></i> Alta procura hoje</span>
                </div>
            </div>
        </div>

        <!-- Tabela de Atendimentos de Hoje -->
        <div class="admin-table-card">
            <div class="table-card-header">
                <h2 class="table-card-title">
                    <i class="fa-solid fa-clock-rotate-left"></i> Agenda de Hoje (Quarta-feira)
                </h2>
                <a href="adminAgendamentos.php" class="btn-secondary-admin" style="font-size: 0.82rem; padding: 6px 16px;">
                    Ver Todos os Agendamentos <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Serviço</th>
                            <th>Barbeiro</th>
                            <th>Horário</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th style="text-align: right;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="appointment-row" data-status="in-progress">
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
                            <td><strong style="color: var(--gold);">16:00</strong> (Em atendimento)</td>
                            <td><strong>R$ 70,00</strong></td>
                            <td><span class="badge-status in-progress"><i class="fa-solid fa-spinner fa-spin"></i> Em Cadeira</span></td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511987654321" target="_blank" class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <button type="button" class="btn-icon-action success" onclick="concludeAppointment(this)" title="Concluir Atendimento">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button type="button" class="btn-icon-action danger" onclick="cancelAdminAppointment(this)" title="Cancelar">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="appointment-row" data-status="confirmed">
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
                            <td>Lucas Silva (Fade Master)</td>
                            <td><strong style="color: var(--text-white);">17:00</strong></td>
                            <td><strong>R$ 45,00</strong></td>
                            <td><span class="badge-status confirmed"><i class="fa-regular fa-clock"></i> Confirmado</span></td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511971238899" target="_blank" class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <button type="button" class="btn-icon-action success" onclick="concludeAppointment(this)" title="Concluir Atendimento">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button type="button" class="btn-icon-action danger" onclick="cancelAdminAppointment(this)" title="Cancelar">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="appointment-row" data-status="confirmed">
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
                            <td><strong style="color: var(--text-white);">17:45</strong></td>
                            <td><strong>R$ 35,00</strong></td>
                            <td><span class="badge-status confirmed"><i class="fa-regular fa-clock"></i> Confirmado</span></td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511965442211" target="_blank" class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <button type="button" class="btn-icon-action success" onclick="concludeAppointment(this)" title="Concluir Atendimento">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button type="button" class="btn-icon-action danger" onclick="cancelAdminAppointment(this)" title="Cancelar">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="appointment-row" data-status="completed">
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
                            <td>Lucas Silva (Fade Master)</td>
                            <td><strong style="color: var(--text-dim);">14:30</strong></td>
                            <td><strong>R$ 45,00</strong></td>
                            <td><span class="badge-status completed"><i class="fa-solid fa-check"></i> Concluído</span></td>
                            <td style="text-align: right;">
                                <div class="table-actions-group" style="justify-content: flex-end;">
                                    <a href="https://wa.me/5511955551234" target="_blank" class="btn-icon-action whatsapp" title="Conversar no WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <span style="font-size: 0.8rem; color: var(--success);"><i class="fa-solid fa-check-double"></i> Pago</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal 1: Novo Agendamento Presencial / Manual -->
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
                    <button type="button" class="btn-secondary-admin" onclick="closeModal('modalNewAppointment')">Cancelar</button>
                    <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-check"></i> Agendar Cliente</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Novo Serviço -->
    <div class="modal-overlay" id="modalNewService">
        <div class="admin-modal">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fa-solid fa-scissors"></i> Cadastrar Novo Serviço</h3>
                <button type="button" class="btn-close-modal" onclick="closeModal('modalNewService')">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form id="formNewService">
                <div class="modal-body">
                    <div class="form-grid-2">
                        <div class="form-group full-width">
                            <label class="form-label">Nome do Serviço *</label>
                            <input type="text" class="form-input" placeholder="Ex: Pigmentação de Barba" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Preço (R$) *</label>
                            <input type="text" class="form-input" placeholder="Ex: 50,00" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Duração Estimada *</label>
                            <select class="form-select" required>
                                <option>20 minutos</option>
                                <option>30 minutos</option>
                                <option selected>45 minutos</option>
                                <option>1 hora</option>
                                <option>1 hora e 15 minutos</option>
                            </select>
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Descrição Detalhada</label>
                            <textarea class="form-textarea" placeholder="Descreva os diferenciais e o que inclui o serviço..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-admin" onclick="closeModal('modalNewService')">Cancelar</button>
                    <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-floppy-disk"></i> Salvar Serviço</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Geral do Administrador -->
    <script src="../assets/js/admin.js" defer></script>
</body>

</html>

