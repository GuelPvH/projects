<?php
require_once 'bootstrap.php';

$cliente = getCliente();
$cliente_nome = $cliente ? $cliente->getNome() : 'Visitante';
$cliente_sobrenome = isset($_SESSION['usuario_sobrenome']) ? $_SESSION['usuario_sobrenome'] : '';

// Helper: encontra produto no cardápio pelo nome
function encontrarProdutoPorNome(string $nome, $cardapio): ?\luca\dinner\Produto {
    foreach ($cardapio->listarProdutos() as $p) {
        if ($p->getNome() === $nome) return $p;
    }
    return null;
}

// Ação de adicionar produto ao pedido (vindo da página do produto)
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

// Ação: incrementar +1 unidade de um item já no carrinho
if (isset($_GET['action']) && $_GET['action'] == 'incrementar') {
    $nome = urldecode($_GET['nome'] ?? '');
    $produto = encontrarProdutoPorNome($nome, $cardapio);
    if ($produto) {
        $pedido_atual->adicionarPedido($produto, 1);
    }
    header('Location: checkout.php');
    exit;
}

// Ação: decrementar -1 unidade de um item já no carrinho
if (isset($_GET['action']) && $_GET['action'] == 'decrementar') {
    $nome = urldecode($_GET['nome'] ?? '');
    $produto = encontrarProdutoPorNome($nome, $cardapio);
    if ($produto) {
        $pedido_atual->removerPedido($produto, 1);
    }
    header('Location: checkout.php');
    exit;
}

