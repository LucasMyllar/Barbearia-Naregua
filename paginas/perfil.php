<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Meu Perfil</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS Customizado de Perfil -->
    <link rel="stylesheet" href="../assets/css/perfil.css">
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
        <button type="button" class="sidebar-nav-btn active" onclick="window.location.href='perfil.php'">
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
            <span class="current"><i class="fa-regular fa-user"></i> Meu Perfil</span>
        </div>

        <div class="header-user-info">
            <div class="header-user-badge">
                <i class="fa-solid fa-circle-user"></i>
                <span>Lucas Silva</span>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main>
        <!-- Hero / Resumo do Perfil -->
        <div class="profile-hero-card">
            <div class="profile-identity-group">
                <div class="profile-avatar-container">
                    <div class="profile-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <button type="button" class="btn-change-avatar" title="Alterar foto">
                        <i class="fa-solid fa-camera"></i>
                    </button>
                </div>

                <div class="profile-identity-details">
                    <h1>Lucas Silva</h1>
                    <p><i class="fa-regular fa-envelope"></i> lucas.silva@email.com • <i class="fa-brands fa-whatsapp"></i> (11) 98765-4321</p>
                    <span class="profile-member-since">Membro da Na Régua desde Outubro de 2024</span>
                </div>
            </div>

            <div class="profile-quick-metrics">
                <div class="metric-badge">
                    <span>Total de Cortes</span>
                    <strong>6</strong>
                </div>
                <div class="metric-badge">
                    <span>Pontualidade</span>
                    <strong>100%</strong>
                </div>
                <div class="metric-badge">
                    <span>Avaliação</span>
                    <strong>5.0 ★</strong>
                </div>
            </div>
        </div>

        <!-- Grade de Conteúdo do Perfil -->
        <div class="profile-content-grid">
            <!-- Coluna da Esquerda: Dados Pessoais & Senha -->
            <div class="profile-forms-column">

                <!-- Seção 1: Dados Cadastrais -->
                <div class="profile-section-card">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fa-regular fa-id-card"></i> Dados Pessoais
                        </h2>
                    </div>

                    <form id="formPersonalData">
                        <div class="form-grid-2">
                            <div class="form-group full-width">
                                <label class="form-label" for="profileName">Nome Completo</label>
                                <input type="text" id="profileName" class="form-input" value="Lucas Silva" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="profileEmail">E-mail</label>
                                <input type="email" id="profileEmail" class="form-input" value="lucas.silva@email.com" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="profilePhone">Telefone / WhatsApp</label>
                                <input type="tel" id="profilePhone" class="form-input" value="(11) 98765-4321" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="profileCpf">CPF (Protegido)</label>
                                <input type="text" id="profileCpf" class="form-input" value="***.456.789-00" disabled>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="profileBirth">Data de Nascimento</label>
                                <input type="date" id="profileBirth" class="form-input" value="1996-08-15">
                            </div>
                        </div>

                        <button type="submit" class="btn-save-profile">
                            <i class="fa-solid fa-floppy-disk"></i> Salvar Alterações
                        </button>
                    </form>
                </div>

                <!-- Seção 2: Segurança / Alterar Senha -->
                <div class="profile-section-card">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fa-solid fa-lock"></i> Segurança & Senha
                        </h2>
                    </div>

                    <form id="formPassword">
                        <div class="form-grid-2">
                            <div class="form-group full-width">
                                <label class="form-label" for="currentPassword">Senha Atual</label>
                                <input type="password" id="currentPassword" class="form-input" placeholder="••••••••" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="newPassword">Nova Senha</label>
                                <input type="password" id="newPassword" class="form-input" placeholder="Nova senha (mínimo 6 caracteres)" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="confirmPassword">Confirmar Nova Senha</label>
                                <input type="password" id="confirmPassword" class="form-input" placeholder="Repita a nova senha" required>
                            </div>
                        </div>

                        <button type="submit" class="btn-save-profile">
                            <i class="fa-solid fa-key"></i> Atualizar Senha
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </main>

    <!-- Script de Perfil -->
    <script src="../assets/js/perfil.js" defer></script>
</body>

</html>
