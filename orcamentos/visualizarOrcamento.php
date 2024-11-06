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

$id_orcamento = $_GET['id'];

// Consulta o orçamento e os itens associados
$sqlOrcamento = "SELECT * FROM orcamentos WHERE id = :id_orcamento";
$stmtOrcamento = $pdo->prepare($sqlOrcamento);
$stmtOrcamento->execute(['id_orcamento' => $id_orcamento]);
$orcamento = $stmtOrcamento->fetch(PDO::FETCH_ASSOC);

$sqlItens = "SELECT io.id, 
                    p.nome AS nome_produto, 
                    io.nome_personalizado, 
                    io.quantidade, 
                    io.valor_unitario 
             FROM itens_orcamento io 
             JOIN produtos p ON io.id_produto = p.id 
             WHERE io.id_orcamento = :id_orcamento";
$stmtItens = $pdo->prepare($sqlItens);
$stmtItens->execute(['id_orcamento' => $id_orcamento]);
$itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Orçamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Orçamento #<?= htmlspecialchars($orcamento['id']) ?></h2>
        <p>Data: <?= date('d/m/Y', strtotime($orcamento['data_criacao'])) ?></p>
        <p>Valor Total: R$ <?= number_format($orcamento['valor_total'], 2, ',', '.') ?></p>
        <a href="adicionarItensOrcamento.php?id_orcamento=<?= htmlspecialchars($orcamento['id']) ?>" class="btn btn-primary mb-3">Adicionar Item</a>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                    <th>Valor Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome_personalizado'] ?: $item['nome_produto']) ?></td>
                        <td><?= htmlspecialchars($item['quantidade']) ?></td>
                        <td>R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($item['quantidade'] * $item['valor_unitario'], 2, ',', '.') ?></td>
                        <td>
                            <a href="removerItemOrcamento.php?id=<?= htmlspecialchars($item['id']) ?>&id_orcamento=<?= htmlspecialchars($id_orcamento) ?>" class="btn btn-danger btn-sm">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <a href="finalizarOrcamento.php?id=<?= htmlspecialchars($id_orcamento) ?>" class="btn btn-success">Confirmar Orçamento</a>
        <a href="excluirOrcamento.php?id=<?= htmlspecialchars($id_orcamento) ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este orçamento? Esta ação não pode ser desfeita.')">Excluir Orçamento</a>

        <!-- Exibir Botões de Navegação -->
        <?php exibirBotoesNavegacao(); ?>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
