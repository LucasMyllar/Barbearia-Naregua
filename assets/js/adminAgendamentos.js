/**
 * NA RÉGUA - ADMIN AGENDAMENTOS JAVASCRIPT
 * Confirmar, concluir e cancelar agendamentos
 */

async function alterarStatus(btn, novoStatus) {
    const textos = {
        CONFIRMADO: 'confirmar',
        CONCLUIDO: 'marcar como concluído',
        CANCELADO: 'cancelar'
    };

    if (!confirm(`Deseja ${textos[novoStatus]} este agendamento?`)) return;

    const dados = new FormData();
    dados.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);
    dados.append('id', btn.dataset.id);
    dados.append('status', novoStatus);

    btn.disabled = true;

    try {
        const resposta = await fetch('adminAlterarStatus.php', { method: 'POST', body: dados });
        const json = await resposta.json();

        if (resposta.ok) {
            location.reload();
        } else {
            alert(json.erro || 'Não foi possível alterar o status.');
            btn.disabled = false;
        }
    } catch (erro) {
        alert('Não foi possível alterar o status.');
        btn.disabled = false;
    }
}