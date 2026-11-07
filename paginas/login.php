<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../config/database.php';

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = ($_POST['senha'] ?? '');

    $tokenEnviado = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $tokenEnviado)) {
        $erros[] = 'Requisição inválida. Recarregue a página e tente novamente.';
    }

    if ($email === '' || $senha === '') {
        $erros[] = 'Informe email e senha.';
    }

    if (empty($erros)) {
        $stmt = $pdo->prepare('SELECT * FROM app_privado.buscar_usuario_login(:email)');
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            session_regenerate_id(true);
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

            if ($usuario['tipo_usuario'] === 'ADM') {
                header('Location: adminDashboard.php');
                exit;
            }

            header('Location: inicioCliente.php');
            exit;

        } else {
            $erros[] = 'E-mail ou senha inválidos.';
        }
    }
}



?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Login</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

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

        <form method="POST" action="" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <div id="mensagem">
                <?php foreach ($erros as $erro): ?>
                    <p><?= htmlspecialchars($erro) ?></p>
                <?php endforeach; ?>
            </div>

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="seu@email.com" required>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="••••••••" required>
            <button type="button" id="toggleSenha" class="btn-toggle-senha" aria-label="Mostrar ou ocultar senha">
                <i class="fa-regular fa-eye"></i>
            </button>

            <a href="#">Esqueceu a senha?</a>

            <button type="submit">Entrar</button>
        </form>

        <hr>

        <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
    </main>

    <!-- Script de Login -->
    <script src="../assets/js/login.js" defer></script>
</body>

</html>