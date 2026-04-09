<?php
require_once __DIR__ . '/../vendor/autoload.php';

use luca\dinner\Produto;
use luca\dinner\Cardapio;
use luca\dinner\Pedido;
use luca\dinner\Cliente;
use luca\dinner\CookieHelper;

session_start();

// ======= RESTAURAÇÃO DE SESSÃO VIA COOKIE ========
CookieHelper::restaurarSessao();

// ====== 1. Inicializar o Cardápio (Base de Dados em Memória) ======
$cardapio = new Cardapio();

$pao = new Produto("Pão", 2.50);
$leite = new Produto("Leite", 3.50);

$cardapio->adicionarProduto($pao);
$cardapio->adicionarProduto($leite);


// ====== 2. Inicializar o Pedido na Sessão ======
if (!isset($_SESSION['pedido'])) {
    $_SESSION['pedido'] = new Pedido();
}
$pedido_atual = $_SESSION['pedido'];

// Função utilitária para pegar o Cliente
function getCliente()
{
    if (isset($_SESSION['usuario_nome'])) {
        return new Cliente($_SESSION['usuario_nome']);
    }
    return null;
}


?>