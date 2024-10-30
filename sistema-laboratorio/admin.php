<?php
session_start();
include 'db.php';

// Verificar se o usuário está logado e é um administrador
if (!isset($_SESSION['id']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Consulta para obter todos os funcionários
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Painel de Administração</h2>
        <a href="funcionarios/cadastrar.php" class="btn btn-add">Adicionar Novo Funcionário</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Usuário</th>
                <th>Nível de Acesso</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($funcionarios as $funcionario): ?>
            <tr>
                <td><?= $funcionario['id'] ?></td>
                <td><?= htmlspecialchars($funcionario['nome']) ?></td>
                <td><?= htmlspecialchars($funcionario['usuario']) ?></td>
                <td><?= htmlspecialchars($funcionario['nivel_acesso']) ?></td>
                <td>
                    <a href="funcionarios/editar.php?id=<?= $funcionario['id'] ?>" class="btn btn-edit">Editar</a>
                    <a href="funcionarios/deletar.php?id=<?= $funcionario['id'] ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir este funcionário?');">Deletar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
