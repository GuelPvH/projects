<?php
require_once 'bootstrap.php';

$cliente = getCliente();
$cliente_nome = $cliente ? $cliente->getNome() : 'Visitante';
$cliente_sobrenome = isset($_SESSION['usuario_sobrenome']) ? $_SESSION['usuario_sobrenome'] : '';

// Ação de adicionar produto ao pedido
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $idx = intval($_GET['id']);
    $qty = isset($_GET['qty']) ? intval($_GET['qty']) : 1;
    $produtos = $cardapio->listarProdutos();
    if (isset($produtos[$idx])) {
        $pedido_atual->adicionarPedido($produtos[$idx], $qty);
    }
    header('Location: checkout.php');
    exit;
}

// Ação para esvaziar carrinho ou remover iten
if (isset($_GET['action']) && $_GET['action'] == 'limpar') {
    $_SESSION['pedido'] = new \luca\dinner\Pedido();
    header('Location: checkout.php');
    exit;
}

$carrinho = [];
$listaPedidos = $pedido_atual->listarPedido();
foreach ($listaPedidos as $nomeLanche => $dados) {
    if ($dados['Quantidade'] === 'Item Removido' || $dados['Quantidade'] <= 0)
        continue;

    $carrinho[] = [
        'titulo' => $nomeLanche,
        'preco' => $dados['Preço'],
        'quantidade' => $dados['Quantidade']
    ];
}

$pedido_atual->calculaPagarPedido();
$total = $pedido_atual->getTotalPagar();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Pedido</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .checkout-header {
            padding: var(--spacing-md);
            background-color: var(--color-background);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
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

        .section-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: var(--spacing-md);
            margin-top: var(--spacing-lg);
            text-transform: uppercase;
            color: var(--color-text);
        }

        .add-more {
            display: inline-block;
            color: var(--color-primary);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            margin-top: var(--spacing-sm);
        }

        .cart-qty-controls {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .cart-qty-btn {
            width: 24px;
            height: 24px;
            border: 1px solid var(--color-border);
            background-color: var(--color-background);
            border-radius: 4px;
            font-weight: bold;
            color: var(--color-primary);
        }

        .payment-methods-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-sm);
            margin-top: var(--spacing-sm);
            display: none;
        }

        .payment-methods-grid.active {
            display: grid;
        }

        .payment-pill {
            border: 1px solid var(--color-border);
            padding: 10px;
            border-radius: var(--border-radius-sm);
            text-align: center;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s;
        }

        input[name="forma_pagamento"] {
            display: none;
        }

        input[name="forma_pagamento"]:checked+.payment-pill {
            border-color: var(--color-primary);
            background-color: rgba(225, 19, 131, 0.05);
            color: var(--color-primary);
            font-weight: 600;
        }

        .user-info-card {
            border: 1px solid var(--color-border);
            padding: var(--spacing-md);
            border-radius: var(--border-radius-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info-details p {
            margin-bottom: 4px;
        }

        .edit-icon {
            color: var(--color-primary);
        }

        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--color-success);
            font-size: 12px;
            font-weight: 600;
            margin-top: var(--spacing-md);
            margin-bottom: var(--spacing-md);
        }
    </style>
</head>

