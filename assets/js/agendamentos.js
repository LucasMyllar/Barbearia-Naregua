/**
 * NA RÉGUA - AGENDAMENTOS JAVASCRIPT
 * Gerencia filtros por categoria e cancelamento de agendamentos
 */

function filterAppointments(type, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const items = document.querySelectorAll('.appt-item');
    const emptyMsg = document.getElementById('emptyMessage');
    let visibleCount = 0;

    items.forEach(item => {
        const shouldShow = item.getAttribute('data-type') === type;
        item.hidden = !shouldShow;
        item.style.display = shouldShow ? 'grid' : 'none';

        if (shouldShow) visibleCount++;
    });

    if (emptyMsg) {
        emptyMsg.style.display = visibleCount === 0 ? 'block' : 'none';
    }
}

async function cancelAppointment(btn) {
    if (!confirm('Deseja realmente cancelar este horário? O cancelamento só é possível até 2 horas antes.')) return;

    const csrfInput = document.querySelector('input[name="csrf_token"]');
    if (!csrfInput || !btn.dataset.id) {
        alert('Não foi possível localizar os dados do agendamento.');
        return;
    }

    const form = new FormData();
    form.append('csrf_token', csrfInput.value);
    form.append('id', btn.dataset.id);

    btn.disabled = true;
    btn.classList.add('is-loading');

    try {
        const resposta = await fetch('cancelarAgendamento.php', { method: 'POST', body: form });
        const dados = await resposta.json();

        if (resposta.ok) {
            location.reload();
            return;
        }

        alert(dados.erro || 'Não foi possível cancelar.');
    } catch (erro) {
        alert('Não foi possível cancelar.');
    } finally {
        btn.disabled = false;
        btn.classList.remove('is-loading');
    }
}

