<?php
// Incluir o arquivo de conexão com o banco de dados
include '../db.php';

// Verificar se um ID foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obter informações do pedido pelo ID
    $sql = "SELECT * FROM pedidos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $pedido = $stmt->fetch();

    // Verificar se o pedido foi encontrado
    if (!$pedido) {
        echo "Pedido não encontrado.";
        exit;
    }
}

// Verificar se o formulário foi enviado para atualizar os dados do pedido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];

    // Atualizar o status do pedido no banco de dados
    $sql = "UPDATE pedidos SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['status' => $status, 'id' => $id]);

    // Redirecionar para a lista de pedidos
    header("Location: listar.php");
}
?>

<!-- Formulário HTML para editar pedido -->
<h2>Editar Pedido</h2>
<form method="POST" action="">
    <label>Status do Pedido:</label>
    <select name="status" required>
        <option value="Pendente" <?= $pedido['status'] == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
        <option value="Processado" <?= $pedido['status'] == 'Processado' ? 'selected' : '' ?>>Processado</option>
        <option value="Enviado" <?= $pedido['status'] == 'Enviado' ? 'selected' : '' ?>>Enviado</option>
        <option value="Concluído" <?= $pedido['status'] == 'Concluído' ? 'selected' : '' ?>>Concluído</option>
    </select>
    <button type="submit">Salvar Alterações</button>
</form>
