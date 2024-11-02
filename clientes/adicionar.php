<?php
session_start();
include '../db.php'; // Conexão com o banco de dados
include '../funcoes.php'; // Inclui a função para exibir botões de navegação

// Processar o formulário de adição de cliente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $cpf_cnpj = $_POST['cpf_cnpj'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $sql = "INSERT INTO clientes (nome, endereco, cpf_cnpj, email, telefone) 
            VALUES (:nome, :endereco, :cpf_cnpj, :email, :telefone)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nome' => $nome,
        'endereco' => $endereco,
        'cpf_cnpj' => $cpf_cnpj,
        'email' => $email,
        'telefone' => $telefone
    ]);

    header("Location: listar.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Cliente - Sistema de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Adicionar Novo Cliente</h2>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" id="endereco" name="endereco" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="cpf_cnpj" class="form-label">CPF ou CNPJ</label>
                <input type="text" id="cpf_cnpj" name="cpf_cnpj" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Cliente</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>

        <!-- Exibir Botões de Navegação -->
        <?php exibirBotoesNavegacao(); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
