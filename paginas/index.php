<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Barbearia & Estilo</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- HEADER / NAVEGAÇÃO -->
    <header class="site-header">
        <a href="#inicio" class="header-brand">
            <i class="fa-solid fa-scissors brand-icon"></i>
            <div>
                <h1>Na Régua</h1>
                <span class="brand-tagline">Estilo • Confiança • Você</span>
            </div>
        </a>

        <nav class="nav-menu">
            <button class="nav-link active" onclick="document.getElementById('inicio').scrollIntoView({behavior: 'smooth'})">Início</button>
            <button class="nav-link" onclick="document.getElementById('servicos').scrollIntoView({behavior: 'smooth'})">Serviços</button>
            <button class="nav-link" onclick="document.getElementById('sobre').scrollIntoView({behavior: 'smooth'})">Sobre</button>
            <button class="nav-link" onclick="document.getElementById('contato').scrollIntoView({behavior: 'smooth'})">Contato</button>
        </nav>

        <a href="login.php" class="btn-header-cta">
            <i class="fa-regular fa-calendar-check"></i> Agendar Horário
        </a>
    </header>

    <main>
        <!-- SEÇÃO HERO -->
        <div id="inicio" class="hero-section">
            <div class="hero-content">
                <span class="hero-pretitle">Mais que um corte,</span>
                <h2 class="hero-title">É um estilo <span class="highlight">de vida.</span></h2>
                <p class="hero-desc">Conosco você encontra qualidade, conforto e profissionais experientes para cuidar do seu visual.</p>
                <a href="login.php" class="hero-cta">
                    <i class="fa-regular fa-calendar-check"></i> Agendar Horário <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
            
            <div class="hero-image-wrapper">
                <div class="hero-image-container">
                    <img class="hero-img" src="../img/cortando.png" alt="Barbeiro cortando cabelo de um cliente">
                </div>
            </div>

            <!-- FAIXA DE DIFERENCIAIS -->
            <div class="highlights-bar">
                <div class="highlight-item">
                    <i class="fa-solid fa-scissors highlight-icon"></i>
                    <h4 class="highlight-title">Cortes Modernos</h4>
                    <p class="highlight-desc">Do clássico ao seu estilo personalizado.</p>
                </div>
                <div class="highlight-item">
                    <i class="fa-solid fa-user-tie highlight-icon"></i>
                    <h4 class="highlight-title">Barba Modelada</h4>
                    <p class="highlight-desc">Mais definição, alinhamento e personalidade.</p>
                </div>
                <div class="highlight-item">
                    <i class="fa-solid fa-shield-halved highlight-icon"></i>
                    <h4 class="highlight-title">Ambiente Confortável</h4>
                    <p class="highlight-desc">Espaço climatizado pensado para seu bem-estar.</p>
                </div>
                <div class="highlight-item">
                    <i class="fa-solid fa-star highlight-icon"></i>
                    <h4 class="highlight-title">Profissionais Experientes</h4>
                    <p class="highlight-desc">Equipe atenta a cada detalhe do visual.</p>
                </div>
            </div>
        </div>

        <!-- SEÇÃO SERVIÇOS -->
        <div id="servicos" class="services-section">
            <div class="services-header">
                <nav class="section-tag">NOSSOS SERVIÇOS</nav>
                <h2 class="services-title">CUIDADO EM CADA DETALHE.</h2>
                <p class="services-subtitle">Oferecemos serviços completos para realçar o seu melhor, com qualidade, agilidade e muito estilo.</p>
            </div>

            <div class="service-card">
                <img src="../img/corteMasculino.jpg" alt="Corte de cabelo masculino">
                <h3>Corte Masculino</h3>
                <p class="service-desc">Estilo e precisão para todos os tipos de cabelos.</p>
                <p class="service-price">A partir de R$ 40,00</p>
            </div>

            <div class="service-card">
                <img src="../img/barba.jpg" alt="Modelagem de barba">
                <h3>Barba</h3>
                <p class="service-desc">Modelagem e alinhamento para um visual impecável.</p>
                <p class="service-price">A partir de R$ 20,00</p>
            </div>

            <div class="service-card">
                <img src="../img/cabeloBarba.jpg" alt="Corte de cabelo e barba">
                <h3>Cabelo e Barba</h3>
                <p class="service-desc">Combo de corte + barba especial.</p>
                <p class="service-price">A partir de R$ 55,00</p>
            </div>

            <div class="service-card">
                <img src="../img/sobrancelha.png" alt="Acabamento e Sobrancelha">
                <h3>Acabamento e Sobrancelha</h3>
                <p class="service-desc">Alinhamento de pezinho, contorno e desenho de sobrancelha.</p>
                <p class="service-price">A partir de R$ 25,00</p>
            </div>
        </div>

        <!-- SEÇÃO SOBRE NÓS -->
        <div id="sobre" class="about-section">
            <img class="about-img" src="../img/salao.webp" alt="Salão da Barbearia Na Régua">
            
            <div class="about-content">
                <nav class="section-tag">SOBRE NÓS</nav>
                <h2 class="about-title">TRADIÇÃO E MODERNIDADE</h2>
                <p class="about-desc">Há 10 anos, nossa barbearia transforma cortes de cabelo em experiências de estilo, confiança e
                    personalidade. Ao longo dessa jornada, construímos uma história baseada na qualidade, no respeito e
                    na satisfação de cada cliente.</p>
                
                <div>
                    <i class="fa-solid fa-award"></i>
                    <p>Qualidade em primeiro lugar</p>
                </div>
                <hr>
                <div>
                    <i class="fa-regular fa-heart"></i>
                    <p>Respeito a cada cliente</p>
                </div>
                <hr>
                <div>
                    <i class="fa-solid fa-users"></i>
                    <p>Ambiente agradável e seguro</p>
                </div>
            </div>
        </div>

        <!-- SEÇÃO CONTATO -->
        <div id="contato" class="contact-section">
            <h2 class="contact-title">ENTRE EM CONTATO</h2>
            <p class="contact-subtitle">Quer renovar o seu estilo? Estamos prontos para te atender! Entre em contato conosco, tire suas dúvidas e agende o seu próximo horário.</p>
            
            <div class="contact-info-list">
                <ul><i class="fa-solid fa-phone" style="color: var(--gold);"></i> Telefone: (81) 99999-9999</ul>
                <ul><i class="fa-solid fa-location-dot" style="color: var(--gold);"></i> Endereço: Recife - PE</ul>
                <ul><i class="fa-brands fa-instagram" style="color: var(--gold);"></i> Instagram: @naregua</ul>
            </div>

            <p class="contact-closing"><i class="fa-solid fa-check"></i> Seu estilo merece o melhor cuidado!</p>
        </div>
    </main>

    <footer>
        <p>Na Régua <i class="fa-regular fa-copyright"></i> 2026 Todos os direitos reservados.</p>
    </footer>

    <!-- Custom JS -->
    <script src="../assets/js/main.js" defer></script>
</body>

</html>