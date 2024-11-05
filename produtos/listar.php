<?php
session_start();
include '../db.php'; // Conexão com o banco de dados
include '../funcoes.php'; // Inclui a função para exibir botões de navegação

// Verificar se o colaborador está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Consultar todos os produtos
$sql = "SELECT * FROM produtos";
$stmt = $pdo->query($sql);
$produtos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Sistema de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">Sistema de Vendas</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Gerenciar Produtos</h2>
        
        <!-- Botão para adicionar novo produto -->
        <a href="adicionar.php" class="btn btn-success mb-3">Adicionar Novo Produto</a>

        <!-- Tabela de Produtos -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Tipo da Unidade</th>
                    <th>Marca</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= $produto['id'] ?></td>
                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                    <td><?= htmlspecialchars($produto['descricao']) ?></td>
                    <td><?= htmlspecialchars($produto['tipo_unidade']) ?></td>
                    <td><?= htmlspecialchars($produto['marca']) ?></td>
                    <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                    <td><?= $produto['quantidade'] ?></td>
                    <td>
                        <a href="editar.php?id=<?= $produto['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="deletar.php?id=<?= $produto['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Deletar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Exibir Botões de Navegação -->
        <?php exibirBotoesNavegacao(); ?>
       
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
