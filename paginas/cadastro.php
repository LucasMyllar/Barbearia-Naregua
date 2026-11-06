<?php

use Dom\HTMLElement;
session_start();

if(empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../config/database.php';


 $erros = [];
 $sucesso = false;
 $nome = '';
 $email = '';
 $telefone = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $senha = ($_POST['senha'] ?? '');
    $confirmar = ($_POST['confirmar'] ?? '');
    $tokenEnviado = $_POST['csrf_token'] ?? '';

    if(!hash_equals($_SESSION['csrf_token'], $tokenEnviado)) {
        $erros[] = 'Requisição inválida. Recarregue a página e tente novamente.';
    }

    if ($nome === '') {
        $erros[] = 'Informe seu nome.';
    }

    if (mb_strlen($nome) > 100) {
        $erros[] = 'O nome deve ter no máximo 100 caracteres.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um email válido.';
    }

    if (mb_strlen($email) > 150) {
        $erros[] = 'O e-mail deve ter no máximo 150 caracteres.';
    }

    if (mb_strlen($senha) < 8) {
        $erros[] = 'A senha deve ter no mínimo 8 caracteres.';
    }

    if ($senha !== $confirmar) {
        $erros[] = 'As senhas não coincidem.';
    }

    if (empty($erros)) {
        $stmt = $pdo->prepare('SELECT id_usuario FROM usuario WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $existe = $stmt->fetch();

        if ($existe) {
            $erros[] = 'Este email já está cadastrado.';
        }
    }

    if (empty($erros)) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $telefone = ($telefone === '') ? null : $telefone;

        $stmt = $pdo->prepare(
            'INSERT INTO usuario (nome, email, senha, telefone, tipo_usuario)
            VALUES (:nome, :email, :senha, :telefone, :tipo)'
        );

        try {
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senhaHash,
                ':telefone' => $telefone,
                ':tipo' => 'CLIENTE'

            ]);

            $sucesso = true;
        }catch (PDOException $e) {
            if($e->getCode() === '23505') {
                $erros[] = 'Este email já está cadastrado.';
            } else {
                error_log($e->getMessage());
                $erros[] = 'Não foi possível concluir o cadastro. Tente novamente.';
            }
        }
    }

}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na Régua - Criar Conta</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

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


        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <div id="mensagem">
                <?php if($sucesso): ?>
                  <p>Conta criada com sucesso! <a href="login.php">Fazer login</a></p>
                <?php endif; ?>

                <?php foreach ($erros as $erro): ?>
                    <p><?= htmlspecialchars($erro) ?></p>
                <?php endforeach; ?>
            </div>
            <label for="nome">Nome/Usuario</label>
            <input type="text" id="nome" name="nome" placeholder="Nome e sobrenome" 
                value="<?= htmlspecialchars($nome) ?>" required>
            
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="exemplo@email.com" 
                value="<?= htmlspecialchars($email) ?>" required>

            <label for="telefone">Telefone (opcional)</label>
            <input type="tel" id="telefone" name="telefone" placeholder="(xx) xxxxx-xxxx" maxlength="15"
                value="<?= htmlspecialchars($telefone) ?>" required>   
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
                <button type="button" class="btn-toggle-senha" id="toggleConfirmar"
                    aria-label="Mostrar ou ocultar confirmação de senha">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>

            <button type="submit">Cadastrar</button>

            <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
        </form>

    </main>

    <!-- Script de Cadastro -->
    <script src="../assets/js/cadastro.js" defer></script>
</body>

</html>