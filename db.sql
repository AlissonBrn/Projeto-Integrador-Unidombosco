-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 186.202.152.119
-- Generation Time: 07-Nov-2024 às 11:51
-- Versão do servidor: 5.7.32-35-log
-- PHP Version: 5.6.40-0+deb8u12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistema_labor`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `endereco` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `contato` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `cpf_cnpj` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `telefone` varchar(20) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `endereco`, `contato`, `cpf_cnpj`, `email`, `telefone`) VALUES
(1, 'Senhor Teste', 'rua testes33', 'senhor@teste.com.br', '9999999999999999', 'jidfhnji@gkmfkds.com', '41999999999'),
(2, 'revo', 'revo', NULL, '1050606560596', 'revo@teste.com', '41999999999');

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `usuario` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `senha` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `nivel_acesso` enum('admin','colaborador') COLLATE latin1_general_ci DEFAULT 'colaborador'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `funcionarios`
--

INSERT INTO `funcionarios` (`id`, `nome`, `usuario`, `senha`, `nivel_acesso`) VALUES
(1, 'admin', 'admin', '$2y$10$wIF9w0KrwtvCbF3qV4tRxO81I8i..Y1e6QH3hvQabb2W2fNbI4EE2', 'admin'),
(2, 'teste', 'teste', '$2y$10$E09ljkzxzJg01qhvD8dg6O0m7x9QRtBGAjr0QoD1rUKYk3BgjxkTq', 'colaborador'),
(3, 'Testes', 'teste1', '$2y$10$s7EYKI4Y2X2xPgwoyvc4zeodkpth3h1yHLO5HwpdPUAN52Oqpqcay', 'colaborador');

-- --------------------------------------------------------

--
-- Estrutura da tabela `itens_orcamento`
--

CREATE TABLE `itens_orcamento` (
  `id` int(11) NOT NULL,
  `id_orcamento` int(11) DEFAULT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `valor_unitario` decimal(10,2) DEFAULT NULL,
  `nome_manual` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `nome_personalizado` varchar(255) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `itens_orcamento`
--

INSERT INTO `itens_orcamento` (`id`, `id_orcamento`, `id_produto`, `quantidade`, `valor_unitario`, `nome_manual`, `nome_personalizado`) VALUES
(45, 66, 4, 10, 15.00, NULL, NULL),
(46, 67, 1, 10, 10.00, NULL, NULL),
(47, 68, 3, 10, 10.00, NULL, NULL),
(48, 69, 3, 10, 10.00, NULL, NULL),
(49, 70, 4, 10, 15.00, NULL, NULL);

--
-- Acionadores `itens_orcamento`
--
DELIMITER $$
CREATE TRIGGER `atualiza_valor_total_orcamento_delete` AFTER DELETE ON `itens_orcamento` FOR EACH ROW BEGIN
  UPDATE `orcamentos` 
  SET `valor_total` = (
      SELECT IFNULL(SUM(`quantidade` * `valor_unitario`), 0) 
      FROM `itens_orcamento` 
      WHERE `id_orcamento` = OLD.`id_orcamento`
  ) 
  WHERE `id` = OLD.`id_orcamento`;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `atualiza_valor_total_orcamento_insert` AFTER INSERT ON `itens_orcamento` FOR EACH ROW BEGIN
  UPDATE `orcamentos` 
  SET `valor_total` = (
      SELECT SUM(`quantidade` * `valor_unitario`) 
      FROM `itens_orcamento` 
      WHERE `id_orcamento` = NEW.`id_orcamento`
  ) 
  WHERE `id` = NEW.`id_orcamento`;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Acionadores `itens_pedido`
--
DELIMITER $$
CREATE TRIGGER `atualiza_total_pedido_delete` AFTER DELETE ON `itens_pedido` FOR EACH ROW BEGIN
  UPDATE `pedidos` 
  SET `total` = (
      SELECT IFNULL(SUM(`quantidade` * `preco_unitario`), 0) 
      FROM `itens_pedido` 
      WHERE `id_pedido` = OLD.`id_pedido`
  ) 
  WHERE `id` = OLD.`id_pedido`;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `atualiza_total_pedido_insert` AFTER INSERT ON `itens_pedido` FOR EACH ROW BEGIN
  UPDATE `pedidos` 
  SET `total` = (
      SELECT SUM(`quantidade` * `preco_unitario`) 
      FROM `itens_pedido` 
      WHERE `id_pedido` = NEW.`id_pedido`
  ) 
  WHERE `id` = NEW.`id_pedido`;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `orcamentos`
--

CREATE TABLE `orcamentos` (
  `id` int(11) NOT NULL,
  `numero_orcamento` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `data` date DEFAULT NULL,
  `validade` int(11) NOT NULL,
  `prazo_entrega` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `valor_total` decimal(10,2) DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pendente','liberado','cancelado','finalizado','imprimir') COLLATE latin1_general_ci NOT NULL DEFAULT 'pendente',
  `forma_pagamento` varchar(50) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `orcamentos`
--

INSERT INTO `orcamentos` (`id`, `numero_orcamento`, `data`, `validade`, `prazo_entrega`, `id_cliente`, `valor_total`, `data_criacao`, `status`, `forma_pagamento`) VALUES
(29, 'ORC-1730642987', '2024-11-03', 0, NULL, NULL, 0.00, '2024-11-05 09:34:47', '', NULL),
(30, 'ORC-1730642987', '2024-11-03', 0, NULL, NULL, 0.00, '2024-11-05 09:34:47', '', NULL),
(31, 'ORC-1730642987', '2024-11-03', 0, NULL, NULL, 0.00, '2024-11-05 09:34:47', '', NULL),
(66, '28873579055546401', '2024-11-07', 10, '10', 1, 150.00, '2024-11-07 11:29:37', 'liberado', 'Dinheiro'),
(67, '28873579055546402', '2024-11-07', 10, '10', 1, 100.00, '2024-11-07 11:35:09', 'liberado', 'Dinheiro'),
(68, '28873579055546403', '2024-11-07', 10, '10', 1, 100.00, '2024-11-07 11:36:07', 'pendente', 'Dinheiro'),
(69, '28873579055546404', '2024-11-07', 10, '10', 1, 100.00, '2024-11-07 11:38:35', 'imprimir', 'Boleto'),
(70, '28873579055546405', '2024-11-07', 10, '10', 1, 150.00, '2024-11-07 11:47:13', 'pendente', 'Dinheiro');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `data_pedido` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pendente','Processado','Enviado','Concluído','Cancelado') COLLATE latin1_general_ci NOT NULL DEFAULT 'Pendente',
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `descricao` text COLLATE latin1_general_ci,
  `tipo_unidade` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `marca` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `tipo_unidade`, `marca`, `preco`, `quantidade`) VALUES
(1, 'Copo Becker 100ML', 'copo becker de 100ml', 'Un', 'Teste', 10.00, 45),
(3, 'Acetato', 'teste', 'fr', 'teste33', 10.00, 12),
(4, 'Copo Becker 250mL', 'Copo becker vidro', 'Un', 'Teste28', 15.00, 90);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indexes for table `itens_orcamento`
--
ALTER TABLE `itens_orcamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_orcamento` (`id_orcamento`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Indexes for table `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Indexes for table `orcamentos`
--
ALTER TABLE `orcamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indexes for table `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `itens_orcamento`
--
ALTER TABLE `itens_orcamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orcamentos`
--
ALTER TABLE `orcamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `itens_orcamento`
--
ALTER TABLE `itens_orcamento`
  ADD CONSTRAINT `itens_orcamento_ibfk_1` FOREIGN KEY (`id_orcamento`) REFERENCES `orcamentos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `itens_orcamento_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `orcamentos`
--
ALTER TABLE `orcamentos`
  ADD CONSTRAINT `orcamentos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
