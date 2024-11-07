<?php
session_start();
include '../db.php';
include '../funcoes.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Verifica se o colaborador está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Consulta os pedidos liberados
$sqlPedidosLiberados = "SELECT o.id, o.numero_orcamento, o.forma_pagamento, o.valor_total, o.data_criacao
                        FROM orcamentos o
                        WHERE o.status = 'liberado'";
$stmtPedidosLiberados = $pdo->prepare($sqlPedidosLiberados);
$stmtPedidosLiberados->execute();
$pedidosLiberados = $stmtPedidosLiberados->fetchAll(PDO::FETCH_ASSOC);

// Consulta os pedidos finalizados, mas não liberados
$sqlPedidosNaoLiberados = "SELECT o.id, o.numero_orcamento, o.forma_pagamento, o.valor_total, o.data_criacao
                           FROM orcamentos o
                           WHERE o.status = 'finalizado'";
$stmtPedidosNaoLiberados = $pdo->prepare($sqlPedidosNaoLiberados);
$stmtPedidosNaoLiberados->execute();
$pedidosNaoLiberados = $stmtPedidosNaoLiberados->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Listar Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Pedidos</h2>
        
        <div class="row">
            <!-- Coluna para pedidos liberados -->
            <div class="col-md-6">
                <h3>Pedidos Liberados</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Número do Pedido</th>
                            <th>Forma de Pagamento</th>
                            <th>Data</th>
                            <th>Valor Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidosLiberados as $pedido): ?>
                            <tr>
                                <td><?= htmlspecialchars($pedido['numero_orcamento']) ?></td>
                                <td><?= htmlspecialchars($pedido['forma_pagamento']) ?></td>
                                <td><?= date('d/m/Y', strtotime($pedido['data_criacao'])) ?></td>
                                <td>R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></td>
                                <td>
                                    <a href="visualizarPedido.php?id=<?= $pedido['id'] ?>" class="btn btn-primary btn-sm">Visualizar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Coluna para pedidos não liberados -->
            <div class="col-md-6">
                <h3>Pedidos Não Liberados</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Número do Pedido</th>
                            <th>Forma de Pagamento</th>
                            <th>Data</th>
                            <th>Valor Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidosNaoLiberados as $pedido): ?>
                            <tr>
                                <td><?= htmlspecialchars($pedido['numero_orcamento']) ?></td>
                                <td><?= htmlspecialchars($pedido['forma_pagamento']) ?></td>
                                <td><?= date('d/m/Y', strtotime($pedido['data_criacao'])) ?></td>
                                <td>R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></td>
                                <td>
                                    <a href="visualizarPedido.php?id=<?= $pedido['id'] ?>" class="btn btn-primary btn-sm">Visualizar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php exibirBotoesNavegacao(); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
