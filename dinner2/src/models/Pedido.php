<?php
namespace luca\dinner;

class Pedido{
    private float $valorTotal = 0;
    private array $itens;
    private array $status = ['Preparo', 'Cancelado', 'Pago'];
    private int $id;


    public function __construct(private Cliente $cliente){
        $this->cliente = $cliente;
    }

    public function adicionarItem(Produto $produto){
        array_push($this->itens, $produto);
    }

    public function cancelarPedido(){
        array_splice($this->itens, 0);
        return $this->status[1];
    }

    public function removerIten(Produto $produto){
        array_splice($this->itens, 1, $produto->getId());
    }

    // Getters Setters

    public function getValorTotal() : float{
        return $this->valorTotal;
    }

    public function getNomeCliente() : Cliente{
        return $this->cliente;
    }

    public function getId() : int{
        return $this->id;
    }

    public function getItens() : array{
        return $this->itens;
    }

    public function setTotalPagar($valores) : void{
        foreach($valores as $valor){
            $this->valorTotal += $valor;
        }
    }

    public function setId(int $id) : void{
        $this->id = $id;
    }







}



/*
 * public function adicionarPedido(Produto $produto, $quantidadeItens = 1){
        $this->pedidos[$produto->getNome()] = [
            "Preço" => $produto->getPreco(),
            "Quantidade" => $this->quantidadeItens = $quantidadeItens,
            "Total" => $this->quantidadeItens * $produto->getPreco()
        ];
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

 */
?>
