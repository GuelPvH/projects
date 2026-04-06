<?php
namespace luca\dinner;

abstract class Usuario{

    public function __construct(protected string $nome){
        $this->nome = $nome;
    }

    public function verCardapio(Cardapio $cardapio){
        return $cardapio->listarProdutos();
    }

    abstract function podeAdicionarProduto() : bool;
    abstract function podeRemoverProduto() : bool;
    abstract function podeAlterarPrecoProduto() : bool;

    protected function adicionarProduto(Cardapio $cardapio) : string{
        if($this->podeAdicionarProduto()){
            $cardapio -> adicionarProduto();
        }
        $this->mostraMensagem($this->podeAdicionarProduto());
    }

    protected function removerProduto(Cardapio $cardapio){
        if($this->podeRemoverProduto()){
            $cardapio -> removerProduto();
        }
        $this->mostraMensagem($this->podeRemoverProduto());
    }

    protected function alterarPrecoProduto(Produto $produto){
        if($this->podeAlterarPrecoProduto()){
            $produto -> setPreco();
        }
        $this->mostraMensagem($this->podeAlterarPrecoProduto());
    }

    private function mostraMensagem(bool $permissao){
        if($permissao){
            return "Operação bem sucedida";
        }else{
            return "Operação não permitida";
        }
    }

}
?>
