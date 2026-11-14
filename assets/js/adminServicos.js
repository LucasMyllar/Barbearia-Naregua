/**
 * NA RÉGUA - ADMIN SERVIÇOS JAVASCRIPT
 * Cadastro, edição e ativação/desativação de serviços
 */

function tokenCsrf() {
    return document.querySelector('input[name="csrf_token"]').value;
}

function novoServico() {
    const form = document.getElementById('formServico');
    form.reset();
    form.elements['id'].value = '';
    document.getElementById('tituloModalServico').textContent = 'Cadastrar Novo Serviço';
    document.getElementById('erroServico').textContent = '';
    openModal('modalNewService');
}

function editarServico(btn) {
    const card = btn.closest('.admin-item-card');
    const form = document.getElementById('formServico');

    form.elements['id'].value = card.dataset.id;
    form.elements['nome'].value = card.dataset.nome;
    form.elements['descricao'].value = card.dataset.descricao;
    form.elements['preco'].value = card.dataset.preco.replace('.', ',');
    form.elements['duracao'].value = card.dataset.duracao;

    document.getElementById('tituloModalServico').textContent = 'Editar Serviço';
    document.getElementById('erroServico').textContent = '';
    openModal('modalNewService');
}

async function alternarServico(btn) {
    const card = btn.closest('.admin-item-card');
    const ativar = card.dataset.ativo !== '1';
    const acao = ativar ? 'ativar' : 'desativar';

    if (!confirm(`Deseja ${acao} o serviço "${card.dataset.nome}"?`)) return;

    const dados = new FormData();
    dados.append('csrf_token', tokenCsrf());
    dados.append('id', card.dataset.id);
    dados.append('ativo', ativar ? '1' : '0');

    btn.disabled = true;

    try {
        const resposta = await fetch('adminAlterarServico.php', { method: 'POST', body: dados });
        const json = await resposta.json();

        if (resposta.ok) {
            location.reload();
        } else {
            alert(json.erro || 'Não foi possível alterar o serviço.');
            btn.disabled = false;
        }
    } catch (erro) {
        alert('Não foi possível alterar o serviço.');
        btn.disabled = false;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formServico');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const erroEl = document.getElementById('erroServico');
        const botao = form.querySelector('button[type="submit"]');
        erroEl.textContent = '';
        botao.disabled = true;

        try {
            const resposta = await fetch('adminSalvarServico.php', { method: 'POST', body: new FormData(form) });
            const json = await resposta.json();

            if (resposta.ok) {
                location.reload();
            } else {
                erroEl.textContent = json.erro || 'Não foi possível salvar o serviço.';
                botao.disabled = false;
            }
        } catch (erro) {
            erroEl.textContent = 'Não foi possível salvar o serviço.';
            botao.disabled = false;
        }
    });
});