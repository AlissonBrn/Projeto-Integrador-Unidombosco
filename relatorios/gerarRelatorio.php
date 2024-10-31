<?php
session_start();
include '../db.php'; // Conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Buscar todos os orçamentos com informações do cliente e produtos
$sql = "
    SELECT o.id, o.numero_orcamento, o.data, o.validade, o.prazo_entrega, o.valor_total, 
           c.nome AS cliente_nome, c.endereco AS cliente_endereco
    FROM orcamentos o
    JOIN clientes c ON o.id_cliente = c.id
    ORDER BY o.data DESC";
$orcamentos = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Orçamentos - Sistema de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Relatório de Orçamentos</h2>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
