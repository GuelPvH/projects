<?php

namespace luca\dinner;

class ProdutosRepository{
    private $pdo;

    public function __construct(){
        $this->pdo = Connection::conexao();
    }

    public function listarProdutos(){
        $sql = 'SELECT nome, preco FROM produtos';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll($this->pdo::FETCH_ASSOC);
        return $result;
    }

    public function listarProdutosFornecedores(){
        $sql = 'SELECT produtos.nome, produtos.preco, fornecedores.nome AS nome_fornecedores FROM produtos INNER JOIN fornecedores WHERE produtos.id = fornecedores.id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll($this->pdo::FETCH_ASSOC);
        return $result;
    }
}
?>
