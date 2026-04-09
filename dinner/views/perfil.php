<?php
require_once 'bootstrap.php';

$cliente = getCliente();
$is_logged_in = $cliente !== null;

if (!$is_logged_in) {
    header("Location: ../index.php");
    exit;
}

$nome_atual = $cliente->getNome();
$sobrenome_atual = isset($_SESSION['usuario_sobrenome']) ? $_SESSION['usuario_sobrenome'] : '';

$mensagem = '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'atualizar') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Falha na validação de segurança. Por favor, tente novamente.');
    }
    $nome_novo = htmlspecialchars($_POST['nome']);
    $sobrenome_novo = htmlspecialchars($_POST['sobrenome']);
    
    $_SESSION['usuario_nome'] = $nome_novo;
    $_SESSION['usuario_sobrenome'] = $sobrenome_novo;
    
    \luca\dinner\CookieHelper::registrarCookiesLogin($nome_novo, $sobrenome_novo);
    
    $nome_atual = $nome_novo;
    $sobrenome_atual = $sobrenome_novo;
    
    $mensagem = "Dados atualizados com sucesso!";
    
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=2">
    <style>
        .page-header {
            background-color: var(--color-background);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: var(--spacing-sm) var(--spacing-md) var(--spacing-sm) var(--spacing-md);
            border-bottom: 1px solid var(--color-border);
        }
        
        .profile-container {
            padding: var(--spacing-lg) var(--spacing-md);
            max-width: 600px;
            margin: 0 auto;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--color-success);
            padding: var(--spacing-md);
            border-radius: var(--border-radius-md);
            margin-bottom: var(--spacing-md);
            font-weight: 500;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="page-header">
        <h2 class="mb-sm text-center">Meu Perfil</h2>
    </div>

    <div class="profile-container">
        
        <?php if ($mensagem): ?>
            <div class="alert-success">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <form action="perfil.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="acao" value="atualizar">
            
            <div class="form-group">
                <label class="form-label" for="nome">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($nome_atual); ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="sobrenome">Sobrenome</label>
                <input type="text" id="sobrenome" name="sobrenome" class="form-control" value="<?php echo htmlspecialchars($sobrenome_atual); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary mt-sm">Salvar Alterações</button>
        </form>

        <div class="mt-xl text-center">
            <p class="small"><a href="../index.php?logout=1" style="color: var(--color-text-muted); text-decoration: underline;">Sair do aplicativo</a></p>
        </div>

    </div>

    <div class="sticky-footer">
        <div class="sticky-footer-inner">
            <a href="cardapio.php" class="nav-item">
                <svg viewBox="0 0 24 24">
                    <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12z"/>
                </svg>
                Cardápio
            </a>
            
            <a href="checkout.php" class="nav-item">
                <svg viewBox="0 0 24 24">
                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zm-9.83-3.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.86-7.01L19.42 4h-.01l-1.1 2-2.76 5H8.53l-.13-.27L6.16 6l-.95-2-.94-2H1v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.13 0-.25-.11-.25-.25z"/>
                </svg>
                Carrinho
            </a>

            <a href="perfil.php" class="nav-item active">
                <svg viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                Perfil
            </a>
        </div>
    </div>

</body>
</html>
