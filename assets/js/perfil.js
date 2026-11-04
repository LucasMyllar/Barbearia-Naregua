/**
 * NA RÉGUA - PERFIL JAVASCRIPT
 * Gerencia a atualização de dados pessoais e alteração de senha
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Atualização dos Dados Pessoais
    const formPersonalData = document.getElementById('formPersonalData');
    if (formPersonalData) {
        formPersonalData.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Dados pessoais atualizados com sucesso!');
        });
    }

    // 2. Validação e Alteração de Senha
    const formPassword = document.getElementById('formPassword');
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('confirmPassword');

    function checkPasswordsMatch() {
        if (!confirmPassword || !newPassword || !confirmPassword.value) return;

        if (confirmPassword.value !== newPassword.value) {
            confirmPassword.setCustomValidity('As senhas não coincidem.');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }

    if (newPassword && confirmPassword) {
        newPassword.addEventListener('input', checkPasswordsMatch);
        confirmPassword.addEventListener('input', checkPasswordsMatch);
    }

    if (formPassword) {
        formPassword.addEventListener('submit', (e) => {
            e.preventDefault();
            if (newPassword && confirmPassword && newPassword.value !== confirmPassword.value) {
                alert('A confirmação de senha não confere com a nova senha.');
                return;
            }
            alert('Senha atualizada com sucesso!');
            formPassword.reset();
        });
    }

    // 3. Botão de Alteração de Foto de Perfil
    const btnChangeAvatar = document.querySelector('.btn-change-avatar');
    if (btnChangeAvatar) {
        btnChangeAvatar.addEventListener('click', () => {
            alert('Funcionalidade de upload de foto em breve!');
        });
    }
});

