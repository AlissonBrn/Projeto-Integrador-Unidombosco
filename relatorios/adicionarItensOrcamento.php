<?php
session_start();
include '../db.php';
include '../funcoes.php';

// Verificar se o colaborador está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Verificar se o ID do orçamento foi fornecido
if (!isset($_GET['id_orcamento'])) {
    echo "ID do orçamento não fornecido.";
    exit;
}

$id_orcamento = $_GET['id_orcamento'];

// Consultar todos os produtos
$sqlProdutos = "SELECT id, nome, preco FROM produtos";
$stmtProdutos = $pdo->query($sqlProdutos);
$produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);

// Processar o formulário de adição de item ao orçamento
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_produto = $_POST['id_produto'];
    $quantidade = $_POST['quantidade'];
    $nome_personalizado = $_POST['nome_personalizado']; // Novo campo para o nome personalizado

    // Consultar o preço unitário do produto selecionado
    $sqlPreco = "SELECT preco FROM produtos WHERE id = :id_produto";
    $stmtPreco = $pdo->prepare($sqlPreco);
    $stmtPreco->execute(['id_produto' => $id_produto]);
    $precoUnitario = $stmtPreco->fetchColumn();

    // Inserir o item no orçamento, incluindo o nome personalizado, se fornecido
    $sqlInserirItem = "INSERT INTO itens_orcamento (id_orcamento, id_produto, quantidade, valor_unitario, nome_personalizado)
                       VALUES (:id_orcamento, :id_produto, :quantidade, :valor_unitario, :nome_personalizado)";
    $stmtInserirItem = $pdo->prepare($sqlInserirItem);
    $stmtInserirItem->execute([
        'id_orcamento' => $id_orcamento,
        'id_produto' => $id_produto,
        'quantidade' => $quantidade,
        'valor_unitario' => $precoUnitario,
        'nome_personalizado' => $nome_personalizado ? $nome_personalizado : null
    ]);

    // Redirecionar para a página de visualização do orçamento
    header("Location: visualizarOrcamento.php?id=$id_orcamento");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Itens ao Orçamento - Sistema de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Adicionar Itens ao Orçamento</h2>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="id_produto" class="form-label">Produto</label>
                <select id="id_produto" name="id_produto" class="form-select" required>
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtos as $produto): ?>
                        <option value="<?= $produto['id'] ?>">
                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="nome_personalizado" class="form-label">Nome Personalizado (Opcional)</label>
                <input type="text" id="nome_personalizado" name="nome_personalizado" class="form-control" placeholder="Insira um nome personalizado para o produto">
            </div>
            <div class="mb-3">
                <label for="quantidade" class="form-label">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" class="form-control" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Item</button>
            <a href="visualizarOrcamento.php?id=<?= $id_orcamento ?>" class="btn btn-secondary">Voltar ao Orçamento</a>
        </form>
        
        <!-- Exibir Botões de Navegação -->
        <?php exibirBotoesNavegacao(); ?>
        
    </div>

    <!-- Script do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
