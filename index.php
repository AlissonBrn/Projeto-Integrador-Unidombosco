<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

// Verificar se o colaborador está logado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Saudação ao colaborador logado
$nomeUsuario = htmlspecialchars($_SESSION['nome']);
$nivelAcesso = $_SESSION['nivel_acesso'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Vendas - Página Inicial</title>
    <!-- Link do CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistema de Vendas</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php if ($nivelAcesso === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">Administração</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h1 class="display-4">Bem-vindo, <?= $nomeUsuario ?>!</h1>
            <p class="lead">Acesse as áreas do sistema de vendas de produtos para laboratórios.</p>
        </div>

        <!-- Cartões de Seções -->
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Clientes</h5>
                        <p class="card-text">Gerencie as informações dos clientes cadastrados.</p>
                        <a href="clientes/listar.php" class="btn btn-primary">Acessar Clientes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Produtos</h5>
                        <p class="card-text">Controle o estoque e detalhes dos produtos.</p>
                        <a href="produtos/listar.php" class="btn btn-primary">Acessar Produtos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Pedidos</h5>
                        <p class="card-text">Acompanhe e gerencie os pedidos realizados.</p>
                        <a href="pedidos/listar.php" class="btn btn-primary">Acessar Pedidos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Relatórios de Vendas</h5>
                        <p class="card-text">Gere relatórios detalhados de vendas e pedidos.</p>
                        <a href="relatorios/gerarRelatorio.php" class="btn btn-primary">Acessar Relatórios de Vendas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Orçamentos</h5>
                        <p class="card-text">Crie, visualize e exporte orçamentos para clientes.</p>
                        <a href="relatorios/gerarOrcamento.php" class="btn btn-primary">Acessar Orçamentos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
