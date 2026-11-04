<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Agendar Horário</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS Customizado de Agendamento -->
    <link rel="stylesheet" href="../assets/css/agendar.css">
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
        <button type="button" class="sidebar-nav-btn" onclick="window.location.href='agendamentos.php'">
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
            <span class="current"><i class="fa-solid fa-calendar-plus"></i> Agendar Horário</span>
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
        <div class="booking-page-header">
            <h1 class="booking-title">Agendar Novo Horário</h1>
            <p class="booking-subtitle">Personalize seu atendimento escolhendo o serviço e o horário ideal.</p>
        </div>

        <div class="booking-grid">
            <!-- Coluna da Esquerda: Etapas -->
            <div class="booking-steps-container">

                <!-- ETAPA 1: Serviços -->
                <div class="step-card">
                    <div class="step-card-header">
                        <span class="step-number">1</span>
                        <h2 class="step-title">Escolha o Serviço</h2>
                        <span class="step-desc">Selecione o corte ou cuidado desejado</span>
                    </div>

                    <div class="services-selection-grid">
                        <!-- Serviço 1: Corte Masculino -->
                        <div class="service-option-card"
                            onclick="selectService(this, 'Corte Masculino (Fade/Social)', 'R$ 45,00', '45 min')">
                            <div class="service-image-box">
                                <img src="../img/corteMasculino.jpg" alt="Corte Masculino">
                                <div class="service-image-overlay"></div>
                            </div>
                            <div class="service-info-box">
                                <h3 class="service-name">Corte Masculino</h3>
                                <div class="service-details">
                                    <span><i class="fa-regular fa-clock"></i> 45 min</span>
                                    <span><i class="fa-solid fa-scissors"></i> Degradê / Social</span>
                                </div>
                                <div class="service-footer">
                                    <span class="service-price">R$ 45,00</span>
                                    <div class="service-radio-indicator"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Serviço 2: Barba -->
                        <div class="service-option-card"
                            onclick="selectService(this, 'Barba Completa & Toalha Quente', 'R$ 35,00', '30 min')">
                            <div class="service-image-box">
                                <img src="../img/barba.jpg" alt="Barba">
                                <div class="service-image-overlay"></div>
                            </div>
                            <div class="service-info-box">
                                <h3 class="service-name">Barba Completa</h3>
                                <div class="service-details">
                                    <span><i class="fa-regular fa-clock"></i> 30 min</span>
                                    <span><i class="fa-solid fa-spray-can-sparkles"></i> Toalha Quente</span>
                                </div>
                                <div class="service-footer">
                                    <span class="service-price">R$ 35,00</span>
                                    <div class="service-radio-indicator"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Serviço 3: Combo Cabelo + Barba (Destaque Inicial) -->
                        <div class="service-option-card selected"
                            onclick="selectService(this, 'Combo Cabelo + Barba Na Régua', 'R$ 70,00', '1h 15min')">
                            <span class="service-badge-popular"><i class="fa-solid fa-star"></i> Mais Pedido</span>
                            <div class="service-image-box">
                                <img src="../img/cabeloBarba.jpg" alt="Cabelo e Barba">
                                <div class="service-image-overlay"></div>
                            </div>
                            <div class="service-info-box">
                                <h3 class="service-name">Cabelo + Barba</h3>
                                <div class="service-details">
                                    <span><i class="fa-regular fa-clock"></i> 1h 15min</span>
                                    <span><i class="fa-solid fa-star"></i> Experiência Completa</span>
                                </div>
                                <div class="service-footer">
                                    <span class="service-price">R$ 70,00</span>
                                    <div class="service-radio-indicator"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Serviço 4: Acabamento / Pezinho -->
                        <div class="service-option-card"
                            onclick="selectService(this, 'Acabamento & Sobrancelha', 'R$ 25,00', '20 min')">
                            <div class="service-image-box">
                                <img src="../img/sobrancelha.png" alt="Acabamento e Sobrancelha">
                                <div class="service-image-overlay"></div>
                            </div>
                            <div class="service-info-box">
                                <h3 class="service-name">Acabamento & Sobrancelha</h3>
                                <div class="service-details">
                                    <span><i class="fa-regular fa-clock"></i> 20 min</span>
                                    <span><i class="fa-solid fa-check"></i> Navalha & Linhas</span>
                                </div>
                                <div class="service-footer">
                                    <span class="service-price">R$ 25,00</span>
                                    <div class="service-radio-indicator"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ETAPA 2: Data e Horário -->
                <div class="step-card">
                    <div class="step-card-header">
                        <span class="step-number">2</span>
                        <h2 class="step-title">Data & Horário</h2>
                        <span class="step-desc">Escolha o melhor momento na sua rotina</span>
                    </div>

                    <!-- Seletor de Dias da Semana -->
                    <div class="date-selector-row">
                        <div class="date-pill selected" onclick="selectDate(this, 'Hoje, 29 de Outubro')">
                            <span class="date-pill-weekday">Hoje</span>
                            <span class="date-pill-day">29</span>
                            <span class="date-pill-month">Out</span>
                        </div>
                        <div class="date-pill" onclick="selectDate(this, 'Amanhã, 30 de Outubro')">
                            <span class="date-pill-weekday">Qui</span>
                            <span class="date-pill-day">30</span>
                            <span class="date-pill-month">Out</span>
                        </div>
                        <div class="date-pill" onclick="selectDate(this, 'Sexta, 31 de Outubro')">
                            <span class="date-pill-weekday">Sex</span>
                            <span class="date-pill-day">31</span>
                            <span class="date-pill-month">Out</span>
                        </div>
                        <div class="date-pill" onclick="selectDate(this, 'Sábado, 01 de Novembro')">
                            <span class="date-pill-weekday">Sáb</span>
                            <span class="date-pill-day">01</span>
                            <span class="date-pill-month">Nov</span>
                        </div>
                        <div class="date-pill" onclick="selectDate(this, 'Segunda, 03 de Novembro')">
                            <span class="date-pill-weekday">Seg</span>
                            <span class="date-pill-day">03</span>
                            <span class="date-pill-month">Nov</span>
                        </div>
                        <div class="date-pill" onclick="selectDate(this, 'Terça, 04 de Novembro')">
                            <span class="date-pill-weekday">Ter</span>
                            <span class="date-pill-day">04</span>
                            <span class="date-pill-month">Nov</span>
                        </div>
                    </div>

                    <!-- Grade de Horários -->
                    <div class="time-slots-container">
                        <div class="time-period-group">
                            <span class="time-period-title"><i class="fa-regular fa-sun"></i> Manhã</span>
                            <div class="time-slots-grid">
                                <button type="button" class="time-slot-btn"
                                    onclick="selectTime(this, '09:00')">09:00</button>
                                <button type="button" class="time-slot-btn disabled" disabled>09:45</button>
                                <button type="button" class="time-slot-btn"
                                    onclick="selectTime(this, '10:30')">10:30</button>
                                <button type="button" class="time-slot-btn"
                                    onclick="selectTime(this, '11:15')">11:15</button>
                            </div>
                        </div>

                        <div class="time-period-group">
                            <span class="time-period-title"><i class="fa-solid fa-cloud-sun"></i> Tarde</span>
                            <div class="time-slots-grid">
                                <button type="button" class="time-slot-btn"
                                    onclick="selectTime(this, '13:30')">13:30</button>
                                <button type="button" class="time-slot-btn"
                                    onclick="selectTime(this, '14:15')">14:15</button>
                                <button type="button" class="time-slot-btn"
                                    onclick="selectTime(this, '15:00')">15:00</button>
                                <button type="button" class="time-slot-btn selected"
                                    onclick="selectTime(this, '16:00')">16:00</button>
                                <button type="button" class="time-slot-btn"
                                    onclick="selectTime(this, '17:00')">17:00</button>
                            </div>
                        </div>

                        <div class="time-period-group">
                            <span class="time-period-title"><i class="fa-solid fa-moon"></i> Noite</span>
                            <div class="time-slots-grid">
                                <button type="button" class="time-slot-btn"
                                    onclick="selectTime(this, '18:00')">18:00</button>
                                <button type="button" class="time-slot-btn"
                                    onclick="selectTime(this, '18:45')">18:45</button>
                                <button type="button" class="time-slot-btn"
                                    onclick="selectTime(this, '19:30')">19:30</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Coluna da Direita: Resumo do Agendamento -->
            <aside class="booking-summary-card">
                <h3 class="summary-title"><i class="fa-regular fa-clipboard"></i> Resumo</h3>

                <div class="summary-items-list">
                    <div class="summary-item">
                        <div class="summary-item-icon">
                            <i class="fa-solid fa-scissors"></i>
                        </div>
                        <div class="summary-item-content">
                            <span class="summary-item-label">Serviço</span>
                            <p class="summary-item-value" id="summaryService">Combo Cabelo + Barba Na Régua</p>
                        </div>
                    </div>

                    <div class="summary-item">
                        <div class="summary-item-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="summary-item-content">
                            <span class="summary-item-label">Local</span>
                            <p class="summary-item-value">Barbearia Na Régua</p>
                        </div>
                    </div>

                    <div class="summary-item">
                        <div class="summary-item-icon">
                            <i class="fa-regular fa-calendar"></i>
                        </div>
                        <div class="summary-item-content">
                            <span class="summary-item-label">Data</span>
                            <p class="summary-item-value" id="summaryDate">Hoje, 29 de Outubro</p>
                        </div>
                    </div>

                    <div class="summary-item">
                        <div class="summary-item-icon">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <div class="summary-item-content">
                            <span class="summary-item-label">Horário & Duração</span>
                            <p class="summary-item-value" id="summaryTime">16:00 (Duração: 1h 15min)</p>
                        </div>
                    </div>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-total-row">
                    <span class="summary-total-label">Total a Pagar:</span>
                    <span class="summary-total-value" id="summaryPrice">R$ 70,00</span>
                </div>

                <button type="button" class="btn-confirm-booking" onclick="confirmBooking()">
                    <i class="fa-solid fa-check"></i> Confirmar Horário
                </button>

                <p class="summary-note">
                    <i class="fa-solid fa-shield-halved"></i> Cancelamento grátis até 2h antes
                </p>
            </aside>
        </div>
    </main>

    <!-- Modal de Confirmação com Sucesso -->
    <div class="modal-overlay" id="successModal">
        <div class="success-modal-card">
            <div class="modal-success-icon">
                <i class="fa-solid fa-check"></i>
            </div>
            <h3 class="modal-title">Horário Confirmado!</h3>
            <p class="modal-text">Seu agendamento foi realizado com sucesso na Barbearia Na Régua. Enviamos os detalhes
                e lembretes para o seu perfil e WhatsApp.</p>
            <div class="modal-actions">
                <a href="agendamentos.php" class="btn-modal-primary">Ver Meus Agendamentos</a>
            </div>
        </div>
    </div>

    <!-- Script de Agendamento -->
    <script src="../assets/js/agendar.js" defer></script>
</body>

</html>