<?php
namespace luca\dinner;

class ProdutoService{

    // Vai receber o parametro do controller

    private $repo;

    public function __construct(){
        $this->repo = new ProdutosRepository();
    }

    public function adicionarProduto(/* parametro 1 e parametro 2 */){
        $produto = new Produto("Maça", 3.50, "Casa do leite");
        $this->repo->adicionarProduto($produto);
        $this->repo->setIdProduto($produto);
    }

    public function listarProduto(){
        return $this->repo->listarProdutos();
    }

    public function listarProdutosFornecedores(){
        return $this->repo->listarProdutosFornecedores();
    }

    public function removerProduto(string $nome){
        $this->repo->removerProduto($nome);
    }

    public function alterarProduto(string $nome, float $newPreco){
        $this->repo->alterarProduto($nome, $newPreco);
    }
}
?>
