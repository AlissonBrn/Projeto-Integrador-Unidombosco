<?php
include '../db.php';

$sql = "SELECT * FROM clientes";
$stmt = $pdo->query($sql);
$clientes = $stmt->fetchAll();
?>

<!-- Exibir clientes -->
<h2>Lista de Clientes</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Endereço</th>
        <th>Contato</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($clientes as $cliente): ?>
    <tr>
        <td><?= $cliente['id'] ?></td>
        <td><?= $cliente['nome'] ?></td>
        <td><?= $cliente['endereco'] ?></td>
        <td><?= $cliente['contato'] ?></td>
        <td>
            <a href="editar.php?id=<?= $cliente['id'] ?>">Editar</a>
            <a href="deletar.php?id=<?= $cliente['id'] ?>">Deletar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
