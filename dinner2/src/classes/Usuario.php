<<<<<<< HEAD
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
=======
<?php
namespace luca\dinner;

abstract class Usuario{
    protected string $nome;

    public function __construct(string $nome){
        $this->nome = $nome;
    }

    public function verCardapio(Cardapio $cardapio){
        return $cardapio->listarProdutos();
    }

    abstract function podeAdicionarProduto() : bool;
    abstract function podeRemoverProduto() : bool;
    abstract function podeAlterarPrecoProduto() : bool;

    protected function adicionarProduto(Cardapio $cardapio, Produto $produto) : string{
        if($this->podeAdicionarProduto()){
            $cardapio -> adicionarProduto($produto);
        }
        return $this->mostraMensagem($this->podeAdicionarProduto());
    }

    protected function removerProduto(Cardapio $cardapio, Produto $produto){
        if($this->podeRemoverProduto()){
            $cardapio -> removerProduto($produto);
        }
        return $this->mostraMensagem($this->podeRemoverProduto());
    }

    protected function alterarPrecoProduto(Produto $produto, float $novoPreco){
        if($this->podeAlterarPrecoProduto()){
            $produto -> setPreco($novoPreco);
        }
        return $this->mostraMensagem($this->podeAlterarPrecoProduto());
    }

    private function mostraMensagem(bool $permissao) : string{
        if($permissao){
            return "Operação bem sucedida";
        }else{
            return "Operação não permitida";
        }
    }

    public function getNome() : string{
        return $this->nome;
    }

}
?>
>>>>>>> 24954e929b617fa56ca6191e6b58b6fe464dec22
