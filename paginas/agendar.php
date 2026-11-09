<?php
require_once __DIR__ . '/../includes/auth.php';
exigirLogin();

require_once __DIR__ . '/../config/database.php';

$servicos = $pdo->query('SELECT id_servico, nome, preco, duracao_minutos FROM servico WHERE ativo = true ORDER BY nome')->fetchAll();
$horarios = $pdo->query('SELECT dia_semana, hora_inicio, hora_fim FROM horario_funcionamento WHERE ativo = true ORDER BY dia_semana, hora_inicio')->fetchAll();

$diasAbertos = array_unique(array_column($horarios, 'dia_semana'));
$nomesDia = [1 => 'Seg', 2 => 'Ter', 3 => 'Qua', 4 => 'Qui', 5 => 'Sex', 6 => 'Sáb', 7 => 'Dom'];
$nomesMes = [1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr', 5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago', 9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'];

$dias = [];
$data = new DateTime('today');

while (count($dias) < 6) {
    $numeroDia = (int) $data->format('N');

    if (in_array($numeroDia, $diasAbertos)) {
        $dias[] = [
            'data' => $data->format('Y-m-d'),
            'semana' => $nomesDia[$numeroDia],
            'dia' => $data->format('d'),
            'mes' => $nomesMes[(int) $data->format('n')],
        ];
    }

    $data->modify('+1 day');
}

?>

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
                        <?php foreach ($servicos as $servico): ?>
                            <div class="service-option-card" data-id="<?= (int) $servico['id_servico'] ?>"
                                data-nome="<?= htmlspecialchars($servico['nome']) ?>"
                                data-preco="<?= (float) $servico['preco'] ?>"
                                data-duracao="<?= (int) $servico['duracao_minutos'] ?>">
                                <div class="service-info-box">
                                    <h3 class="service-name"><?= htmlspecialchars($servico['nome']) ?></h3>
                                    <div class="service-details">
                                        <span><i class="fa-regular fa-clock"></i> <?= (int) $servico['duracao_minutos'] ?>
                                            min</span>
                                    </div>
                                    <div class="service-footer">
                                        <span class="service-price">R$
                                            <?= number_format((float) $servico['preco'], 2, ',', ',') ?></span>
                                        <div class="service-radio-indicator"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
                        <?php foreach ($dias as $i => $dia): ?>
                            <div class="date-pill <?= $i === 0 ? 'selected' : '' ?>"
                                data-data="<?= htmlspecialchars($dia['data']) ?>">
                                <span class="date-pill-weekday"><?= htmlspecialchars($dia['semana']) ?></span>
                                <span class="date-pill-day"><?= htmlspecialchars($dia['dia']) ?></span>
                                <span class="date-pill-month"><?= htmlspecialchars($dia['mes']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Grade de Horários -->
                    <div class="time-slots-container" id="horariosContainer">
                        <p>Escolha um serviço e uma data para ver os horários.</p>
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

                <p id="erroAgendamento"></p>
                <br>
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
            <p class="modal-text">Seu agendamento foi registrado e aguarda confirmação da barbearia.</p>
            <div class="modal-actions">
                <a href="agendamentos.php" class="btn-modal-primary">Ver Meus Agendamentos</a>
            </div>
        </div>
    </div>

    <!-- Script de Agendamento -->
    <script src="../assets/js/agendar.js" defer></script>
</body>

</html>