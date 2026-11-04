/**
 * NA RÉGUA - AGENDAMENTOS JAVASCRIPT
 * Gerencia filtros por categoria e cancelamento de agendamentos
 */

function filterAppointments(type, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const items = document.querySelectorAll('.appt-item');
    const emptyMsg = document.getElementById('emptyMessage');
    let visibleCount = 0;

    items.forEach(item => {
        if (item.getAttribute('data-type') === type) {
            item.style.display = 'grid';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    if (visibleCount === 0) {
        if (emptyMsg) emptyMsg.style.display = 'block';
    } else {
        if (emptyMsg) emptyMsg.style.display = 'none';
    }
}

function cancelAppointment(btn) {
    if (confirm("Deseja realmente cancelar este horário? O cancelamento é gratuito.")) {
        const card = btn.closest('.appointment-item-card');
        if (card) {
            card.style.transition = 'all 0.4s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => {
                card.remove();
                alert("Agendamento cancelado com sucesso!");
            }, 400);
        }
    }
}

