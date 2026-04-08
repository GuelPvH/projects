<?php
require_once 'bootstrap.php';

$produto_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$produtosObjeto = $cardapio->listarProdutos();
$produtoItem = $produtosObjeto[$produto_id] ?? null;

if (!$produtoItem) {
    die("Produto não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhe do Produto</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .product-page-header {
            padding: var(--spacing-lg) var(--spacing-md);
            background-color: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
        }

        .header-top {
            display: flex;
            align-items: center;
            margin-bottom: var(--spacing-lg);
        }

        .back-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--color-text);
            display: flex;
            align-items: center;
            font-size: 16px;
            text-decoration: none;
            font-weight: 600;
        }

        .back-btn svg {
            width: 24px;
            height: 24px;
            margin-right: 8px;
            fill: currentColor;
        }

        .price-highlight {
            font-size: 24px;
            font-weight: 800;
            color: var(--color-primary);
            margin-top: var(--spacing-sm);
        }

        .obs-section {
            padding: var(--spacing-lg) var(--spacing-md);
        }

        .obs-helper {
            font-size: 12px;
            color: var(--color-text-muted);
            margin-top: 6px;
            font-style: italic;
        }

        .sticky-footer-btn {
            flex: 1;
            padding: 14px;
        }
    </style>
</head>

<body>

    <div class="product-page-header">
        <div class="header-top">
            <a href="cardapio.php" class="back-btn">
                <svg viewBox="0 0 24 24">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                </svg>
                Voltar
            </a>
        </div>

        <h2><?php echo htmlspecialchars($produtoItem->getNome()); ?></h2>
        <p class="small mt-sm">Lanche Fresquinho</p>
        <div class="price-highlight" id="unit-price" data-price="<?php echo $produtoItem->getPreco(); ?>">
            R$ <?php echo number_format($produtoItem->getPreco(), 2, ',', '.'); ?>
        </div>
    </div>

    <div class="obs-section container">
        <div class="form-group">
            <label class="form-label" for="observacoes">Observações (Opcional)</label>
            <textarea id="observacoes" class="form-control"
                placeholder="Ex: Tirar cebola, maionese à parte..."></textarea>
            <p class="obs-helper">Converse diretamente com o estabelecimento caso queira modificar algum item. Neste
                campo não são aceitas modificações que podem gerar cobrança adicional.</p>
        </div>
    </div>

    <div class="sticky-footer" style="padding: 12px 16px;">
        <div class="sticky-footer-inner" style="gap: 16px;">
            <div class="qty-selector">
                <button type="button" class="qty-btn" id="btn-minus">-</button>
                <span class="qty-value" id="qty-val">1</span>
                <button type="button" class="qty-btn" id="btn-plus">+</button>
            </div>

            <button class="btn btn-primary sticky-footer-btn" id="add-btn"
                onclick="addToCart(<?php echo $produto_id; ?>)">
                Adicionar R$ <?php echo number_format($produtoItem->getPreco(), 2, ',', '.'); ?>
            </button>
        </div>
    </div>

    <script>
        const unitPrice = parseFloat(document.getElementById('unit-price').dataset.price);
        let qty = 1;

        const qtyVal = document.getElementById('qty-val');
        const btnMinus = document.getElementById('btn-minus');
        const btnPlus = document.getElementById('btn-plus');
        const addBtn = document.getElementById('add-btn');

        function updatePrice() {
            const total = unitPrice * qty;
            const formattedTotal = total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            addBtn.innerHTML = `Adicionar R$ ${formattedTotal}`;
        }

        btnMinus.addEventListener('click', () => {
            if (qty > 1) {
                qty--;
                qtyVal.textContent = qty;
                updatePrice();
            }
        });

        btnPlus.addEventListener('click', () => {
            qty++;
            qtyVal.textContent = qty;
            updatePrice();
        });

        function addToCart(produto_id) {
            window.location.href = `checkout.php?action=add&id=${produto_id}&qty=${qty}`;
        }
    </script>
</body>

</html>