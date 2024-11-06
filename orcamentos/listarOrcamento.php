<?php
session_start();
include '../db.php';
include '../funcoes.php'; // Inclui as funções de navegação

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
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Orçamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Orçamentos</h2>

        <!-- Mensagem de Sucesso -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'orcamento_finalizado'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Orçamento confirmado com sucesso!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
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
                        <td><?= htmlspecialchars($orcamento['id']) ?></td>
                        <td><?= date('d/m/Y', strtotime($orcamento['data_criacao'])) ?></td>
                        <td><?= htmlspecialchars($orcamento['cliente_nome']) ?></td>
                        <td>R$ <?= number_format($orcamento['valor_total'], 2, ',', '.') ?></td>
                        <td>
                            <span class="badge <?= $orcamento['status'] == 'aberto' ? 'bg-primary' : 'bg-secondary' ?>">
                                <?= ucfirst($orcamento['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($orcamento['status'] == 'aberto'): ?>
                                <a href="visualizarOrcamento.php?id=<?= $orcamento['id'] ?>" class="btn btn-info btn-sm">Opções do orçamento</a>
                                <a href="editarOrcamento.php?id=<?= $orcamento['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="finalizarOrcamento.php?id=<?= $orcamento['id'] ?>" class="btn btn-success btn-sm">Tranformar em pedido</a>
                                <a href="excluirOrcamento.php?id=<?= $orcamento['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este orçamento?')">Excluir</a>
                            <?php else: ?>
                                <a href="visualizarOrcamento.php?id=<?= $orcamento['id'] ?>" class="btn btn-secondary btn-sm">Visualizar</a>
                                <span class="text-muted">Finalizado</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Exibir Botões de Navegação -->
        <?php exibirBotoesNavegacao(); ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
