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

    // 2. Redirecionamento inteligente (Admin vs Cliente)
    const btnEntrar = document.querySelector('button[type="submit"]');
    if (btnEntrar) {
        btnEntrar.addEventListener('click', (e) => {
            e.preventDefault();
            const emailInput = document.getElementById('email');
            const email = emailInput ? emailInput.value.toLowerCase().trim() : '';

            // Se o login for de administrador, encaminha para o painel admin
            if (email.includes('admin') || email.includes('gestor') || email.includes('gerente')) {
                window.location.href = 'adminDashboard.php';
            } else {
                window.location.href = 'inicioCliente.php';
            }
        });
    }
});
