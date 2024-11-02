<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

// Verificar se há um administrador cadastrado no banco de dados
$sql = "SELECT COUNT(*) FROM funcionarios WHERE nivel_acesso = 'admin'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$adminCount = $stmt->fetchColumn();

// Se não houver administradores cadastrados, redirecionar para a página de cadastro de administrador
if ($adminCount == 0) {
    header("Location: primeiro_acesso.php");
    exit;
}

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

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistema de Vendas</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="clientes/listar.php">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produtos/listar.php">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pedidos/listar.php">Pedidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="relatorios/gerarRelatorio.php">Relatórios</a>
                    </li>
                    <!-- Link para o painel de administração, visível apenas para administradores -->
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

    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1>Bem-vindo, <?= $nomeUsuario ?>!</h1>
            <p class="lead">Você está logado no sistema de vendas de produtos para laboratórios.</p>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gerenciar Clientes</h5>
                        <p class="card-text">Adicione, edite ou exclua registros de clientes.</p>
                        <a href="clientes/listar.php" class="btn btn-primary">Acessar Clientes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gerenciar Produtos</h5>
                        <p class="card-text">Controle o estoque e informações dos produtos.</p>
                        <a href="produtos/listar.php" class="btn btn-primary">Acessar Produtos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gerenciar Pedidos</h5>
                        <p class="card-text">Acompanhe os pedidos feitos e atualize o status.</p>
                        <a href="pedidos/listar.php" class="btn btn-primary">Acessar Pedidos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Relatórios de Vendas</h5>
                        <p class="card-text">Visualize e exporte relatórios de vendas.</p>
                        <a href="relatorios/gerarRelatorio.php" class="btn btn-primary">Acessar Relatórios</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
