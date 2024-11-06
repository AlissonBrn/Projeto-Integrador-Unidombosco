<?php
session_start();
include '../db.php';

// Verifica se o colaborador está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Verifica o ID do orçamento e seu status
if (isset($_GET['id'])) {
    $orcamentoId = $_GET['id'];
    $sqlOrcamento = "SELECT status FROM orcamentos WHERE id = :id";
    $stmtOrcamento = $pdo->prepare($sqlOrcamento);
    $stmtOrcamento->bindParam(':id', $orcamentoId, PDO::PARAM_INT);
    $stmtOrcamento->execute();
    $orcamento = $stmtOrcamento->fetch(PDO::FETCH_ASSOC);

    // Impede edição se o orçamento estiver finalizado
    if ($orcamento['status'] == 'finalizado') {
        echo "<p>Orçamento já finalizado. Não pode ser editado.</p>";
        exit;
    }

    // Código para carregar e exibir detalhes do orçamento para edição...
} else {
    echo "<p>ID de orçamento inválido.</p>";
    exit;
}
?>
