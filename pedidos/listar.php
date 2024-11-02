<?php
// Incluir o arquivo de conexão com o banco de dados
include '../db.php';

// Consultar todos os pedidos e os clientes associados
$sql = "SELECT pedidos.id, pedidos.data_pedido, pedidos.status, pedidos.total, clientes.nome AS cliente_nome
        FROM pedidos
        JOIN clientes ON pedidos.id_cliente = clientes.id";
$stmt = $pdo->query($sql);
$pedidos = $stmt->fetchAll();
?>

<!-- Tabela de Pedidos -->
<h2>Lista de Pedidos</h2>
<a href="adicionar.php">Adicionar Novo Pedido</a>
<table>
    <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Data do Pedido</th>
        <th>Status</th>
        <th>Total (R$)</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($pedidos as $pedido): ?>
    <tr>
        <td><?= $pedido['id'] ?></td>
        <td><?= htmlspecialchars($pedido['cliente_nome']) ?></td>
        <td><?= date("d/m/Y H:i", strtotime($pedido['data_pedido'])) ?></td>
        <td><?= htmlspecialchars($pedido['status']) ?></td>
        <td><?= number_format($pedido['total'], 2, ',', '.') ?></td>
        <td>
            <a href="editar.php?id=<?= $pedido['id'] ?>" class="btn btn-edit">Editar</a>
            <a href="deletar.php?id=<?= $pedido['id'] ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir este pedido?');">Deletar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
