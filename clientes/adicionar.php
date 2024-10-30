<?php
// incluir db.php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $contato = $_POST['contato'];

    $sql = "INSERT INTO clientes (nome, endereco, contato) VALUES (:nome, :endereco, :contato)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nome' => $nome, 'endereco' => $endereco, 'contato' => $contato]);

    header("Location: listar.php");
}
?>

<!-- Formulário HTML para adicionar cliente -->
<form method="POST" action="">
    Nome: <input type="text" name="nome" required>
    Endereço: <input type="text" name="endereco" required>
    Contato: <input type="text" name="contato" required>
    <button type="submit">Adicionar Cliente</button>
</form>
