<?php
namespace luca\dinner;

class PedidosRepository{
    private $pdo;

    public function __construct(){
        $this->pdo = Connection::conexao();
    }

    public function salvar(Cliente $cliente) : void{
        $sql = 'INSERT INTO pedidos(pedinte) VALUES (:pedinte)';
        $parametros = [':pedinte' => $cliente->getNome()];
        $this->preparaQuerysSql($sql, $parametros);
    }

    public function deletar(Cliente $cliente) : void{
        $sql = 'DELETE FROM pedidos WHERE pedinte = :pedinte';
        $parametros = [':pedinte' => $cliente->getNome()];
        $this->preparaQuerysSql($sql, $parametros);
    }

    public function getIdPedido(Cliente $cliente) : int{
        $sql = 'SELECT id FROM pedidos WHERE pedinte = :pedinte';
        $parametros = [':pedinte' => $cliente->getNome(), ':id_pedido' => $cliente->getId()];
        $stmt = $this->preparaQuerysSql($sql, $parametros);
        $result = $this->retornaUmValorInteiroDaQuery($stmt);
        return $result;
    }

    public function retornaPrecoDosProdutosDoPedido() : array{
        $sql = 'SELECT produtos.preco FROM produtos, itemPedido, pedidos WHERE produtos.nome = itemPedido.nome_produto AND pedidos.id = itemPedido.id_pedido';
        $stmt = $this->preparaQuerysSql($sql);
        $result = $this->retornaUmArrayDeValoresDaQuery($stmt);
        return $result;
    }

    private function preparaQuerysSql(string $sql, array $parametros = []){
        $stmt = $this->pdo->prepare($sql);
        foreach($parametros as $campo => $valor){
            $this->verificaSeValorSqlEhStringOrNumero($campo, $valor, $stmt);
        }
        $this->executaQuerySql($stmt);
        return $stmt;
    }

    private function verificaSeValorSqlEhStringOrNumero(string $campo, string|float $valor, $stmt) : string|float{
        if (strtolower($campo[1] != 'i') && strtolower($campo[2] != 'd')) return $stmt->bindValue($campo, $valor);
        return $stmt->bindValue($campo, (float)$valor, $this->pdo::PARAM_INT);
    }

    private function executaQuerySql($stmt) : void{
        $stmt->execute();
    }

    private function retornaUmValorInteiroDaQuery($stmt) : int{
        return $stmt->fetchColumn();
    }

    private function retornaUmArrayDeValoresDaQuery($stmt) : array{
        return $stmt->fetchAll($this->pdo::FETCH_ASSOC);
    }
}

?>
