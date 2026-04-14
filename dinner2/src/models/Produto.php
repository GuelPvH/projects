<?php
namespace luca\dinner;

// Nome
// Preco

class Produto{
    private int $id;
    
    public function __construct(private string $nome, private float $preco, private string $fornecedor){
        $this->nome = $nome;
        $this->preco = $preco;
        $this->fornecedor = $fornecedor;
    }

    public function getNome() : string{
        return $this->nome;
    }

    public function getPreco() : float{
        return $this->preco;
    }

    public function setPreco(float $novoPreco){
        $this->preco = $novoPreco;
    }

    public function getId(){
        return $this->id;
    }

    public function setId(int $id){
        $this->id = $id;
    }

    public function getFornecedor(){
        return $this->fornecedor;
    }

    public function setFornecedor(string $newFornecedor){
        $this->fornecedor = $newFornecedor;
    }
}   

?>
