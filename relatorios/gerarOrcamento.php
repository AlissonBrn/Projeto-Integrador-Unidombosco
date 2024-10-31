<?php
session_start();
include '../db.php'; // Conexão com o banco de dados
include '../funcoes.php'; // Inclui a função para exibir botões de navegação

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Buscar clientes e produtos cadastrados
$clientes = $pdo->query("SELECT * FROM clientes")->fetchAll();
$produtos = $pdo->query("SELECT * FROM produtos")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_orcamento = $_POST['numero_orcamento'];
    $data = date('Y-m-d'); // Definir a data atual do orçamento
    $validade = $_POST['validade'];
    $prazo_entrega = $_POST['prazo_entrega'];
    $id_cliente = $_POST['id_cliente'];
    $produtos_selecionados = $_POST['produtos'];
    $valor_total = 0;

    // Calcular o valor total do orçamento
    foreach ($produtos_selecionados as $produto_id => $dados) {
        $valor_total += $dados['valor_unitario'] * $dados['quantidade'];
    }

    // Inserir orçamento no banco de dados
    $sql_orcamento = "INSERT INTO orcamentos (numero_orcamento, data, validade, prazo_entrega, id_cliente, valor_total)
                      VALUES (:numero_orcamento, :data, :validade, :prazo_entrega, :id_cliente, :valor_total)";
    $stmt = $pdo->prepare($sql_orcamento);
    $stmt->execute([
        'numero_orcamento' => $numero_orcamento,
        'data' => $data,
        'validade' => $validade,
        'prazo_entrega' => $prazo_entrega,
        'id_cliente' => $id_cliente,
        'valor_total' => $valor_total
    ]);

    // Obter o ID do orçamento inserido
    $id_orcamento = $pdo->lastInsertId();

    // Inserir produtos selecionados no orçamento
    foreach ($produtos_selecionados as $produto_id => $dados) {
        $sql_item = "INSERT INTO itens_orcamento (id_orcamento, id_produto, quantidade, valor_unitario)
                     VALUES (:id_orcamento, :id_produto, :quantidade, :valor_unitario)";
        $stmt = $pdo->prepare($sql_item);
        $stmt->execute([
            'id_orcamento' => $id_orcamento,
            'id_produto' => $produto_id,
            'quantidade' => $dados['quantidade'],
            'valor_unitario' => $dados['valor_unitario']
        ]);
    }

    $sucesso = "Orçamento gerado com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Orçamento - Sistema de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Gerar Orçamento</h2>
        
        <?php if (isset($sucesso)): ?>
            <div class="alert alert-success"><?= $sucesso ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="numero_orcamento" class="form-label">Número do Orçamento</label>
                <input type="text" id="numero_orcamento" name="numero_orcamento" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="data" class="form-label">Data</label>
                <input type="text" id="data" name="data" class="form-control" value="<?= date('Y-m-d') ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="validade" class="form-label">Validade do Orçamento (em dias)</label>
                <input type="number" id="validade" name="validade" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="prazo_entrega" class="form-label">Prazo de Entrega</label>
                <input type="text" id="prazo_entrega" name="prazo_entrega" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="id_cliente" class="form-label">Cliente</label>
                <select id="id_cliente" name="id_cliente" class="form-select" required>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <h4 class="mt-4">Produtos</h4>
            <div id="produtos">
                <?php foreach ($produtos as $produto): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
                            <p class="card-text">Código: <?= $produto['id'] ?></p>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="quantidade_<?= $produto['id'] ?>" class="form-label">Quantidade</label>
                                    <input type="number" name="produtos[<?= $produto['id'] ?>][quantidade]" class="form-control" min="1" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="valor_unitario_<?= $produto['id'] ?>" class="form-label">Valor Unitário</label>
                                    <input type="number" name="produtos[<?= $produto['id'] ?>][valor_unitario]" class="form-control" value="<?= $produto['preco'] ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-primary">Gerar Orçamento</button>
        </form>
        
        <!-- Exibir Botões de Navegação -->
        <?php exibirBotoesNavegacao(); ?>
        
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
