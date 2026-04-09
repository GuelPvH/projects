<?php
require_once 'views/bootstrap.php';

// Se acessar com index.php?logout=1, remove a sessão e cookies
if (isset($_GET['logout'])) {
    session_destroy();
    \luca\dinner\CookieHelper::destruirCookies();
    header("Location: index.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Se o formulário de login foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Falha na validação de segurança. Por favor, tente novamente.');
    }

    $nome = htmlspecialchars(trim($_POST['nome']), ENT_QUOTES, 'UTF-8');
    $sobrenome = htmlspecialchars(trim($_POST['sobrenome']), ENT_QUOTES, 'UTF-8');

    if (!empty($nome) && !empty($sobrenome)) {
        $_SESSION['usuario_nome'] = $nome;
        $_SESSION['usuario_sobrenome'] = $sobrenome;

        // Cookie de Lembrar-me por 30 dias (HttpOnly ativado) isolado na classe
        \luca\dinner\CookieHelper::registrarCookiesLogin($nome, $sobrenome);

        // Renovar token por segurança
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        header("Location: index.php");
        exit;
    }
}

$cliente = getCliente();
$is_logged_in = $cliente !== null;
$nome_display = $is_logged_in ? htmlspecialchars($cliente->getNome(), ENT_QUOTES, 'UTF-8') : null;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MecDonin - Mistura Perfeita</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <style>
        .home-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: var(--spacing-md) var(--spacing-md);
            text-align: center;
            background-color: var(--color-background);
            gap: var(--spacing-sm);
        }

        .slogan {
            font-size: 15px;
            color: var(--color-text-muted);
            margin-bottom: var(--spacing-md);
        }
    </style>
</head>

<body>
    <div class="home-container">

        <div class="logo-container">
            <div class="logo-box">
                <img src="assets/img/logo.png" alt="MecDonin Logo" class="logo-img">
            </div>
            <span class="status-badge">Aberta</span>
        </div>

        <?php if ($is_logged_in): ?>

            <h1 class="mb-sm">Olá, <?php echo $nome_display; ?>!</h1>
            <p class="slogan">Peça aqui sua mistura perfeita!</p>

            <a href="views/cardapio.php" class="btn btn-primary mt-md">Acessar o cardápio</a>

            <p class="mt-lg small"><a href="?logout=1" style="color: var(--color-text-muted);">Sair / Trocar usuário</a></p>

        <?php else: ?>

            <h1 class="mb-sm">Bem-vindo!</h1>
            <p class="slogan">Identifique-se para continuar na MecDonin!</p>

            <form action="index.php" method="POST" class="mt-md">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group">
                    <label class="form-label" for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" class="form-control" placeholder="Seu nome" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="sobrenome">Sobrenome</label>
                    <input type="text" id="sobrenome" name="sobrenome" class="form-control" placeholder="Seu sobrenome"
                        required>
                </div>

                <button type="submit" class="btn btn-primary mt-sm">Confirmar Dados</button>
            </form>

        <?php endif; ?>

    </div>
</body>

</html>