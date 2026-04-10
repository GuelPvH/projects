<?php
namespace luca\dinner;

class Pedido{
    protected float $totalPagar = 0.0;
    protected array $pedidos = [];
    protected int $quantidadeItens = 0;

    public function __wakeup()
    {
        $this->recalcularTotais();
    }

    public function adicionarPedido(Produto $produto, int $quantidadeAdicionar = 1){
        $nomeProduto = $produto->getNome();
        $precoProduto = $produto->getPreco();

        if (isset($this->pedidos[$nomeProduto])) {
            $this->pedidos[$nomeProduto]['Quantidade'] += $quantidadeAdicionar;
        } else {
            $this->pedidos[$nomeProduto] = [
                "Preço" => $precoProduto,
                "Quantidade" => $quantidadeAdicionar,
            ];
        }

        $this->pedidos[$nomeProduto]['Total'] = $this->pedidos[$nomeProduto]['Quantidade'] * $precoProduto;
        $this->recalcularTotais();
    }

    public function listarPedido() : array{
        return $this->pedidos;
    }

    public function removerPedido(Produto $produto, int $quantidadeRemover = 1) : void {
        $nomeProduto = $produto->getNome();

        if (isset($this->pedidos[$nomeProduto])) {
            $this->pedidos[$nomeProduto]['Quantidade'] -= $quantidadeRemover;

            if ($this->pedidos[$nomeProduto]['Quantidade'] <= 0) {
                unset($this->pedidos[$nomeProduto]);
            } else {
                $this->pedidos[$nomeProduto]['Total'] = $this->pedidos[$nomeProduto]['Quantidade'] * $produto->getPreco();
            }

            $this->recalcularTotais();
        }
    }

    public function finalizarPedido() : float{
        return $this->getTotalPagar();
    }

     public function getTotalPagar() : float{
        return $this->totalPagar;
    }

    public function getQuantidadeTotalItens(): int
    {
        return $this->quantidadeItens;
    }

    private function recalcularTotais() : void{
        $valorTotal = 0;
        $itensTotais = 0;
        foreach($this->pedidos as $pedido){
            $valorTotal += $pedido['Total'] ?? 0;
            $itensTotais += $pedido['Quantidade'] ?? 0;
        }
        $this->totalPagar = $valorTotal;
        $this->quantidadeItens = $itensTotais;
    }

}
?>
