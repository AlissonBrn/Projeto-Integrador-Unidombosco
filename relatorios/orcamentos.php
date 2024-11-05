<?php
session_start();
include '../db.php';

// Verifica se o colaborador está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Consulta todos os orçamentos
$sqlOrcamentos = "SELECT o.id, o.data_criacao, o.valor_total, o.status, c.nome AS cliente_nome
                  FROM orcamentos o
                  JOIN clientes c ON o.id_cliente = c.id
                  ORDER BY o.data_criacao DESC";
$stmtOrcamentos = $pdo->prepare($sqlOrcamentos);
$stmtOrcamentos->execute();
$orcamentos = $stmtOrcamentos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Orçamentos</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>Orçamentos</h2>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data de Criação</th>
                    <th>Cliente</th>
                    <th>Valor Total</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcamentos as $orcamento): ?>
                    <tr>
                        <td><?php echo $orcamento['id']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($orcamento['data_criacao'])); ?></td>
                        <td><?php echo htmlspecialchars($orcamento['cliente_nome']); ?></td>
                        <td>R$ <?php echo number_format($orcamento['valor_total'], 2, ',', '.'); ?></td>
                        <td><?php echo ucfirst($orcamento['status']); ?></td>
                        <td>
                            <?php if ($orcamento['status'] == 'aberto'): ?>
                                <a href="editarOrcamento.php?id=<?php echo $orcamento['id']; ?>" class="btn btn-primary">Editar</a>
                                <a href="finalizarOrcamento.php?id=<?php echo $orcamento['id']; ?>" class="btn btn-success">Finalizar</a>
                            <?php else: ?>
                                <span class="text-muted">Finalizado</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
