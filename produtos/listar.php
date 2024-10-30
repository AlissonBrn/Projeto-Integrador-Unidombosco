<?php
// Incluir o arquivo de conexão com o banco de dados
include '../db.php';

// Consultar todos os produtos
$sql = "SELECT * FROM produtos";
$stmt = $pdo->query($sql);
$produtos = $stmt->fetchAll();
?>

<!-- Tabela de Produtos -->
<h2>Lista de Produtos</h2>
<a href="adicionar.php">Adicionar Novo Produto</a>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Preço</th>
        <th>Quantidade</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($produtos as $produto): ?>
    <tr>
        <td><?= $produto['id'] ?></td>
        <td><?= htmlspecialchars($produto['nome']) ?></td>
        <td><?= htmlspecialchars($produto['descricao']) ?></td>
        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
        <td><?= $produto['quantidade'] ?></td>
        <td>
            <a href="editar.php?id=<?= $produto['id'] ?>" class="btn btn-edit">Editar</a>
            <a href="deletar.php?id=<?= $produto['id'] ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Deletar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
