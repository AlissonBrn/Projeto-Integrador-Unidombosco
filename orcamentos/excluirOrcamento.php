<?php
session_start();
include '../db.php';
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Verifica se o colaborador está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Obtém o ID do orçamento a ser excluído
if (isset($_GET['id'])) {
    $id_orcamento = $_GET['id'];
    
    try {
        // Inicia uma transação para garantir a exclusão consistente
        $pdo->beginTransaction();
        
        // Exclui os itens associados ao orçamento
        $sqlExcluirItens = "DELETE FROM itens_orcamento WHERE id_orcamento = :id_orcamento";
        $stmtExcluirItens = $pdo->prepare($sqlExcluirItens);
        $stmtExcluirItens->execute(['id_orcamento' => $id_orcamento]);
        
        // Exclui o orçamento em si
        $sqlExcluirOrcamento = "DELETE FROM orcamentos WHERE id = :id_orcamento";
        $stmtExcluirOrcamento = $pdo->prepare($sqlExcluirOrcamento);
        $stmtExcluirOrcamento->execute(['id_orcamento' => $id_orcamento]);
        
        // Confirma a transação
        $pdo->commit();
        
        // Redireciona de volta para a página de listagem de orçamentos com mensagem de sucesso
        header("Location: listarOrcamento.php?msg=orcamento_excluido");
        exit;
        
    } catch (Exception $e) {
        // Em caso de erro, reverte a transação e exibe uma mensagem de erro
        $pdo->rollBack();
        echo "Erro ao excluir o orçamento: " . $e->getMessage();
    }
} else {
    echo "ID de orçamento inválido.";
}
?>

