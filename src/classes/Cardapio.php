<?php
namespace luca\dinner;

class Cardapio{
    private array $produtos = [];

    public function adicionarProduto(Produto $produto) : void{
        $this->produtos[] = $produto;
    }

    public function removerProduto(Produto $produto) : void{
        $this->produtos = array_filter(
            $this->produtos, 
            function ($produtoAtual) use ($produto){
                echo 'Produto Atual '.$produtoAtual->getNome();
                if($produtoAtual === $produto){
                    echo ' - Produto Removido';
                }
                echo '<br>';
                return $produtoAtual !== $produto;
            }
        );
    }

    public function listarProdutos() : array{
        return $this->produtos;
    }
}
?>
