/**
 * NA RÉGUA - LOGIN JAVASCRIPT
 * Gerencia a interatividade e redirecionamento da tela de login
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Alternar visibilidade da senha
    const toggleSenha = document.getElementById('toggleSenha');
    const senhaInput = document.getElementById('senha');

    if (toggleSenha && senhaInput) {
        toggleSenha.addEventListener('click', () => {
            const isPassword = senhaInput.type === 'password';
            senhaInput.type = isPassword ? 'text' : 'password';

            const icon = toggleSenha.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
            }
        });
    }

    
});
