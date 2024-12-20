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
    <title>Projeto Sistema de Vendas - Página Inicial</title>
    <!-- Link do CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .header h1 {
            font-weight: bold;
        }
        .card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-title {
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistema de Vendas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
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

    <!-- Saudação e Cabeçalho -->
    <div class="container mt-5">
        <div class="header mb-5">
            <h1>Bem-vindo, <?= $nomeUsuario ?>!</h1>
            <p class="lead">Acesse as áreas do sistema de vendas de produtos para laboratórios.</p>
        </div>

        <!-- Cartões de Seções -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            
            <!-- Card Clientes -->
            <div class="col">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">Clientes</h5>
                        <p class="card-text">Gerencie as informações dos clientes cadastrados.</p>
                        <a href="clientes/listar.php" class="btn btn-primary w-100">Acessar Clientes</a>
                    </div>
                </div>
            </div>

            <!-- Card Produtos -->
            <div class="col">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">Produtos e estoque</h5>
                        <p class="card-text">Controle o estoque e detalhes dos produtos.</p>
                        <a href="produtos/listar.php" class="btn btn-primary w-100">Acessar Produtos</a>
                    </div>
                </div>
            </div>

            <!-- Card Pedidos -->
            <div class="col">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">Pedidos</h5>
                        <p class="card-text">Acompanhe e gerencie os pedidos realizados.</p>
                        <a href="pedidos/listar.php" class="btn btn-primary w-100">Acessar Pedidos</a>
                    </div>
                </div>
            </div>

            <!-- Card Relatórios de Vendas -->
            <div class="col">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">Geração dos Relatórios</h5>
                        <p class="card-text">Gere os orçamentos e relatórios detalhados de vendas e pedidos em .pdf.</p>
                        <a href="relatorios/gerarRelatorio.php" class="btn btn-primary w-100">Acessar Relatórios de Vendas</a>
                    </div>
                </div>
            </div>

            <!-- Card Orçamentos -->
            <div class="col">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">Orçamentos</h5>
                        <p class="card-text">Espaço destinado a criação dos orçamentos para clientes.</p>
                        <a href="relatorios/gerarOrcamento.php" class="btn btn-primary w-100">Acessar Orçamentos</a>
                    </div>
                </div>
            </div>

            <!-- Card Listagem de Orçamentos -->
            <div class="col">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">Listar e Confirmar Orçamentos</h5>
                        <p class="card-text">Confirme, modifique ou remova orçamentos já cadastrados.</p>
                        <a href="orcamentos/listarOrcamento.php" class="btn btn-primary w-100">Acessar Listagem de Orçamentos</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Rodapé -->
    <footer class="text-center mt-5 py-3" style="background-color: #343a40; color: white;">
        <p>Projeto criado por Alisson para fins educacionais.</p>
    </footer>

    <!-- Script do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
