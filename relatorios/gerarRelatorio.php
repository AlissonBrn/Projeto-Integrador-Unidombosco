<?php
session_start();
include '../db.php';
include '../funcoes.php'; // Inclui a função para exibir botões de navegação

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se o colaborador está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Consulta os orçamentos finalizados
$sqlOrcamentos = "SELECT o.id, o.data_criacao, o.valor_total, c.nome AS cliente_nome
                  FROM orcamentos o
                  JOIN clientes c ON o.id_cliente = c.id
                  WHERE o.status = 'imprimir'
                  ORDER BY o.data_criacao DESC";
$stmtOrcamentos = $pdo->prepare($sqlOrcamentos);
$stmtOrcamentos->execute();
$orcamentos = $stmtOrcamentos->fetchAll(PDO::FETCH_ASSOC);

//Correção do status pendente

$sqlOrcamentos = "SELECT o.id, o.data_criacao, o.valor_total, c.nome AS cliente_nome
                  FROM orcamentos o
                  JOIN clientes c ON o.id_cliente = c.id
                  WHERE o.status = 'pendente'
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
    <title>Relatório de Orçamentos Finalizados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Relatório de Orçamentos Finalizados</h2>
        
        <!-- Botão para exportar todos os orçamentos em PDF -->
        <div class="d-flex justify-content-end mb-3">
            <a href="exportarOrcamentos.php" class="btn btn-success">Exportar Todos em PDF</a>
        </div>

        <!-- Tabela de orçamentos finalizados -->
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Data de Criação</th>
                    <th>Cliente</th>
                    <th>Valor Total</th>
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
                        <td>
                            <a href="exportarOrcamento.php?id=<?php echo $orcamento['id']; ?>" class="btn btn-primary btn-sm">Exportar PDF</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Exibir Botões de Navegação -->
        <?php exibirBotoesNavegacao(); ?>
        
        
    </div>

    <!-- Script do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
