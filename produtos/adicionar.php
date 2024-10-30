<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];

    $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade) VALUES (:nome, :descricao, :preco, :quantidade)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nome' => $nome, 'descricao' => $descricao, 'preco' => $preco, 'quantidade' => $quantidade]);

    header("Location: listar.php");
}
?>

<form method="POST" action="">
    Nome: <input type="text" name="nome" required>
    Descrição: <input type="text" name="descricao" required>
    Preço: <input type="text" name="preco" required>
    Quantidade: <input type="text" name="quantidade" required>
    <button type="submit">Adicionar Produto</button>
</form>
