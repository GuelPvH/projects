<?php
require_once 'bootstrap.php';

$categorias = ['Todos os Lanches'];

$produtosObjeto = $cardapio->listarProdutos();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .page-header {
            background-color: var(--color-background);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: var(--spacing-sm) var(--spacing-md) 0 var(--spacing-md);
            border-bottom: 1px solid var(--color-border);
        }
    </style>
</head>
<body>

    <div class="page-header">
        <h2 class="mb-sm">Cardápio</h2>
        
        <div class="category-scroll">
            <?php foreach($categorias as $index => $cat): ?>
                <div class="category-pill <?php echo $index === 0 ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($cat); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container">
        
        <?php foreach($produtosObjeto as $index => $prod): ?>
            <a href="produto.php?id=<?php echo $index; ?>" class="product-card">
                <div class="product-info">
                    <h3 class="product-title"><?php echo htmlspecialchars($prod->getNome()); ?></h3>
                    <p class="product-desc small">Lanche Fresquinho</p>
                    <div class="product-price">R$ <?php echo number_format($prod->getPreco(), 2, ',', '.'); ?></div>
                </div>
            </a>
        <?php endforeach; ?>

        <div style="height: 40px;"></div>

    </div>

    <div class="sticky-footer">
        <div class="sticky-footer-inner">
            <a href="cardapio.php" class="nav-item active">
                <svg viewBox="0 0 24 24">
                    <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12z"/>
                </svg>
                Cardápio
            </a>
            
            <a href="javascript:alert('Página de perfil em construção!');" class="nav-item">
                <svg viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                Perfil
            </a>
        </div>
    </div>

    <script>
        document.querySelectorAll('.category-pill').forEach(pill => {
            pill.addEventListener('click', function() {
                document.querySelectorAll('.category-pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
