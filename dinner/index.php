<?php
require_once 'bootstrap.php';

// Se acessar com index.php?logout=1, remove a sessão e cookies
if (isset($_GET['logout'])) {
    session_destroy();
    \luca\dinner\CookieHelper::destruirCookies();
    header("Location: index.php");
    exit;
}

// Se o formulário de login foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
    $nome = htmlspecialchars($_POST['nome']);
    $telefone = htmlspecialchars($_POST['telefone']);
    
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_telefone'] = $telefone;
    
    // Cookie de Lembrar-me por 30 dias (HttpOnly ativado) isolado na classe
    \luca\dinner\CookieHelper::registrarCookiesLogin($nome, $telefone);
    
    header("Location: index.php");
    exit;
}

$cliente = getCliente();
$is_logged_in = $cliente !== null;
$nome_display = $is_logged_in ? $cliente->getNome() : null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanchonete - Peça Aqui</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .home-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: var(--spacing-lg) var(--spacing-md);
            text-align: center;
            background-color: var(--color-background);
        }
        .slogan {
            font-size: 16px;
            color: var(--color-text-muted);
            margin-bottom: var(--spacing-xl);
        }
    </style>
</head>
<body>
    <div class="home-container">
        
        <div class="logo-container">
            <div class="logo-box">LOGO</div>
            <span class="status-badge">Aberta</span>
        </div>

        <?php if ($is_logged_in): ?>
            
            <h1 class="mb-sm">Olá, <?php echo $nome_display; ?>!</h1>
            <p class="slogan">Peça aqui o melhor lanche da cidade!</p>
            
            <a href="cardapio.php" class="btn btn-primary mt-md">Acessar o cardápio</a>
            
            <p class="mt-lg small"><a href="?logout=1" style="color: var(--color-text-muted);">Sair / Trocar usuário</a></p>

        <?php else: ?>

            <h1 class="mb-sm">Bem-vindo!</h1>
            <p class="slogan">Identifique-se para continuar o melhor lanche da cidade.</p>

            <form action="index.php" method="POST" class="mt-md">
                <div class="form-group">
                    <label class="form-label" for="telefone">Telefone / WhatsApp</label>
                    <input type="tel" id="telefone" name="telefone" class="form-control" placeholder="(00) 00000-0000" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="nome">Nome ou Apelido</label>
                    <input type="text" id="nome" name="nome" class="form-control" placeholder="Seu nome" required>
                </div>

                <button type="submit" class="btn btn-primary mt-sm">Confirmar Telefone</button>
            </form>

        <?php endif; ?>

    </div>
</body>
</html>
