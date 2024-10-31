<?php
session_start();
include '../db.php'; // Conexão com o banco de dados
include '../funcoes.php'; // Inclui a função para exibir botões de navegação

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Consultar todos os pedidos de venda para o relatório de vendas
$sql_vendas = "
    SELECT p.id, p.data_pedido, p.status, c.nome AS cliente_nome, p.total
    FROM pedidos p
    JOIN clientes c ON p.id_cliente = c.id
    ORDER BY p.data_pedido DESC";
$pedidos = $pdo->query($sql_vendas)->fetchAll();

// Consultar todos os orçamentos para a seção de orçamentos
$sql_orcamentos = "
    SELECT o.id, o.numero_orcamento, o.data, o.validade, o.prazo_entrega, o.valor_total, 
           c.nome AS cliente_nome
    FROM orcamentos o
    JOIN clientes c ON o.id_cliente = c.id
    ORDER BY o.data DESC";
$orcamentos = $pdo->query($sql_orcamentos)->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Sistema de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Relatórios</h2>

        <!-- Seção de Relatório de Vendas -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4>Relatório de Vendas</h4>
            </div>
            <div class="card-body">
                <?php if (count($pedidos) > 0): ?>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID do Pedido</th>
                                <th>Data</th>
                                <th>Cliente</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr>
                                    <td><?= $pedido['id'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($pedido['data_pedido'])) ?></td>
                                    <td><?= htmlspecialchars($pedido['cliente_nome']) ?></td>
                                    <td><?= htmlspecialchars($pedido['status']) ?></td>
                                    <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">Nenhum pedido encontrado.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Seção de Orçamentos -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h4>Orçamentos</h4>
            </div>
            <div class="card-body">
                <?php if (count($orcamentos) > 0): ?>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Número do Orçamento</th>
                                <th>Data</th>
                                <th>Cliente</th>
                                <th>Validade (Dias)</th>
                                <th>Prazo de Entrega</th>
                                <th>Valor Total</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orcamentos as $orcamento): ?>
                                <tr>
                                    <td><?= htmlspecialchars($orcamento['numero_orcamento']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($orcamento['data'])) ?></td>
                                    <td><?= htmlspecialchars($orcamento['cliente_nome']) ?></td>
                                    <td><?= htmlspecialchars($orcamento['validade']) ?></td>
                                    <td><?= htmlspecialchars($orcamento['prazo_entrega']) ?></td>
                                    <td>R$ <?= number_format($orcamento['valor_total'], 2, ',', '.') ?></td>
                                    <td>
                                        <a href="visualizarOrcamento.php?id=<?= $orcamento['id'] ?>" class="btn btn-primary btn-sm">Visualizar</a>
                                        <a href="exportarOrcamento.php?id=<?= $orcamento['id'] ?>" class="btn btn-secondary btn-sm">Exportar PDF</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">Nenhum orçamento encontrado.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Exibir Botões de Navegação -->
        <?php exibirBotoesNavegacao(); ?>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
