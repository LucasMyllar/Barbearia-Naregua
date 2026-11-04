<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Criar Conta</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/cadastro.css">
</head>

<body>
    <main>
        <div>
            <i class="fa-solid fa-scissors brand-icon"></i>
            <h1>Na Régua</h1>
            <span>Estilo • Confiança • Você</span>
        </div>

        <div>
            <h1>Crie sua conta</h1>
            <p>É rápido e fácil.</p>
        </div>

        <div>
            <label for="nome">Nome/Usuario</label>
            <input type="text" id="nome" name="nome" placeholder="Nome completo" required>

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="seu@email.com" required>

            <label for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" maxlength="14" required>

            <label for="telefone">Telefone (opcional)</label>
            <input type="tel" id="telefone" name="telefone" placeholder="(99) 99999-9999" maxlength="15">

            <label for="senha">Senha</label>
            <div class="password-field-wrapper">
                <input type="password" id="senha" name="senha" placeholder="••••••••" required>
                <button type="button" class="btn-toggle-senha" id="toggleSenha" aria-label="Mostrar ou ocultar senha">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>

            <label for="confirmar">Confirmar senha</label>
            <div class="password-field-wrapper">
                <input type="password" id="confirmar" name="confirmar" placeholder="Confirmar senha" required>
                <button type="button" class="btn-toggle-senha" id="toggleConfirmar" aria-label="Mostrar ou ocultar confirmação de senha">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>

            <button type="submit">Cadastrar</button>

            <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
        </div>
    </main>

    <!-- Script de Cadastro -->
    <script src="../assets/js/cadastro.js" defer></script>
</body>

</html>