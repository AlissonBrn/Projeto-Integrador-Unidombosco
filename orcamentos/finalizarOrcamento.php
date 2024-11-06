<?php
session_start();
include '../db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Verifica se o colaborador está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Obtém o ID do orçamento a ser finalizado
if (isset($_GET['id'])) {
    $orcamentoId = $_GET['id'];
    
    // Atualiza o status do orçamento para "finalizado"
    $sqlFinalizar = "UPDATE orcamentos SET status = 'finalizado' WHERE id = :id";
    $stmtFinalizar = $pdo->prepare($sqlFinalizar);
    $stmtFinalizar->bindParam(':id', $orcamentoId, PDO::PARAM_INT);

    if ($stmtFinalizar->execute()) {
        // Redireciona para a página de relatório de orçamentos
        header("Location: ../pedidos/listar.php");
        exit;
    } else {
        echo "Erro ao finalizar ao confirmar o orçamento.";
    }
} else {
    echo "ID de orçamento inválido.";
}
