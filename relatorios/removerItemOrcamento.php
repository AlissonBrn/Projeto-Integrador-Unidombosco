<?php
session_start();
include '../db.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];
$id_orcamento = $_GET['id_orcamento'];

// Consultar o valor do item a ser removido
$sqlItem = "SELECT quantidade * valor_unitario AS valor_total_item FROM itens_orcamento WHERE id = :id";
$stmtItem = $pdo->prepare($sqlItem);
$stmtItem->execute(['id' => $id]);
$valor_total_item = $stmtItem->fetchColumn();

// Remover o item do orçamento
$sqlRemoverItem = "DELETE FROM itens_orcamento WHERE id = :id";
$stmtRemoverItem = $pdo->prepare($sqlRemoverItem);
$stmtRemoverItem->execute(['id' => $id]);

// Atualizar o valor total do orçamento
$sqlAtualizarOrcamento = "UPDATE orcamentos SET valor_total = valor_total - :valor_total_item WHERE id = :id_orcamento";
$stmtAtualizarOrcamento = $pdo->prepare($sqlAtualizarOrcamento);
$stmtAtualizarOrcamento->execute([
    'valor_total_item' => $valor_total_item,
    'id_orcamento' => $id_orcamento
]);

header("Location: visualizarOrcamento.php?id=$id_orcamento");
exit;
?>
