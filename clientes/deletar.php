<?php
// incluir db.php para conexão com o banco de dados
include '../db.php';

// Verificar se o ID do cliente foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Excluir cliente do banco de dados
    $sql = "DELETE FROM clientes WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    // Redirecionar para a lista de clientes
    header("Location: listar.php");
} else {
    echo "ID de cliente não fornecido.";
}
?>
