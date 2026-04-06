<?php
namespace luca\dinner;

class Pedido{
    protected float $totalPagar;
    protected array $pedidos = [];
    protected int $quantidadeItens;

    public function adicionarPedido(Produto $produto, $quantidadeItens = 1){
        $this->pedidos[$produto->getNome()] = [
            "Preço" => $produto->getPreco(), 
            "Quantidade" => $this->quantidadeItens = $quantidadeItens, 
            "Total" => $this->quantidadeItens * $produto->getPreco()
        ];
    }

    public function listarPedido() : array{
        return $this->pedidos;
    }

    public function removerPedido(Produto $produto, $quantidadeItens = 1){
        $valueItem;
        foreach($this->pedidos as $pedido){
            foreach($pedido as $key => $value){
                if($key == "Quantidade"){
                    $valueItem = $value - $quantidadeItens;
                }
            }
        }
        if($valueItem <= 0){
            return $this->pedidos[$produto->getNome()] = [
                "Preço" => $produto->getPreco(),
                "Quantidade" => "Item Removido",
                "Total" => 0.00
            ];
        }else{
            return $this->pedidos[$produto->getNome()] = [
                "Preço" => $produto->getPreco(),
                "Quantidade" => $this->quantidadeItens = $valueItem,
                "Total" => $this->totalPagar = $valueItem * $produto->getPreco()
            ];
        }
    }

    public function finalizarPedido() : float{
            $this->calculaPagarPedido();
            return $this->getTotalPagar();
    }

     public function getTotalPagar() : float{
        return $this->totalPagar;
    }

    private function setTotalPagar($valor) : void{
        $this->totalPagar = $valor;
    }

    public function calculaPagarPedido() : void{
        $valor;
        foreach($this->pedidos as $pedido){
            foreach($pedido as $key => $value){
                if($key == "Total"){
                    $valor += $value;
                }
            }
        }
        $this->setTotalPagar($valor);
    }

}
?>
