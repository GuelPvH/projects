<?php
namespace luca\dinner;

// Nome
// Preco

class Produto{
    public function __construct(private string $nome, private float $preco){
        $this->nome = $nome;
        $this->preco = $preco;
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
}   

?>
