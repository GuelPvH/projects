CREATE DATABASE IF NOT EXISTS lanchonete;

USE lanchonete;

CREATE TABLE IF NOT EXISTS fornecedores(
    id_fornecedor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS produtos(
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    preco DECIMAL(10,2) NOT NULL UNIQUE,
    id_fornecedor INT NOT NULL,
    CONSTRAINT fk_id_fornecedor FOREIGN KEY (id_fornecedor) REFERENCES fornecedores(id_fornecedor);
);

CREATE TABLE IF NOT EXISTS clientes(
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS pedidos(
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    CONSTRAINT fk_id_cliente FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
);

CREATE TABLE IF NOT EXISTS itens(
    id_pedido INT NOT NULL,
    id_produto INT NOT NULL,
    nome_produto VARCHAR(100) NOT NULL,
    quantidade INT DEFAULT 1,
    preco_produto DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_id_pedido FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
    CONSTRAINT fk_id_produto FOREIGN KEY (id_produto) REFERENCES produtos(id_produto),
    CONSTRAINT fk_nome_produto FOREIGN KEY (nome_produto) REFERENCES produtos(nome),
    CONSTRAINT fk_preco_produto FOREIGN KEY (preco_produto) REFERENCES produtos(preco)
);
