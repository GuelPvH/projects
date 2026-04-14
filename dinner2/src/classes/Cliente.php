<<<<<<< HEAD
<?php
namespace luca\dinner;

class Cliente extends Usuario{

    public function podeAdicionarProduto() : bool{
        return false;
    }

    public function podeRemoverProduto() : bool{
        return false;
    }

    public function podeAlterarPrecoProduto() : bool{
        return false;
    }

    public function getNome(){
        return $this->nome;
    }

}
?>
=======
<?php
namespace luca\dinner;

class Cliente extends Usuario{
    private Pedido $pedido;

    public function __construct(string $nome = ''){
        parent::__construct($nome);
        $this->pedido = new Pedido();
    }

    public function podeAdicionarProduto() : bool{
        return false;
    }

    public function podeRemoverProduto() : bool{
        return false;
    }

    public function podeAlterarPrecoProduto() : bool{
        return false;
    }

    public function fazerPedido(Produto $produto, $quantidadeItens) : void{
        $this->pedido->adicionarPedido($produto, $quantidadeItens);
        $this->mostrarMensagem(true, $produto);
    }

    public function removerPedido(Produto $produto, $quantidadeItens) : void{
        $this->pedido->removerPedido($produto, $quantidadeItens);
        $this->mostrarMensagem(false, $produto);
    }

    public function pagarPedido() : void{
        echo "O total a pagar pelo seu pedido é R$ ".$this->pedido->finalizarPedido();
    }

    private function mostrarMensagem(bool $sucesso, Produto $produto) : void{
        if($sucesso){
                echo "O produto ".$produto->getNome()." foi adicionado<br>";
        }else{
                echo "O produto ".$produto->getNome()." foi removido<br>";
        }
    }
}
?>
>>>>>>> 24954e929b617fa56ca6191e6b58b6fe464dec22
