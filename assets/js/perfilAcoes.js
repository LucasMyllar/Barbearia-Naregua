/**
 * NA RÉGUA - PERFIL (envio dos formulários)
 */

function mostrarMensagem(elemento, texto, sucesso) {
    elemento.textContent = texto;
    elemento.style.color = sucesso ? '#4ade80' : '#f87171';
}

async function enviarFormulario(form, url, msgEl, textoSucesso, aoSucesso) {
    const botao = form.querySelector('button[type="submit"]');
    msgEl.textContent = '';
    botao.disabled = true;

    try {
        const resposta = await fetch(url, { method: 'POST', body: new FormData(form) });
        const json = await resposta.json();

        if (resposta.ok) {
            mostrarMensagem(msgEl, textoSucesso, true);
            if (aoSucesso) aoSucesso();
        } else {
            mostrarMensagem(msgEl, json.erro || 'Não foi possível salvar.', false);
        }
    } catch (erro) {
        mostrarMensagem(msgEl, 'Não foi possível salvar.', false);
    } finally {
        botao.disabled = false;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const formPerfil = document.getElementById('formPerfil');
    const formSenha = document.getElementById('formSenha');

    if (formPerfil) {
        formPerfil.addEventListener('submit', (e) => {
            e.preventDefault();
            enviarFormulario(formPerfil, 'atualizarPerfil.php', document.getElementById('msgPerfil'), 'Dados atualizados com sucesso!');
        });
    }

    if (formSenha) {
        formSenha.addEventListener('submit', (e) => {
            e.preventDefault();
            enviarFormulario(formSenha, 'alterarSenha.php', document.getElementById('msgSenha'), 'Senha alterada com sucesso!', () => formSenha.reset());
        });
    }
});