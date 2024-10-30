<?php
// incluir db.php para conexão com o banco de dados
include '../db.php';

// Verificar se um ID foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obter informações do cliente pelo ID
    $sql = "SELECT * FROM clientes WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $cliente = $stmt->fetch();

    // Verificar se o cliente foi encontrado
    if (!$cliente) {
        echo "Cliente não encontrado.";
        exit;
    }
}

// Verificar se o formulário foi enviado para atualizar os dados do cliente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $contato = $_POST['contato'];

    // Atualizar os dados do cliente no banco de dados
    $sql = "UPDATE clientes SET nome = :nome, endereco = :endereco, contato = :contato WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nome' => $nome, 'endereco' => $endereco, 'contato' => $contato, 'id' => $id]);

    // Redirecionar para a lista de clientes
    header("Location: listar.php");
}
?>

<!-- Formulário HTML para editar cliente -->
<h2>Editar Cliente</h2>
<form method="POST" action="">
    Nome: <input type="text" name="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required>
    Endereço: <input type="text" name="endereco" value="<?= htmlspecialchars($cliente['endereco']) ?>" required>
    Contato: <input type="text" name="contato" value="<?= htmlspecialchars($cliente['contato']) ?>" required>
    <button type="submit">Salvar Alterações</button>
</form>
