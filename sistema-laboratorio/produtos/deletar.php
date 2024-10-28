<?php
// Incluir o arquivo de conexão com o banco de dados
include '../db.php';

// Verificar se o ID do produto foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Excluir produto do banco de dados
    $sql = "DELETE FROM produtos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    // Redirecionar para a lista de produtos
    header("Location: listar.php");
} else {
    echo "ID de produto não fornecido.";
}
?>
