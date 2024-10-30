<?php
// Incluir o arquivo de conexão com o banco de dados
include '../db.php';

// Verificar se um ID foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obter informações do produto pelo ID
    $sql = "SELECT * FROM produtos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $produto = $stmt->fetch();

    // Verificar se o produto foi encontrado
    if (!$produto) {
        echo "Produto não encontrado.";
        exit;
    }
}

// Verificar se o formulário foi enviado para atualizar os dados do produto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];

    // Atualizar os dados do produto no banco de dados
    $sql = "UPDATE produtos SET nome = :nome, descricao = :descricao, preco = :preco, quantidade = :quantidade WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nome' => $nome, 'descricao' => $descricao, 'preco' => $preco, 'quantidade' => $quantidade, 'id' => $id]);

    // Redirecionar para a lista de produtos
    header("Location: listar.php");
}
?>

<!-- Formulário HTML para editar produto -->
<h2>Editar Produto</h2>
<form method="POST" action="">
    Nome: <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
    Descrição: <input type="text" name="descricao" value="<?= htmlspecialchars($produto['descricao']) ?>" required>
    Preço: <input type="number" step="0.01" name="preco" value="<?= $produto['preco'] ?>" required>
    Quantidade: <input type="number" name="quantidade" value="<?= $produto['quantidade'] ?>" required>
    <button type="submit">Salvar Alterações</button>
</form>
