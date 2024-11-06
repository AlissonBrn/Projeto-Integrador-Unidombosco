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

// Obtém o ID do orçamento a ser atualizado para o status de "imprimir"
if (isset($_GET['id'])) {
    $orcamentoId = $_GET['id'];
    
    // Atualiza o status do orçamento para "imprimir"
    $sqlAtualizarStatus = "UPDATE orcamentos SET status = 'imprimir' WHERE id = :id";
    $stmtAtualizarStatus = $pdo->prepare($sqlAtualizarStatus);
    $stmtAtualizarStatus->bindParam(':id', $orcamentoId, PDO::PARAM_INT);

    if ($stmtAtualizarStatus->execute()) {
        // Redireciona para a página de relatório de orçamentos
        header("Location: gerarRelatorio.php");
        exit;
    } else {
        echo "Erro ao atualizar o orçamento.";
    }
} else {
    echo "ID de orçamento inválido.";
}
