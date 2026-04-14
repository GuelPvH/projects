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

    /**
     * Adiciona um novo cliente.
     *
     * @param Cliente $cliente
     * @return void
     * @throws InvalidArgumentException
     */
    public function adicionarCliente(Cliente $cliente): void
    {
        $this->validarCliente($cliente);
        $this->repository->salvar($cliente);
    }

    /**
     * Retorna a lista de todos os clientes.
     * @return array
     */
    public function listarClientes(): array
    {
        $clientes = $this->repository->listarTodosClientes();
        
        if (!$clientes) {
            return [];
        }

        return $clientes;
    }
    /**
     * Atualiza o nome do cliente.
     *
     * @param Cliente $cliente
     * @param string $novoNome
     * @return void
     * @throws InvalidArgumentException
     */
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

    /**
     * Busca os dados do cliente informando o nome no Model.
     *
     * @param Cliente $cliente
     * @return mixed
     * @throws Exception
     */
    public function buscarCliente(Cliente $cliente)
    {
        $this->validarCliente($cliente);

        $resultado = $this->repository->buscarCliente($cliente);

        if (!$resultado) {
            throw new Exception("Cliente não encontrado.");
        }

        return $resultado;
    }
    /**
     * Remove um cliente do repositório.
     *
     * @param Cliente $cliente
     * @return void
     * @throws InvalidArgumentException
     */
    public function removerCliente(Cliente $cliente): void
    {
        $this->validarCliente($cliente);
        $this->repository->deleteCliente($cliente);
    }

    /**
     * Centraliza a validação comum do modelo Cliente.
     * @param Cliente $cliente
     * @return void
     * @throws InvalidArgumentException
     */
    private function validarCliente(Cliente $cliente): void
    {
        $nome = $cliente->getNome();

        // Verifica se o nome veio vazio ou nulo
        if ($nome === null || empty(trim($nome))) {
            throw new InvalidArgumentException("Erro na validação: O nome do cliente é obrigatório e não pode ser vazio ou conter apenas espaços.");
        }

        // Verifica o tamanho mínimo do nome
        if (strlen(trim($nome)) < 3) {
            throw new InvalidArgumentException("Erro na validação: O nome do cliente deve conter pelo menos 3 caracteres.");
        }
    }
}
?>