// Ação: remover item específico do carrinho (setar quantidade = 0)
if (isset($_GET['action']) && $_GET['action'] == 'remover') {
    $nome = urldecode($_GET['nome'] ?? '');
    $produto = encontrarProdutoPorNome($nome, $cardapio);
    if ($produto) {
        // Remove todas as unidades desse item
        $listaPedidos = $pedido_atual->listarPedido();
        if (isset($listaPedidos[$nome])) {
            $qtdAtual = $listaPedidos[$nome]['Quantidade'];
            $pedido_atual->removerPedido($produto, $qtdAtual);
        }
    }
    header('Location: checkout.php');
    exit;
}
// Ação: esvaziar TODO o carrinho
if (isset($_GET['action']) && $_GET['action'] == 'limpar_tudo') {
    $_SESSION['pedido'] = new \luca\dinner\Pedido();
    header('Location: checkout.php');
    exit;
}
$carrinho = [];
$listaPedidos = $pedido_atual->listarPedido();
foreach ($listaPedidos as $nomeLanche => $dados) {
    $carrinho[] = [
        'titulo' => $nomeLanche,
        'preco' => $dados['Preço'],
        'quantidade' => $dados['Quantidade']
    ];
}

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
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: background-color 0.15s, border-color 0.15s;
        }

        .cart-qty-btn:hover {
            background-color: rgba(225, 19, 131, 0.08);
            border-color: var(--color-primary);
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
            <?php $nomeEnc = urlencode($item['titulo']); ?>
            <div class="cart-item">
                <div class="cart-item-img"></div>
                <div class="cart-item-info">
                    <p class="cart-item-title"><?php echo htmlspecialchars($item['titulo']); ?></p>
                    <p class="cart-item-price">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                    <div class="cart-qty-controls">
                        <a href="?action=decrementar&nome=<?php echo $nomeEnc; ?>" class="cart-qty-btn" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">-</a>
                        <span><?php echo $item['quantidade']; ?></span>
                        <a href="?action=incrementar&nome=<?php echo $nomeEnc; ?>" class="cart-qty-btn" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">+</a>
                    </div>
                </div>
                <a href="?action=remover&nome=<?php echo $nomeEnc; ?>" class="cart-remove" style="text-decoration: none;" title="Remover item">
                    <svg viewBox="0 0 24 24" style="width:24px; height:24px; fill:currentColor;">
                        <path
                            d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12 1.41 1.41L13.41 14l2.12 2.12-1.41 1.41L12 15.41l-2.12 2.12-1.41-1.41L10.59 14l-2.12-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4z" />
                    </svg>
                </a>
            </div>
        <?php endforeach; ?>

        <a href="cardapio.php" class="add-more">+ Adicionar mais itens</a>

        <hr style="border: 0; border-top: 1px solid var(--color-border); margin: var(--spacing-lg) 0;">

        <!-- 2. Modalidade de Entrega / Retirada -->
        <h3 class="section-title">Entrega / Retirada</h3>

        <label class="radio-card" id="label-entrega">
            <input type="radio" name="modalidade" value="entrega" onchange="toggleModalidade()">
            <div>
                <div style="font-weight:600">Entregar</div>
                <div class="small">Receber no seu endereço</div>
            </div>
        </label>

        <!-- Campo CEP — aparece só quando 'Entregar' está selecionado -->
        <div id="bloco-cep" style="display:none; margin-top: -4px; margin-bottom: var(--spacing-sm);">
            <div style="background: rgba(225,19,131,0.06); border: 1px solid var(--color-border); border-top: none; border-radius: 0 0 var(--border-radius-md) var(--border-radius-md); padding: var(--spacing-md);">

                <!-- CEP -->
                <div class="form-group" style="margin-bottom: var(--spacing-sm);">
                    <label class="form-label" for="cep">CEP *</label>
                    <input type="text" id="cep" name="cep" class="form-control"
                        placeholder="Ex: 01310-100"
                        maxlength="9"
                        inputmode="numeric"
                        oninput="mascaraCEP(this)"
                        onkeyup="checkFormStatus()">
                </div>

                <!-- Rua !-->
                <div style="display:grid; grid-template-columns: 1fr 90px; gap: var(--spacing-sm); margin-bottom: var(--spacing-sm);">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="rua">Nome da rua *</label>
                        <input type="text" id="rua" name="rua" class="form-control"
                            placeholder="Ex: Rua das Flores"
                            oninput="checkFormStatus()">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="numero">Número *</label>
                        <input type="text" id="numero" name="numero" class="form-control"
                            placeholder="Ex: 42"
                            oninput="checkFormStatus()">
                    </div>
                </div>

                <!-- Referência (opcional) -->
                <div class="form-group" style="margin-bottom: var(--spacing-sm);">
                    <label class="form-label" for="referencia">Referência <span style="font-weight:400; color:var(--color-text-muted);">(opcional)</span></label>
                    <input type="text" id="referencia" name="referencia" class="form-control"
                        placeholder="Ex: Próximo ao mercado, portão azul...">
                </div>

                <!-- Aviso de frete -->
                <div style="display:flex; align-items:center; gap:8px; background:rgba(225,19,131,0.08); border-radius:var(--border-radius-sm); padding:10px 12px;">
                    <svg viewBox="0 0 24 24" style="width:18px; height:18px; fill:var(--color-primary); flex-shrink:0;">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    <span style="font-size:13px; color:var(--color-primary); font-weight:600;">
                        Taxa de entrega: <strong>R$ 10,00</strong> (cobrada no total)
                    </span>
                </div>

            </div>
        </div>

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
            <div style="display:flex; flex-direction:column; gap:2px;">
                <div style="display:flex; justify-content:space-between; width:100%;">
                    <span style="font-weight:500; font-size:14px; color:var(--color-text-muted);">Subtotal</span>
                    <span style="font-weight:500; font-size:14px;" id="span-subtotal">R$ <?php echo number_format($pedido_atual->getTotalPagar(), 2, ',', '.'); ?></span>
                </div>
                <div style="display:none; justify-content:space-between; width:100%;" id="linha-frete">
                    <span style="font-weight:500; font-size:14px; color:var(--color-text-muted);">Frete</span>
                    <span style="font-weight:500; font-size:14px; color:var(--color-primary);">+ R$ 10,00</span>
                </div>
                <div style="display:flex; justify-content:space-between; width:100%; border-top:1px solid var(--color-border); padding-top:4px; margin-top:2px;">
                    <span style="font-weight:700; font-size:16px;">Total</span>
                    <span style="font-weight:700; font-size:16px;" id="span-total">R$ <?php echo number_format($pedido_atual->getTotalPagar(), 2, ',', '.'); ?></span>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary" id="btn-finalizar" style="opacity: 0.5; pointer-events: none;"
            onclick="alert('Pedido realizado com sucesso!')">
            Fazer pedido
        </button>
    </div>

    <script>
        const SUBTOTAL = <?php echo json_encode($pedido_atual->getTotalPagar()); ?>;
        const FRETE    = 10.00;

        function mascaraCEP(input) {
            let v = input.value.replace(/\D/g, '');
            if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5, 8);
            input.value = v;
        }

        function atualizarTotal(comFrete) {
            const total = comFrete ? SUBTOTAL + FRETE : SUBTOTAL;
            const fmt   = total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('span-total').textContent    = 'R$ ' + fmt;
            document.getElementById('span-subtotal').textContent = 'R$ ' + SUBTOTAL.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('linha-frete').style.display = comFrete ? 'flex' : 'none';
        }

        function toggleModalidade() {
            const modalidadeChecked = document.querySelector('input[name="modalidade"]:checked');
            const blocoCep = document.getElementById('bloco-cep');
            const entrega  = modalidadeChecked && modalidadeChecked.value === 'entrega';

            // Mostrar/esconder bloco de endereço e linha de frete
            blocoCep.style.display = entrega ? 'block' : 'none';
            atualizarTotal(entrega);

            // Limpar campos de endereço ao trocar de modalidade
            if (!entrega) {
                ['cep','rua','numero','referencia'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = '';
                });
            }

            document.getElementById('label-online').style.display = 'flex';

            checkFormStatus();
        }

        function togglePaymentMethods(type) {
            const onlineGrid    = document.getElementById('methods-online');
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
            const pagamentoChecked  = document.querySelector('input[name="forma_pagamento"]:checked');
            const btnFinalizar      = document.getElementById('btn-finalizar');

            let enderecoValido = true;

            if (modalidadeChecked && modalidadeChecked.value === 'entrega') {
                const cep    = document.getElementById('cep').value.replace(/\D/g, '');
                const rua    = document.getElementById('rua').value.trim();
                const numero = document.getElementById('numero').value.trim();
                enderecoValido = cep.length === 8 && rua.length > 0 && numero.length > 0;
            }

            if (modalidadeChecked && pagamentoChecked && enderecoValido) {
                btnFinalizar.style.opacity     = '1';
                btnFinalizar.style.pointerEvents = 'auto';
            } else {
                btnFinalizar.style.opacity     = '0.5';
                btnFinalizar.style.pointerEvents = 'none';
            }
        }

        ['cep','rua','numero'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', checkFormStatus);
        });
    </script>
</body>

</html>