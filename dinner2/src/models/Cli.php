<?php
namespace luca\dinner;

class Cli{
    private Cliente $cliente;
    public $nome;

    public function apresentacao(){
        echo "Bem vindo a lanchonete Food-CLI\n";
        $this->nome = readline("Como você gostaria de ser chamado(a): ");
        echo "Olá ".$this->getNome()." é um prazer ter você aqui!\n";
        $this->cliente = new Cliente($this->getNome());
    }

    public function getNome(){
        return $this->nome;
    }
}

?>
