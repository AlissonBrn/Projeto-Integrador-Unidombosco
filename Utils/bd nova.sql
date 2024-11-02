-- Criação do Banco de Dados
CREATE DATABASE IF NOT EXISTS sistema_laboratorio;
USE sistema_laboratorio;

-- Tabela Clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    endereco VARCHAR(255),
    contato VARCHAR(50)
);

-- Tabela Produtos com novos campos: tipo_unidade e marca
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    tipo_unidade VARCHAR(10), -- Tipo da Unidade (ex: Un, Fr, Cx)
    marca VARCHAR(50), -- Marca do Produto
    preco DECIMAL(10, 2) NOT NULL,
    quantidade INT NOT NULL
);

-- Tabela Pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pendente', 'Processado', 'Enviado', 'Concluído') DEFAULT 'Pendente',
    total DECIMAL(10, 2),
    FOREIGN KEY (id_cliente) REFERENCES clientes(id) ON DELETE CASCADE
);

-- Tabela Itens do Pedido
CREATE TABLE IF NOT EXISTS itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_produto INT,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id) ON DELETE SET NULL
);

-- Tabela Funcionários para Autenticação e Nível de Acesso
CREATE TABLE IF NOT EXISTS funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, -- A senha será armazenada como hash
    nivel_acesso ENUM('admin', 'colaborador') DEFAULT 'colaborador' -- Define o nível de acesso do funcionário
);

-- Tabela Orçamentos
CREATE TABLE IF NOT EXISTS orcamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_orcamento VARCHAR(20) NOT NULL,
    data DATE, -- Definido sem valor padrão
    validade INT NOT NULL,
    prazo_entrega VARCHAR(100),
    id_cliente INT,
    valor_total DECIMAL(10, 2),
    FOREIGN KEY (id_cliente) REFERENCES clientes(id) ON DELETE SET NULL
);

-- Tabela Itens do Orçamento
CREATE TABLE IF NOT EXISTS itens_orcamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_orcamento INT,
    id_produto INT,
    quantidade INT NOT NULL,
    valor_unitario DECIMAL(10, 2),
    FOREIGN KEY (id_orcamento) REFERENCES orcamentos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id) ON DELETE SET NULL
);