<body>

    <div class="checkout-header">
        <a href="cardapio.php" class="back-btn">
            <svg viewBox="0 0 24 24">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
            </svg>
            Menu
        </a>
        <h2 style="margin-left:auto; margin-right:auto; padding-right:100px;">Meu Pedido</h2>
    </div>

    <div class="container" style="padding-bottom: 120px;">
        <!-- 1. Resumo do Carrinho -->
        <h3 class="section-title" style="margin-top: 0;">Seu Carrinho</h3>

        <?php foreach ($carrinho as $item): ?>
            <div class="cart-item">
                <div class="cart-item-img"></div>
                <div class="cart-item-info">
                    <p class="cart-item-title"><?php echo htmlspecialchars($item['titulo']); ?></p>
                    <p class="cart-item-price">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                    <div class="cart-qty-controls">
                        <button class="cart-qty-btn">-</button>
                        <span><?php echo $item['quantidade']; ?></span>
                        <button class="cart-qty-btn">+</button>
                    </div>
                </div>
                <a href="?action=limpar" class="cart-remove" style="text-decoration: none;">
                    <svg viewBox="0 0 24 24" style="width:24px; height:24px; fill:currentColor;">
                        <path
                            d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12 1.41 1.41L13.41 14l2.12 2.12-1.41 1.41L12 15.41l-2.12 2.12-1.41-1.41L10.59 14l-2.12-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4z" />
                    </svg>
                </a>
            </div>
        <?php endforeach; ?>

        <a href="cardapio.php" class="add-more">+ Adicionar mais itens</a>

        <hr style="border: 0; border-top: 1px solid var(--color-border); margin: var(--spacing-lg) 0;">

        <!-- 2. Modalidade de Retirada -->
        <h3 class="section-title">Retirada</h3>

        <label class="radio-card">
            <input type="radio" name="modalidade" value="mesa" onchange="toggleModalidade()">
            <div>
                <div style="font-weight:600">Comer no local</div>
                <div class="small">Receber na lanchonete</div>
            </div>
        </label>

        <label class="radio-card">
            <input type="radio" name="modalidade" value="balcao" onchange="toggleModalidade()">
            <div>
                <div style="font-weight:600">Retirada no local</div>
                <div class="small">Pronto em cerca de 20 min</div>
            </div>
        </label>

        <!-- 3. Métodos de Pagamento -->
        <h3 class="section-title">Pagamento</h3>

        <label class="radio-card mb-0" id="label-online">
            <input type="radio" name="pagamento_tipo" value="online" onchange="togglePaymentMethods('online')">
            <div>
                <div style="font-weight:600">Pagamento Online</div>
                <div class="small">Pague agora via Pix, Cartão ou Carteira</div>
            </div>
        </label>

        <div id="methods-online" class="payment-methods-grid mb-md">
            <!-- options -->
            <label>
                <input type="radio" name="forma_pagamento" value="pix_online" onchange="checkFormStatus()">
                <div class="payment-pill">PIX</div>
            </label>
            <label>
                <input type="radio" name="forma_pagamento" value="credito_online" onchange="checkFormStatus()">
                <div class="payment-pill">Cartão de Crédito</div>
            </label>
            <label>
                <input type="radio" name="forma_pagamento" value="debito_online" onchange="checkFormStatus()">
                <div class="payment-pill">Cartão de Débito</div>
            </label>
            <label>
                <input type="radio" name="forma_pagamento" value="apple_google" onchange="checkFormStatus()">
                <div class="payment-pill">Apple / Google Pay</div>
            </label>
        </div>

        <label class="radio-card mt-sm mb-0" id="label-presencial">
            <input type="radio" name="pagamento_tipo" value="presencial" onchange="togglePaymentMethods('presencial')">
            <div>
                <div style="font-weight:600">Pagamento Presencial</div>
                <div class="small">Pague na entrega/retirada</div>
            </div>
        </label>

        <div id="methods-presencial" class="payment-methods-grid">
            <label>
                <input type="radio" name="forma_pagamento" value="dinheiro" onchange="checkFormStatus()">
                <div class="payment-pill">Dinheiro</div>
            </label>
            <label>
                <input type="radio" name="forma_pagamento" value="pix_presencial" onchange="checkFormStatus()">
                <div class="payment-pill">PIX (Presencial)</div>
            </label>
            <label>
                <input type="radio" name="forma_pagamento" value="credito_maquina" onchange="checkFormStatus()">
                <div class="payment-pill">Crédito (Máquina)</div>
            </label>
            <label>
                <input type="radio" name="forma_pagamento" value="debito_maquina" onchange="checkFormStatus()">
                <div class="payment-pill">Débito (Máquina)</div>
            </label>
        </div>

        <div class="secure-badge">
            <svg viewBox="0 0 24 24" style="width:16px; height:16px; fill:currentColor">
                <path
                    d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z" />
            </svg>
            Pagamento Rápido e Seguro
        </div>

        <!-- 4. Identificação -->
        <h3 class="section-title">Seus Dados</h3>
        <div class="user-info-card">
            <div class="user-info-details">
                <p class="fw-bold"><?php echo htmlspecialchars(trim($cliente_nome . ' ' . $cliente_sobrenome)); ?></p>
            </div>
            <a href="perfil.php" class="edit-icon">
                <svg viewBox="0 0 24 24" style="width:20px; height:20px; fill:currentColor">
                    <path
                        d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                </svg>
            </a>
        </div>

    </div>

    <div class="sticky-footer checkout-footer">
        <div class="checkout-total">
            <span>Total:</span>
            <span>R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
        </div>
        <button type="button" class="btn btn-primary" id="btn-finalizar" style="opacity: 0.5; pointer-events: none;"
            onclick="alert('Pedido realizado com sucesso!')">
            Fazer pedido
        </button>
    </div>

    <script>
        function toggleModalidade() {
            const modalidadeChecked = document.querySelector('input[name="modalidade"]:checked');
            const labelOnline = document.getElementById('label-online');
            const onlineGrid = document.getElementById('methods-online');

            if (modalidadeChecked && modalidadeChecked.value === 'mesa') {
                labelOnline.style.display = 'none';
                onlineGrid.classList.remove('active');
                const onlineRadio = document.querySelector('input[name="pagamento_tipo"][value="online"]');
                if (onlineRadio) onlineRadio.checked = false;

                document.querySelectorAll('#methods-online input[name="forma_pagamento"]').forEach(el => el.checked = false);
            } else {
                labelOnline.style.display = 'flex';
            }

            checkFormStatus();
        }

        function togglePaymentMethods(type) {
            const onlineGrid = document.getElementById('methods-online');
            const presencialGrid = document.getElementById('methods-presencial');

            document.querySelectorAll('input[name="forma_pagamento"]').forEach(el => el.checked = false);

            if (type === 'online') {
                onlineGrid.classList.add('active');
                presencialGrid.classList.remove('active');
            } else {
                onlineGrid.classList.remove('active');
                presencialGrid.classList.add('active');
            }

            checkFormStatus();
        }

        function checkFormStatus() {
            const modalidadeChecked = document.querySelector('input[name="modalidade"]:checked');
            const pagamentoChecked = document.querySelector('input[name="forma_pagamento"]:checked');
            const btnFinalizar = document.getElementById('btn-finalizar');

            if (modalidadeChecked && pagamentoChecked) {
                btnFinalizar.style.opacity = '1';
                btnFinalizar.style.pointerEvents = 'auto';
            } else {
                btnFinalizar.style.opacity = '0.5';
                btnFinalizar.style.pointerEvents = 'none';
            }
        }
    </script>
</body>

</html>