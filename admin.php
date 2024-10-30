<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

// Verificar se o usuário é administrador
if (!isset($_SESSION['id']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Consultar todos os funcionários
$sql = "SELECT * FROM funcionarios";
$stmt = $pdo->query($sql);
$funcionarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração do Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Sistema de Vendas</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Painel de Administração</h2>
        <a href="funcionarios/cadastrar.php" class="btn btn-success mb-3">Adicionar Novo Funcionário</a>
        
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Usuário</th>
                    <th>Nível de Acesso</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($funcionarios as $funcionario): ?>
                <tr>
                    <td><?= $funcionario['id'] ?></td>
                    <td><?= htmlspecialchars($funcionario['nome']) ?></td>
                    <td><?= htmlspecialchars($funcionario['usuario']) ?></td>
                    <td><?= htmlspecialchars($funcionario['nivel_acesso']) ?></td>
                    <td>
                        <a href="funcionarios/editar.php?id=<?= $funcionario['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="funcionarios/deletar.php?id=<?= $funcionario['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este funcionário?');">Deletar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
