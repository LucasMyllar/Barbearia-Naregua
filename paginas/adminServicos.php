<?php
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();
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
                <h1 class="page-title">Serviços & Tabela de Preços</h1>
                <p class="page-subtitle">Configure os serviços disponíveis para agendamento, durações estimadas e preços
                    vigentes.</p>
            </div>
            <button type="button" class="btn-primary-admin" onclick="openModal('modalNewService')">
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
                Exibindo <strong>5 serviços</strong> cadastrados no catálogo
            </div>
        </div>

        <!-- Grade de Cards de Serviços -->
        <div class="admin-cards-grid">
            <!-- Serviço 1: Combo Na Régua -->
            <div class="admin-item-card">
                <div>
                    <div class="card-item-top">
                        <div class="item-icon-avatar">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </div>
                        <span class="badge-status active"><i class="fa-solid fa-circle-check"></i> Ativo</span>
                    </div>

                    <div class="item-title-group">
                        <h3>Combo Cabelo + Barba Na Régua</h3>
                    </div>

                    <p class="item-desc">
                        A experiência completa: corte de cabelo personalizado degradê/social com lavagem especial, barba
                        esculpida na navalha com toalha quente e massagem facial.
                    </p>
                </div>

                <div>
                    <div class="item-meta-row">
                        <div>
                            <span class="item-duration"><i class="fa-regular fa-clock"></i> 1h 15min de
                                atendimento</span>
                            <div class="item-price">R$ 70,00</div>
                        </div>
                        <div class="table-actions-group">
                            <button type="button" class="btn-icon-action gold" onclick="toggleServiceStatus(this)"
                                title="Pausar ou Ativar Serviço">
                                <i class="fa-solid fa-pause"></i>
                            </button>
                            <button type="button" class="btn-icon-action danger"
                                onclick="deleteAdminCard(this, 'serviço')" title="Remover Serviço">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serviço 2: Corte Degradê / Fade -->
            <div class="admin-item-card">
                <div>
                    <div class="card-item-top">
                        <div class="item-icon-avatar">
                            <i class="fa-solid fa-scissors"></i>
                        </div>
                        <span class="badge-status active"><i class="fa-solid fa-circle-check"></i> Ativo</span>
                    </div>

                    <div class="item-title-group">
                        <h3>Corte Masculino (Fade / Degradê)</h3>
                    </div>

                    <p class="item-desc">
                        Corte contemporâneo executado com precisão: fade na navalha, low/mid/high fade, tesoura
                        clássica, lavagem refrescante e finalização com pomada modeladora.
                    </p>
                </div>

                <div>
                    <div class="item-meta-row">
                        <div>
                            <span class="item-duration"><i class="fa-regular fa-clock"></i> 45 min de atendimento</span>
                            <div class="item-price">R$ 45,00</div>
                        </div>
                        <div class="table-actions-group">
                            <button type="button" class="btn-icon-action gold" onclick="toggleServiceStatus(this)"
                                title="Pausar ou Ativar Serviço">
                                <i class="fa-solid fa-pause"></i>
                            </button>
                            <button type="button" class="btn-icon-action danger"
                                onclick="deleteAdminCard(this, 'serviço')" title="Remover Serviço">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serviço 3: Barba Terapia -->
            <div class="admin-item-card">
                <div>
                    <div class="card-item-top">
                        <div class="item-icon-avatar">
                            <i class="fa-solid fa-spray-can-sparkles"></i>
                        </div>
                        <span class="badge-status active"><i class="fa-solid fa-circle-check"></i> Ativo</span>
                    </div>

                    <div class="item-title-group">
                        <h3>Barba Completa & Toalha Quente</h3>
                    </div>

                    <p class="item-desc">
                        Ritual clássico de barbearia com toalha aromatizada a vapor, óleo pré-barba, navalha afiada,
                        alinhamento simétrico e bálsamo pós-barba refrescante.
                    </p>
                </div>

                <div>
                    <div class="item-meta-row">
                        <div>
                            <span class="item-duration"><i class="fa-regular fa-clock"></i> 30 min de atendimento</span>
                            <div class="item-price">R$ 35,00</div>
                        </div>
                        <div class="table-actions-group">
                            <button type="button" class="btn-icon-action gold" onclick="toggleServiceStatus(this)"
                                title="Pausar ou Ativar Serviço">
                                <i class="fa-solid fa-pause"></i>
                            </button>
                            <button type="button" class="btn-icon-action danger"
                                onclick="deleteAdminCard(this, 'serviço')" title="Remover Serviço">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serviço 4: Acabamento & Sobrancelha -->
            <div class="admin-item-card">
                <div>
                    <div class="card-item-top">
                        <div class="item-icon-avatar">
                            <i class="fa-solid fa-brush"></i>
                        </div>
                        <span class="badge-status active"><i class="fa-solid fa-circle-check"></i> Ativo</span>
                    </div>

                    <div class="item-title-group">
                        <h3>Acabamento de Pezinho & Sobrancelha</h3>
                    </div>

                    <p class="item-desc">
                        Manutenção e retoque fino da linha da nuca, contorno frontal e desenho higiênico da sobrancelha
                        na lâmina ou pinça para manter a postura sempre em dia.
                    </p>
                </div>

                <div>
                    <div class="item-meta-row">
                        <div>
                            <span class="item-duration"><i class="fa-regular fa-clock"></i> 20 min de atendimento</span>
                            <div class="item-price">R$ 25,00</div>
                        </div>
                        <div class="table-actions-group">
                            <button type="button" class="btn-icon-action gold" onclick="toggleServiceStatus(this)"
                                title="Pausar ou Ativar Serviço">
                                <i class="fa-solid fa-pause"></i>
                            </button>
                            <button type="button" class="btn-icon-action danger"
                                onclick="deleteAdminCard(this, 'serviço')" title="Remover Serviço">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serviço 5: Hidratação Capilar Premium -->
            <div class="admin-item-card">
                <div>
                    <div class="card-item-top">
                        <div class="item-icon-avatar">
                            <i class="fa-solid fa-droplet"></i>
                        </div>
                        <span class="badge-status active"><i class="fa-solid fa-circle-check"></i> Ativo</span>
                    </div>

                    <div class="item-title-group">
                        <h3>Hidratação & Tratamento Capilar</h3>
                    </div>

                    <p class="item-desc">
                        Tratamento reconstrutor intensivo com máscara nutritiva premium e ozônio, combatendo
                        ressecamento e fortalecendo os fios desde a raiz.
                    </p>
                </div>

                <div>
                    <div class="item-meta-row">
                        <div>
                            <span class="item-duration"><i class="fa-regular fa-clock"></i> 30 min de atendimento</span>
                            <div class="item-price">R$ 40,00</div>
                        </div>
                        <div class="table-actions-group">
                            <button type="button" class="btn-icon-action gold" onclick="toggleServiceStatus(this)"
                                title="Pausar ou Ativar Serviço">
                                <i class="fa-solid fa-pause"></i>
                            </button>
                            <button type="button" class="btn-icon-action danger"
                                onclick="deleteAdminCard(this, 'serviço')" title="Remover Serviço">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal: Novo Serviço -->
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
                            <textarea class="form-textarea"
                                placeholder="Descreva os diferenciais e o que inclui o serviço..."></textarea>
                        </div>
                    </div>
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

    <!-- Script Geral do Administrador -->
    <script src="../assets/js/admin.js" defer></script>
</body>

</html>