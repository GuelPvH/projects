<?php
namespace luca\dinner;

class ClienteRepository{

    private $pdo;

    public function __construct(){
        $this->pdo = Connection::conexao();
    }

    public function salvar(Cliente $cliente){
        $sql = 'INSERT INTO clientes(nome) VALUES (:nome)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $cliente->getNome());
        $stmt->execute();
    }

    public function alterarCliente(Cliente $cliente, string $valorAlteracao){
        $sql = 'UPDATE clientes SET nome = :nome WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $_id = $this->descobreIdCliente($cliente);
        $stmt->bindValue(':id', (int)$_id, $this->pdo::PARAM_INT);
        $stmt->bindValue(':nome', $valorAlteracao);
        $stmt->execute();
    }

    public function buscarCliente(Cliente $cliente){
        $sql = 'SELECT nome FROM clientes WHERE nome = :nome';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $cliente->getNome());
        $stmt->execute();
        $result = $stmt->fetch($this->pdo::FETCH_ASSOC);
        return $result["nome"];
    }

    public function listarTodosClientes(){
        $sql = 'SELECT nome FROM clientes';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll($this->pdo::FETCH_ASSOC);
        return $result;
    }

    public function deleteCliente(Cliente $cliente){
        $sql = 'DELETE FROM clientes WHERE nome = :nome';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $cliente->getNome());
        $stmt->execute();
    }

    /*private function descobreIdCliente(Cliente $cliente) : int{
        $sql = 'SELECT id FROM clientes WHERE nome = :nome';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $cliente->getNome());
        $stmt->execute();
        $result = $stmt->fetch($this->pdo::FETCH_ASSOC);
        return $result["id"];
    }*/
}

?>
