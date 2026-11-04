/**
 * NA RÉGUA - CADASTRO JAVASCRIPT
 * Gerencia máscaras de input, alternância de senha e validações na tela de cadastro
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Função reutilizável para alternar visibilidade de senhas
    function setupPasswordToggle(buttonId, inputId) {
        const btn = document.getElementById(buttonId);
        const input = document.getElementById(inputId);

        if (btn && input) {
            btn.addEventListener('click', () => {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';

                const icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-eye', !isPassword);
                    icon.classList.toggle('fa-eye-slash', isPassword);
                }
            });
        }
    }

    setupPasswordToggle('toggleSenha', 'senha');
    setupPasswordToggle('toggleConfirmar', 'confirmar');

    // 2. Máscara automática de CPF (000.000.000-00)
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\D/g, '');
            if (v.length > 11) v = v.slice(0, 11);
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = v;
        });
    }

    // 3. Máscara automática de Telefone ((00) 00000-0000)
    const telInput = document.getElementById('telefone');
    if (telInput) {
        telInput.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\D/g, '');
            if (v.length > 11) v = v.slice(0, 11);
            if (v.length > 10) {
                v = v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
            } else if (v.length > 6) {
                v = v.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
            } else if (v.length > 2) {
                v = v.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
            }
            e.target.value = v;
        });
    }

    // 4. Verificação de correspondência entre senha e confirmação de senha
    const senhaInput = document.getElementById('senha');
    const confirmarInput = document.getElementById('confirmar');

    function validarSenhas() {
        if (!confirmarInput || !senhaInput || !confirmarInput.value) return;

        if (confirmarInput.value !== senhaInput.value) {
            confirmarInput.setCustomValidity('As senhas não coincidem.');
        } else {
            confirmarInput.setCustomValidity('');
        }
    }

    if (senhaInput && confirmarInput) {
        senhaInput.addEventListener('input', validarSenhas);
        confirmarInput.addEventListener('input', validarSenhas);
    }
});
