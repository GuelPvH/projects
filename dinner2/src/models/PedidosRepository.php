<?php
namespace luca\dinner;

class PedidosRepository{
    private $pdo;

    public function __construct(){
        $this->pdo = Connection::conexao();
    }

    public function criarPedido(Cliente $cliente){
        $sql = 'INSERT INTO pedidos(pedinte) VALUES (:pedinte)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':pedinte', $cliente->getNome());
        $stmt->execute();
    }

    public function deletarPedido(Cliente $cliente){
        $sql = 'DELETE FROM pedidos WHERE pedinte = :pedinte';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':pedinte', $cliente->getNome());
        $stmt->execute();
    }

    public function getIdPedido(Cliente $cliente){
        $sql = 'SELECT id FROM pedidos WHERE pedinte = :pedinte';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':pedinte', $cliente->getNome());
        $stmt->execute();
        $result = $stmt->fetchAll($this->pdo::FETCH_ASSOC);
        return $result;
    }
}

?>
