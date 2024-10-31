<?php
session_start();
include '../db.php'; // Conexão com o banco de dados

// Verificar se o colaborador está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Obter o ID do produto a partir do parâmetro URL
$id = $_GET['id'] ?? null;

// Buscar os dados do produto para preencher o formulário
$sql = "SELECT * FROM produtos WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$produto = $stmt->fetch();

if (!$produto) {
    echo "Produto não encontrado.";
    exit;
}

// Processar o formulário de edição
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $tipo_unidade = $_POST['tipo_unidade'];
    $marca = $_POST['marca'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];

    $sql = "UPDATE produtos SET nome = :nome, descricao = :descricao, tipo_unidade = :tipo_unidade, marca = :marca, preco = :preco, quantidade = :quantidade WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nome' => $nome,
        'descricao' => $descricao,
        'tipo_unidade' => $tipo_unidade,
        'marca' => $marca,
        'preco' => $preco,
        'quantidade' => $quantidade,
        'id' => $id
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
    <title>Editar Produto - Sistema de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Editar Produto</h2>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($produto['nome']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-control" rows="3" required><?= htmlspecialchars($produto['descricao']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="tipo_unidade" class="form-label">Tipo da Unidade</label>
                <input type="text" id="tipo_unidade" name="tipo_unidade" class="form-control" value="<?= htmlspecialchars($produto['tipo_unidade']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" id="marca" name="marca" class="form-control" value="<?= htmlspecialchars($produto['marca']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="preco" class="form-label">Preço</label>
                <input type="number" step="0.01" id="preco" name="preco" class="form-control" value="<?= htmlspecialchars($produto['preco']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="quantidade" class="form-label">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" class="form-control" value="<?= htmlspecialchars($produto['quantidade']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>