<?php
namespace luca\dinner;

class ItemPedido{

    public function __construct(private int $id, private Produto $produto, private Pedido $pedido){
        $this->id = $id;
        $this->produto->getNome();
        $this->pedido->getIdPedido();
    }
}

?>
