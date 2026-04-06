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
}

?>
