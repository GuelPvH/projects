<?php
require_once 'vendor/autoload.php';

use luca\dinner\Produto;
use luca\dinner\Cardapio;
use luca\dinner\Pedido;
use luca\dinner\Cliente;
use luca\dinner\Cli;
use luca\dinner\Connection;
use luca\dinner\ClienteRepository;

$pao = new Produto("Pão", 2.50);
$leite = new Produto("Leite", 3.50);


$cardapio = new Cardapio();
$cardapio->adicionarProduto($pao);
$cardapio->adicionarProduto($leite);/*
echo "<pre>";
print_r($cardapio->listarProdutos());
echo "<br>";
*/

//$cardapio->removerProduto($produto1);
//echo "<pre>";
//print_r($cardapio->listarProdutos());

//$pedido = new Pedido();

//$pedido->adicionarPedido($produto1, 2);

//$pedido->adicionarPedido($produto2, 3);

//print_r($pedido->listarPedido());

//$pedido->removerPedido($produto2, 3);
//print_r($pedido->listarPedido());

/*
$cliente = new Cliente("Luca");
print_r($cliente->verCardapio($cardapio));
$cliente->fazerPedido($pao, 3);
$cliente->fazerPedido($leite, 2);
$cliente->pagarPedido();
echo "<br>";
*/

/*
$pedido = new Pedido();
$pedido->adicionarPedido($pao, 3);
$pedido->adicionarPedido($leite, 2);
print_r($pedido->listarPedido());
$pedido->calculaPagarPedido();
echo $pedido->getTotalPagar();
*/

//$cli = new Cli();
//$cli->apresentacao();

//$cliente = new Cliente("Luca");
//print_r($cliente->verCardapio($cardapio));
$cliente = new Cliente("Luana");
$repo = new ClienteRepository();
$repo->salvar($cliente);
?>
