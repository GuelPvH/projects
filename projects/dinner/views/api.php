<?php
header('Access-Control-Allow-Origin: *'); 
header('Content-Type: application/json'); 

require_once 'vendor/autoload.php';

use luca\dinner\Produto;
use luca\dinner\Cardapio;

$pao = new Produto("Pão", 2.50);
$leite = new Produto("Leite", 3.50);

$cardapio = new Cardapio();
$cardapio->adicionarProduto($pao);
$cardapio->adicionarProduto($leite);


$produtosFormatados = [];
foreach ($cardapio->listarProdutos() as $produto) {
    $produtosFormatados[] = [
        'nome' => $produto->getNome(),
        'preco' => $produto->getPreco()
    ];
}

echo json_encode(['cardapio' => $produtosFormatados]);
