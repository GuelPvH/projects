<?php
namespace luca\dinner;

class Cliente{
    private string $id;

    public function __construct(private string $nome){
        $this->nome = $nome;
    }

    public function getNome() : string{
        return $this->nome;
    }

    public function getId() : int{
        return $this->id;
    }

    public function setNome(string $novoNome) : void{
        $this->nome = $novoNome;
    }

    public function setId(int $id) : void{
        $this->id = $id;
    }

}
?>
