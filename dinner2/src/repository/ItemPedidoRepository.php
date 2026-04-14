<?php
namespace luca\dinner;

class ItemPedidoRepository{
    private $pdo;

    public function __construct(){
        $this->pdo = Connection::conexao();
    }

    // Consertar a lógica de getIdPedido --> Fazer um select para descobri o nome do cliente se baseando no id do pedido;

    public function adicionarProduto(PedidosRepository $pedido, string $produto, ?int $quantidade) : void{
        $sql = 'INSERT INTO itemPedido(id_pedido, nome_produto, quantidade) VALUES (:id_pedido, :nome_produto, :quantidade)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_pedido', (int)$pedido->getIdPedido(), $this->pdo::PARAM_INT);
        $stmt->bindValue(':nome_produto', strtolower($produto));
        $stmt->bindValue(':nome_produto', $quantidade);
        $stmt->execute();
    }

    public function removerProduto(PedidosRepository $pedido, string $produto, ?int $quantidade) : void{
        $sql = 'DELETE FROM itemPedido WHERE id_pedido = :id_pedido && nome_produto = :nome_produto && quantidade = :quantidade';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_pedido', (int)$pedido->getIdPedido(), $this->pdo::PARAM_INT);
        $stmt->bindValue(':nome_produto', strtolower($produto));
        $stmt->bindValue(':nome_produto', $quantidade);
        $stmt->execute();
    }

    public function listarItensPedido(PedidosRepository $pedido) : array{
        $sql = 'SELECT nome_produto, quantidade FROM itemPedido WHERE id_pedido = :id_pedido';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_pedido', (int)$pedido->getIdPedido(), $this->pdo::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll($this->pdo::FETCH_ASSOC);
        return $result;
    }

    public function getIdPedido() : int{
        $sql = 'SELECT id_pedido FROM itemPedido WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', (int)$pedido->getIdPedido(), $this->pdo::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result;
    }
}

?>
