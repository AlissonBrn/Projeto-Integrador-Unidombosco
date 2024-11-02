<?php
// Incluir o arquivo de conexão com o banco de dados
include '../db.php';

// Verificar se o ID do pedido foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Excluir o pedido do banco de dados
    $sql = "DELETE FROM pedidos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    // Redirecionar para a lista de pedidos
    header("Location: listar.php");
} else {
    echo "ID de pedido não fornecido.";
}
?>
