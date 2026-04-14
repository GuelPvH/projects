<?php
require_once 'vendor/autoload.php';

use luca\dinner\Produto;
use luca\dinner\Cardapio;
use luca\dinner\Pedido;
use luca\dinner\Cliente;
use luca\dinner\Cli;
use luca\dinner\Connection;
use luca\dinner\ClienteRepository;
use luca\dinner\ProdutosRepository;
use luca\dinner\PedidosRepository;
use luca\dinner\ItemPedidoRepository;
use luca\dinner\ProdutoService;
use luca\dinner\PedidoService;
/*
$produto = new ProdutoService();
$produto->adicionarProduto();
var_dump($produto->listarProduto());
$produto->alterarProduto("Leite", 6.70);
$produto->removerProduto("Maça");
*/

$pedido = new PedidoService();
$pedido->criarPedido();
?>
