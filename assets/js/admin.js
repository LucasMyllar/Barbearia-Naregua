/**
 * NA RÉGUA - ADMIN JAVASCRIPT (admin.js)
 * Gerenciamento centralizado de interações do painel administrativo
 */

// Toast Notificações do Admin
function showAdminToast(message, icon = 'fa-circle-check') {
    let toast = document.getElementById('adminToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'adminToast';
        toast.className = 'admin-toast';
        document.body.appendChild(toast);
    }

    toast.innerHTML = `<i class="fa-solid ${icon}"></i> <span>${message}</span>`;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3500);
}

// Controle de Modais
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Fechar modais com tecla ESC ou clique fora
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(modal => {
            closeModal(modal.id);
        });
    }
});

document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal-overlay')) {
        closeModal(e.target.id);
    }
});

function syncEmptyTableState() {
    const rows = document.querySelectorAll('.appointment-row');
    const emptyRow = document.getElementById('emptyTableMessage');
    if (!emptyRow) return;

    const visibleCount = Array.from(rows).filter(row => row.style.display !== 'none').length;
    emptyRow.style.display = visibleCount === 0 ? '' : 'none';
}

// Filtro de Agendamentos por Status (Tabs)
function filterAdminAppointments(status, btn) {
    if (btn) {
        const parentTabs = btn.closest('.filter-tabs');
        if (parentTabs) {
            parentTabs.querySelectorAll('.filter-tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }
    }

    const rows = document.querySelectorAll('.appointment-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        if (status === 'all' || rowStatus === status) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const emptyRow = document.getElementById('emptyTableMessage');
    if (emptyRow) {
        emptyRow.style.display = visibleCount === 0 ? '' : 'none';
    }
}

// Busca em Tempo Real na Tabela
function filterTableBySearch(input, targetSelector) {
    const term = input.value.toLowerCase().trim();
    const rows = document.querySelectorAll(targetSelector);
    let visibleCount = 0;

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const matches = text.includes(term);
        row.style.display = matches ? '' : 'none';
        if (matches) visibleCount++;
    });

    const emptyRow = document.getElementById('emptyTableMessage');
    if (emptyRow) {
        emptyRow.style.display = visibleCount === 0 ? '' : 'none';
    }
}

// Concluir Atendimento
function concludeAppointment(btn) {
    const row = btn.closest('tr');
    if (!row) return;

    const badge = row.querySelector('.badge-status');
    if (badge) {
        badge.className = 'badge-status completed';
        badge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Concluído';
        row.setAttribute('data-status', 'completed');
    }

    // Desativa os botões de ação após concluir
    btn.style.opacity = '0.4';
    btn.style.pointerEvents = 'none';
    btn.title = 'Atendimento já concluído';

    showAdminToast('Atendimento concluído e registrado com sucesso!', 'fa-circle-check');
}

// Cancelar Agendamento pelo Admin
function cancelAdminAppointment(btn) {
    if (confirm('Deseja realmente cancelar este agendamento?')) {
        const row = btn.closest('tr');
        if (!row) return;

        const badge = row.querySelector('.badge-status');
        if (badge) {
            badge.className = 'badge-status canceled';
            badge.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Cancelado';
            row.setAttribute('data-status', 'canceled');
        }

        btn.style.opacity = '0.4';
        btn.style.pointerEvents = 'none';

        showAdminToast('Agendamento cancelado com sucesso.', 'fa-triangle-exclamation');
    }
}

// Excluir Item (Serviço ou Barbeiro)
function deleteAdminCard(btn, itemType = 'item') {
    if (confirm(`Deseja realmente remover este ${itemType}? Esta ação não pode ser desfeita.`)) {
        const card = btn.closest('.admin-item-card') || btn.closest('tr');
        if (card) {
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => {
                card.remove();
                showAdminToast(`${itemType.charAt(0).toUpperCase() + itemType.slice(1)} removido com sucesso.`, 'fa-trash-can');
            }, 300);
        }
    }
}

// Alternar Status do Serviço (Ativo / Pausado)
function toggleServiceStatus(btn) {
    const card = btn.closest('.admin-item-card');
    if (!card) return;

    const badge = card.querySelector('.badge-status');
    if (!badge) return;

    if (badge.classList.contains('active')) {
        badge.className = 'badge-status inactive';
        badge.innerHTML = '<i class="fa-solid fa-pause"></i> Pausado';
        btn.innerHTML = '<i class="fa-solid fa-play"></i> Ativar';
        showAdminToast('Serviço pausado temporariamente no catálogo.', 'fa-circle-pause');
    } else {
        badge.className = 'badge-status active';
        badge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Ativo';
        btn.innerHTML = '<i class="fa-solid fa-pause"></i> Pausar';
        showAdminToast('Serviço reativado com sucesso.', 'fa-circle-check');
    }
}

// Inicialização dos Formulários dos Modais
document.addEventListener('DOMContentLoaded', () => {
    // 1. Formulário de Novo Agendamento Presencial / Manual
    const formNewAppointment = document.getElementById('formNewAppointment');
    if (formNewAppointment) {
        formNewAppointment.addEventListener('submit', (e) => {
            e.preventDefault();
            closeModal('modalNewAppointment');
            showAdminToast('Novo agendamento cadastrado com sucesso!', 'fa-calendar-check');
            formNewAppointment.reset();
        });
    }

    // 2. Formulário de Novo Serviço
    const formNewService = document.getElementById('formNewService');
    if (formNewService) {
        formNewService.addEventListener('submit', (e) => {
            e.preventDefault();
            closeModal('modalNewService');
            showAdminToast('Novo serviço adicionado ao catálogo com sucesso!', 'fa-scissors');
            formNewService.reset();
        });
    }
});

