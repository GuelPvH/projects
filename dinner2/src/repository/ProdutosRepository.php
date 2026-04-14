<?php

namespace luca\dinner;

class ProdutosRepository{
    private $pdo;

    public function __construct(){
        $this->pdo = Connection::conexao();
    }

    public function listarProdutos() : array{
        $sql = 'SELECT nome, preco FROM produtos';
        $stmt = $this->preparaQuerysSql($sql);
        $result = $this->retornaUmArrayDeValoresDaQuery($stmt);
        return $result;
    }

    public function listarProdutosFornecedores() : array{
        $sql = 'SELECT produtos.nome, produtos.preco, fornecedores.nome AS nome_fornecedores FROM produtos INNER JOIN fornecedores WHERE produtos.id = fornecedores.id';
        $stmt = $this->preparaQuerysSql($sql);
        $result = $this->retornaUmArrayDeValoresDaQuery($stmt);
        return $result;
    }

    public function adicionarProduto(Produto $produto) : void{
        $sql = 'INSERT INTO produtos (nome, preco, id_fornecedor) VALUES (:nome, :preco, :id_fornecedor)';
        $parametros = [':nome' => $produto->getNome(), ':preco' => $produto->getPreco(), ':id_fornecedor' => $produto->getFornecedor()];
        $this->preparaQuerysSql($sql, $parametros);
    }

    public function removerProduto(string $nome) : void{
        $sql = 'DELETE FROM produtos WHERE nome = :nome';
        $parametros = [':nome' => $nome];
        $this->preparaQuerysSql($sql, $parametros);
    }

    public function alterarProduto(string $nome, float $newPreco) : void{
        $sql = 'UPDATE produtos SET preco = :preco WHERE nome = :nome';
        $parametros = [':nome' => $nome, ':preco' => $newPreco];
        $this->preparaQuerysSql($sql, $parametros);
    }

    public function getIdFornecedor(string $fornecedor) : int{
        $sql = 'SELECT id FROM fornecedores WHERE nome = :nome';
        $parametros = [':nome' => $fornecedor];
        $stmt = $this->preparaQuerysSql($sql, $parametros);
        $result = $this->retornaUmValorInteiroDaQuery($stmt);
        return $result;
    }

    public function setIdProduto(Produto $produto) : void{
        $sql = 'SELECT id FROM produtos WHERE nome = :nome';
        $parametros = [':nome' => $produto->getNome()];
        $stmt = $this->preparaQuerysSql($sql, $parametros);
        $result = $this->retornaUmValorInteiroDaQuery($stmt);
        $produto->setId($result);
    }

    public function preparaQuerysSql(string $sql, array $parametros = []){
        $stmt = $this->pdo->prepare($sql);
        foreach($parametros as $campo => $valor){
            $this->verificaSeValorSqlEhStringOrNumero($campo, $valor, $stmt);
        }
        $this->executaQuerySql($stmt);
        return $stmt;
    }

    public function verificaSeValorSqlEhStringOrNumero(string $campo, string|float $valor, $stmt) : string|float{
        if (strtolower($campo[1] != 'i') && strtolower($campo[2] != 'd')) return $stmt->bindValue($campo, $valor);
        return $stmt->bindValue($campo, (float)$this->getIdFornecedor($valor), $this->pdo::PARAM_INT);
    }

    public function executaQuerySql($stmt) : void{
        $stmt->execute();
    }

    public function retornaUmValorInteiroDaQuery($stmt) : int{
        return $stmt->fetchColumn();
    }

    public function retornaUmArrayDeValoresDaQuery($stmt) : array{
        return $stmt->fetchAll($this->pdo::FETCH_ASSOC);
    }

}
?>
