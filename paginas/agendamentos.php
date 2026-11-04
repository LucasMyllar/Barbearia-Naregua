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

        <a href="login.php" class="sidebar-logout">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <span>Sair</span>
        </a>
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
                <p class="page-subtitle">Acompanhe seus horários marcados, consulte o histórico e repita seus cortes favoritos.</p>
            </div>
            <a href="agendar.php" class="btn-new-appointment">
                <i class="fa-solid fa-calendar-plus"></i> Novo Agendamento
            </a>
        </div>

        <!-- Estatísticas / Resumo de Fidelidade -->
        <div class="stats-cards-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-regular fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Próximo Horário</span>
                    <h3 class="stat-number">1 Ativo</h3>
                    <span class="stat-detail"><i class="fa-solid fa-clock"></i> Hoje às 16:00</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-scissors"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Cortes Realizados</span>
                    <h3 class="stat-number">6 Cortes</h3>
                    <span class="stat-detail"><i class="fa-solid fa-trophy"></i> Cliente Fiel</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-regular fa-star"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Avaliação</span>
                    <h3 class="stat-number">5.0 ★</h3>
                    <span class="stat-detail"><i class="fa-solid fa-check"></i> Experiência Na Régua</span>
                </div>
            </div>
        </div>

        <!-- Abas de Filtro -->
        <div class="appointments-tabs-nav">
            <button type="button" class="tab-btn active" onclick="filterAppointments('active', this)">
                <i class="fa-regular fa-calendar-check"></i> Próximos Horários <span class="tab-counter">1</span>
            </button>
            <button type="button" class="tab-btn" onclick="filterAppointments('history', this)">
                <i class="fa-solid fa-clock-rotate-left"></i> Histórico de Cortes <span class="tab-counter">3</span>
            </button>
            <button type="button" class="tab-btn" onclick="filterAppointments('canceled', this)">
                <i class="fa-regular fa-calendar-xmark"></i> Cancelados <span class="tab-counter">0</span>
            </button>
        </div>

        <!-- Listagem de Agendamentos -->
        <div class="appointments-list" id="appointmentsList">
            <!-- AGENDAMENTO ATIVO / PRÓXIMO -->
            <div class="appointment-item-card confirmed appt-item" data-type="active">
                <div class="appointment-date-badge">
                    <span class="date-badge-day">29</span>
                    <span class="date-badge-month">Outubro</span>
                    <span class="date-badge-time"><i class="fa-regular fa-clock"></i> 16:00</span>
                </div>

                <div class="appointment-details">
                    <div class="appointment-header-row">
                        <h2 class="appointment-service-title">Combo Cabelo + Barba Na Régua</h2>
                        <span class="status-badge status-confirmed">Confirmado</span>
                    </div>

                    <div class="appointment-meta-info">
                        <span class="meta-item"><i class="fa-solid fa-user-check"></i> Barbeiro: Lucas Silva</span>
                        <span class="meta-item"><i class="fa-regular fa-hourglass-half"></i> Duração: 1h 15min</span>
                        <span class="meta-item"><i class="fa-solid fa-location-dot"></i> Cadeira 02 • Na Régua Barber</span>
                    </div>

                    <span class="appointment-price-tag">R$ 70,00</span>
                </div>

                <div class="appointment-actions">
                    <a href="agendar.php" class="btn-action-reschedule">
                        <i class="fa-regular fa-calendar-days"></i> Reagendar
                    </a>
                    <button type="button" class="btn-action-cancel" onclick="cancelAppointment(this)">
                        <i class="fa-regular fa-circle-xmark"></i> Cancelar
                    </button>
                </div>
            </div>

            <!-- HISTÓRICO 1 -->
            <div class="appointment-item-card completed appt-item" data-type="history" style="display: none;">
                <div class="appointment-date-badge">
                    <span class="date-badge-day">15</span>
                    <span class="date-badge-month">Outubro</span>
                    <span class="date-badge-time"><i class="fa-regular fa-clock"></i> 14:30</span>
                </div>

                <div class="appointment-details">
                    <div class="appointment-header-row">
                        <h2 class="appointment-service-title">Corte Masculino Degradê Fade</h2>
                        <span class="status-badge status-done">Concluído</span>
                    </div>

                    <div class="appointment-meta-info">
                        <span class="meta-item"><i class="fa-solid fa-user-check"></i> Barbeiro: Carlos Navalha</span>
                        <span class="meta-item"><i class="fa-solid fa-star" style="color: var(--gold);"></i> Avaliação: 5.0 (Excelente)</span>
                    </div>

                    <span class="appointment-price-tag">R$ 45,00</span>
                </div>

                <div class="appointment-actions">
                    <a href="agendar.php" class="btn-action-repeat">
                        <i class="fa-solid fa-rotate-right"></i> Repetir Corte
                    </a>
                </div>
            </div>

            <!-- HISTÓRICO 2 -->
            <div class="appointment-item-card completed appt-item" data-type="history" style="display: none;">
                <div class="appointment-date-badge">
                    <span class="date-badge-day">28</span>
                    <span class="date-badge-month">Setembro</span>
                    <span class="date-badge-time"><i class="fa-regular fa-clock"></i> 17:00</span>
                </div>

                <div class="appointment-details">
                    <div class="appointment-header-row">
                        <h2 class="appointment-service-title">Barba Terapia com Toalha Quente</h2>
                        <span class="status-badge status-done">Concluído</span>
                    </div>

                    <div class="appointment-meta-info">
                        <span class="meta-item"><i class="fa-solid fa-user-check"></i> Barbeiro: Carlos Navalha</span>
                        <span class="meta-item"><i class="fa-solid fa-star" style="color: var(--gold);"></i> Avaliação: 5.0 (Excelente)</span>
                    </div>

                    <span class="appointment-price-tag">R$ 35,00</span>
                </div>

                <div class="appointment-actions">
                    <a href="agendar.php" class="btn-action-repeat">
                        <i class="fa-solid fa-rotate-right"></i> Repetir Corte
                    </a>
                </div>
            </div>

            <!-- HISTÓRICO 3 -->
            <div class="appointment-item-card completed appt-item" data-type="history" style="display: none;">
                <div class="appointment-date-badge">
                    <span class="date-badge-day">10</span>
                    <span class="date-badge-month">Setembro</span>
                    <span class="date-badge-time"><i class="fa-regular fa-clock"></i> 11:00</span>
                </div>

                <div class="appointment-details">
                    <div class="appointment-header-row">
                        <h2 class="appointment-service-title">Combo Cabelo + Barba Na Régua</h2>
                        <span class="status-badge status-done">Concluído</span>
                    </div>

                    <div class="appointment-meta-info">
                        <span class="meta-item"><i class="fa-solid fa-user-check"></i> Barbeiro: Lucas Silva</span>
                        <span class="meta-item"><i class="fa-solid fa-star" style="color: var(--gold);"></i> Avaliação: 5.0 (Excelente)</span>
                    </div>

                    <span class="appointment-price-tag">R$ 70,00</span>
                </div>

                <div class="appointment-actions">
                    <a href="agendar.php" class="btn-action-repeat">
                        <i class="fa-solid fa-rotate-right"></i> Repetir Corte
                    </a>
                </div>
            </div>

            <!-- MENSAGEM VAZIA PARA CANCELADOS -->
            <div id="emptyMessage" style="display: none; text-align: center; padding: 60px 20px;">
                <i class="fa-regular fa-calendar-check" style="font-size: 3rem; color: var(--gold); margin-bottom: 16px; display: inline-block;"></i>
                <p style="font-size: 1.2rem; font-weight: 600; color: var(--text-white);">Nenhum agendamento nesta categoria.</p>
                <span style="color: var(--text-gray-muted); font-size: 0.95rem;">Você não possui horários cancelados.</span>
            </div>
        </div>
    </main>

    <!-- Script de Agendamentos -->
    <script src="../assets/js/agendamentos.js" defer></script>
</body>

</html>
