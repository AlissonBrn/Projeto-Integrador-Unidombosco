<?php
session_start();

// Verificar se o colaborador está logado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Incluir o arquivo de conexão com o banco de dados
include 'db.php';

// Saudação ao colaborador logado
$nomeUsuario = htmlspecialchars($_SESSION['nome']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Vendas - Página Inicial</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo, <?= $nomeUsuario ?>!</h1>
        <p>Você está logado no sistema de vendas de produtos para laboratórios.</p>

        <!-- Links para as principais áreas do sistema -->
        <nav class="navbar">
            <a href="clientes/listar.php">Clientes</a>
            <a href="produtos/listar.php">Produtos</a>
            <a href="pedidos/listar.php">Pedidos</a>
            <a href="relatorios/gerarRelatorio.php">Relatórios</a>
            <a href="logout.php" style="float: right;">Sair</a>
        </nav>

        <!-- Conteúdo Principal -->
        <div class="content">
            <h2>Seções do Sistema:</h2>
            <ul>
                <li><a href="clientes/listar.php">Gerenciar Clientes</a> - Adicionar, editar ou excluir clientes</li>
                <li><a href="produtos/listar.php">Gerenciar Produtos</a> - Adicionar, editar ou excluir produtos</li>
                <li><a href="pedidos/listar.php">Gerenciar Pedidos</a> - Adicionar, editar ou excluir pedidos</li>
                <li><a href="relatorios/gerarRelatorio.php">Relatórios de Vendas</a> - Visualizar e exportar relatórios</li>
            </ul>
        </div>
    </div>
</body>
</html>
