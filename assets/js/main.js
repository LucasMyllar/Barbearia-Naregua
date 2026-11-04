/**
 * NA RÉGUA - MAIN JAVASCRIPT
 * Gerencia a navegação suave e comportamento dinâmico da landing page (index.php)
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Navegação suave e ativação dos links
    const navButtons = document.querySelectorAll('.nav-menu .nav-link');
    const sections = ['inicio', 'servicos', 'sobre', 'contato'];

    // Atualizar classe ativa com base na rolagem
    window.addEventListener('scroll', () => {
        let currentSection = '';
        const scrollPosition = window.scrollY + 120;

        sections.forEach((id) => {
            const sectionEl = document.getElementById(id);
            if (sectionEl) {
                const sectionTop = sectionEl.offsetTop;
                const sectionHeight = sectionEl.offsetHeight;

                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    currentSection = id;
                }
            }
        });

        if (currentSection) {
            navButtons.forEach((btn) => {
                const onclickAttr = btn.getAttribute('onclick') || '';
                if (onclickAttr.includes(currentSection)) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }
    });
});
