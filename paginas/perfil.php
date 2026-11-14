<?php
require_once __DIR__ . '/../includes/auth.php';
exigirLogin();

require_once __DIR__ . '/../config/database.php';

$id = (int) $_SESSION['id_usuario'];

$stmt = $pdo->prepare('SELECT * FROM app_privado.meu_perfil(CAST(:id AS integer))');
$stmt->execute([':id' => $id]);
$perfil = $stmt->fetch();

if (!$perfil) {
    http_response_code(404);
    exit('Perfil não encontrado.');
}

$stmt = $pdo->prepare('SELECT status FROM app_privado.meus_agendamentos(CAST(:id AS integer))');
$stmt->execute([':id' => $id]);
$status = $stmt->fetchAll(PDO::FETCH_COLUMN);

$totalAgendamentos = count($status);
$concluidos = count(array_filter($status, fn($s) => $s === 'CONCLUIDO'));
$proximos = count(array_filter($status, fn($s) => in_array($s, ['PENDENTE', 'CONFIRMADO'], true)));
?>

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
            <span class="current"><i class="fa-regular fa-user"></i> Meu Perfil</span>
        </div>

        <div class="header-user-info">
            <div class="header-user-badge">
                <i class="fa-solid fa-circle-user"></i>
                <span><?= htmlspecialchars($perfil['nome']) ?></span>
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
                </div>

                <div class="profile-identity-details">
                    <h1><?= htmlspecialchars($perfil['nome']) ?></h1>
                    <p><i class="fa-regular fa-envelope"></i> <?= htmlspecialchars($perfil['email']) ?>
                        <?php if (!empty($perfil['telefone'])): ?>
                            • <i class="fa-brands fa-whatsapp"></i> <?= htmlspecialchars($perfil['telefone']) ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="profile-quick-metrics">
                <div class="metric-badge">
                    <span>Agendamentos</span>
                    <strong><?= $totalAgendamentos ?></strong>
                </div>
                <div class="metric-badge">
                    <span>Concluídos</span>
                    <strong><?= $concluidos ?></strong>
                </div>
                <div class="metric-badge">
                    <span>Próximos</span>
                    <strong><?= $proximos ?></strong>
                </div>
            </div>
        </div>

        <!-- Grade de Conteúdo do Perfil -->
        <div class="profile-content-grid">
            <div class="profile-forms-column">

                <!-- Seção 1: Dados Cadastrais -->
                <div class="profile-section-card">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fa-regular fa-id-card"></i> Dados Pessoais
                        </h2>
                    </div>

                    <form id="formPerfil">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <div class="form-grid-2">
                            <div class="form-group full-width">
                                <label class="form-label" for="profileName">Nome Completo</label>
                                <input type="text" id="profileName" name="nome" class="form-input" maxlength="100"
                                    value="<?= htmlspecialchars($perfil['nome'], ENT_QUOTES) ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="profileEmail">E-mail (não pode ser alterado)</label>
                                <input type="email" id="profileEmail" class="form-input"
                                    value="<?= htmlspecialchars($perfil['email'], ENT_QUOTES) ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="profilePhone">Telefone / WhatsApp</label>
                                <input type="tel" id="profilePhone" name="telefone" class="form-input" maxlength="20"
                                    value="<?= htmlspecialchars((string) ($perfil['telefone'] ?? ''), ENT_QUOTES) ?>">
                            </div>
                        </div>

                        <p id="msgPerfil"></p>

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

                    <form id="formSenha">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <div class="form-grid-2">
                            <div class="form-group full-width">
                                <label class="form-label" for="currentPassword">Senha Atual</label>
                                <input type="password" id="currentPassword" name="atual" class="form-input"
                                    placeholder="••••••••" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="newPassword">Nova Senha</label>
                                <input type="password" id="newPassword" name="nova" class="form-input"
                                    placeholder="Mínimo 8 caracteres" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="confirmPassword">Confirmar Nova Senha</label>
                                <input type="password" id="confirmPassword" name="confirmar" class="form-input"
                                    placeholder="Repita a nova senha" required>
                            </div>
                        </div>

                        <p id="msgSenha"></p>

                        <button type="submit" class="btn-save-profile">
                            <i class="fa-solid fa-key"></i> Atualizar Senha
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </main>

    <!-- Scripts de Perfil -->
    <script src="../assets/js/perfil.js" defer></script>
    <script src="../assets/js/perfilAcoes.js" defer></script>
</body>

</html>