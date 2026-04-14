<?php

namespace luca\dinner;

use Exception;
use InvalidArgumentException;

class ClienteService
{
    private ClienteRepository $repository;

    public function __construct()
    {
        $this->repository = new ClienteRepository();
    }
    public function adicionarCliente(Cliente $cliente): void
    {
        $this->validarCliente($cliente);
        $this->repository->salvar($cliente);
    }
    public function listarClientes(): array
    {
        $clientes = $this->repository->listarTodosClientes();
        
        if (!$clientes) {
            return [];
        }

        return $clientes;
    }
    
    public function atualizarCliente(Cliente $cliente, string $novoNome): void
    {
        if ($novoNome === null || empty(trim($novoNome))) {
            throw new InvalidArgumentException("O novo nome do cliente não pode ser vazio.");
        }

        if (strlen(trim($novoNome)) < 3) {
            throw new InvalidArgumentException("O novo nome do cliente deve conter pelo menos 3 caracteres.");
        }

        $this->repository->alterarCliente($cliente, $novoNome);
    }
    public function buscarCliente(Cliente $cliente)
    {
        $this->validarCliente($cliente);

        $resultado = $this->repository->buscarCliente($cliente);

        if (!$resultado) {
            throw new Exception("Cliente não encontrado.");
        }

        return $resultado;
    }
    
    public function removerCliente(Cliente $cliente): void
    {
        $this->validarCliente($cliente);
        $this->repository->deleteCliente($cliente);
    }
    private function validarCliente(Cliente $cliente): void
    {
        $nome = $cliente->getNome();
        if ($nome === null || empty(trim($nome))) {
            throw new InvalidArgumentException("Erro na validação: O nome do cliente é obrigatório e não pode ser vazio ou conter apenas espaços.");
        }
        if (strlen(trim($nome)) < 3) {
            throw new InvalidArgumentException("Erro na validação: O nome do cliente deve conter pelo menos 3 caracteres.");
        }
    }
}
?>
