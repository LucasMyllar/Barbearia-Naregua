<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Login</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS Customizado de Login -->
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
    <main>
            <div>
                <i class="fa-solid fa-scissors brand-icon"></i>
                <h1>Na Régua</h1>
                <span>Estilo • Confiança • Você</span>
            </div>

            <div>
                <h2>Bem-vindo de volta!</h2>
                <p>Faça login para continuar.</p>
            </div>

            <div>
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="seu@email.com" required>

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="••••••••" required>
                <button type="button" id="toggleSenha" class="btn-toggle-senha" aria-label="Mostrar ou ocultar senha">
                    <i class="fa-regular fa-eye"></i>
                </button>
                
                <input type="checkbox" id="lembrar" name="lembrar">
                <label for="lembrar">Lembrar de mim</label>

                <a href="#">Esqueceu a senha?</a>

                <button type="submit">Entrar</button>
            </div>

            <hr>

            <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
        </main>

    <!-- Script de Login -->
    <script src="../assets/js/login.js" defer></script>
</body>

</html>