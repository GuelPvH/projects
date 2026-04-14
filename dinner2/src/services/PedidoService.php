<?php
namespace luca\dinner;

class PedidoService{

// Vai receber o parametro do controller
    
    private $repo;

    public function __construct(){
        $this->repo = new PedidosRepository();
    }

    public function criarPedido(/* parametro 1 e parametro 2 */){
        $cliente = new Cliente("Luca Gorayeb");
        $this->repo->salvar($cliente);
    }
}
?>
