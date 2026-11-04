<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Início</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS Customizado do Painel do Cliente -->
    <link rel="stylesheet" href="../assets/css/inicioCliente.css">
</head>

<body>
    <!-- Barra lateral -->
    <section class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon-wrapper">
                <i class="fa-solid fa-scissors brand-icon"></i>
            </div>
            <h1>Na Régua</h1>
            <span>Estilo • Confiança • Você</span>
        </div>

        <button type="button" class="sidebar-nav-btn active" onclick="window.location.href='inicioCliente.php'">
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

    <!-- Cabeçalho superior -->
    <header>
        <p><i class="fa-solid fa-house"></i> Início</p>

        <div class="header-user-info">
            <div class="header-user-badge">
                <i class="fa-solid fa-circle-user"></i>
                <span>Minha Conta</span>
            </div>
        </div>
    </header>

    <!-- Conteúdo principal -->
    <main>
        <h1 class="client-name-title">
           Lucas
            <span class="client-name-subtitle">Seja bem-vindo(a) ao seu painel exclusivo</span>
        </h1>

        <button type="button" class="btn-agendar" onclick="window.location.href='agendar.php'">
            <i class="fa-solid fa-calendar-plus"></i> Agendar
        </button>

        <!-- Card de Agendamentos conforme o wireframe -->
        <div class="card-agendamentos">
            <div class="card-agendamentos-header">
                <h2 class="card-agendamentos-title">
                    <i class="fa-regular fa-calendar-check"></i> Agendamentos
                </h2>
                <span class="card-agendamentos-badge">
                    <i class="fa-regular fa-clock"></i> 0 Pendentes
                </span>
            </div>

            <div class="empty-state-content">
                <div class="empty-icon-box">
                    <i class="fa-regular fa-calendar-xmark"></i>
                </div>
                <p class="empty-state-text">Nenhum agendamento realizado até o momento!</p>
                <span class="empty-state-desc">Você ainda não possui horários marcados. Escolha o serviço ideal e agende com nossos melhores profissionais.</span>
                <a href="agendar.php" class="btn-schedule-empty">
                    <i class="fa-solid fa-plus"></i> Fazer Agendamento
                </a>
            </div>
        </div>
    </main>

    <!-- Script do Painel do Cliente -->
    <script src="../assets/js/inicioCliente.js" defer></script>
</body>

</html>