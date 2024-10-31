<?php
session_start();
include '../db.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Obter o ID do orçamento
$id_orcamento = $_GET['id'] ?? null;

// Consultar detalhes do orçamento e informações do cliente
$sql_orcamento = "
    SELECT o.*, c.nome AS cliente_nome, c.endereco AS cliente_endereco
    FROM orcamentos o
    JOIN clientes c ON o.id_cliente = c.id
    WHERE o.id = :id";
$stmt = $pdo->prepare($sql_orcamento);
$stmt->execute(['id' => $id_orcamento]);
$orcamento = $stmt->fetch();

if (!$orcamento) {
    echo "Orçamento não encontrado.";
    exit;
}

// Consultar os produtos do orçamento
$sql_produtos = "
    SELECT p.nome, p.marca, io.quantidade, io.valor_unitario
    FROM itens_orcamento io
    JOIN produtos p ON io.id_produto = p.id
    WHERE io.id_orcamento = :id";
$stmt = $pdo->prepare($sql_produtos);
$stmt->execute(['id' => $id_orcamento]);
$produtos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Orçamento - Sistema de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Detalhes do Orçamento</h2>
        <p><strong>Número do Orçamento:</strong> <?= htmlspecialchars($orcamento['numero_orcamento']) ?></p>
        <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($orcamento['data'])) ?></p>
        <p><strong>Cliente:</strong> <?= htmlspecialchars($orcamento['cliente_nome']) ?></p>
        <p><strong>Endereço do Cliente:</strong> <?= htmlspecialchars($orcamento['cliente_endereco']) ?></p>
        <p><strong>Validade:</strong> <?= htmlspecialchars($orcamento['validade']) ?> dias</p>
        <p><strong>Prazo de Entrega:</strong> <?= htmlspecialchars($orcamento['prazo_entrega']) ?></p>
        <p><strong>Valor Total:</strong> R$ <?= number_format($orcamento['valor_total'], 2, ',', '.') ?></p>

        <h4 class="mt-4">Produtos</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Produto</th>
                    <th>Marca</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <td><?= htmlspecialchars($produto['marca']) ?></td>
                        <td><?= $produto['quantidade'] ?></td>
                        <td>R$ <?= number_format($produto['valor_unitario'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($produto['valor_unitario'] * $produto['quantidade'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="gerarRelatorio.php" class="btn btn-secondary mt-3">Voltar</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
