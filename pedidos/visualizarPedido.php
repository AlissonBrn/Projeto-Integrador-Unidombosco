<?php
session_start();
include '../db.php';
include '../funcoes.php';
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Verifica se o colaborador está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Obter o ID do pedido a partir do parâmetro URL
$idPedido = $_GET['id'] ?? null;

// Consulta o pedido e os dados do cliente
$sqlPedido = "SELECT o.*, c.nome AS cliente_nome, c.email AS cliente_email 
              FROM orcamentos o 
              JOIN clientes c ON o.id_cliente = c.id 
              WHERE o.id = :id";
$stmtPedido = $pdo->prepare($sqlPedido);
$stmtPedido->execute(['id' => $idPedido]);
$pedido = $stmtPedido->fetch();

// Consulta os itens do pedido
$sqlItens = "SELECT i.*, p.nome AS produto_nome, p.quantidade AS produto_estoque 
             FROM itens_orcamento i 
             JOIN produtos p ON i.id_produto = p.id 
             WHERE i.id_orcamento = :id";
$stmtItens = $pdo->prepare($sqlItens);
$stmtItens->execute(['id' => $idPedido]);
$itens = $stmtItens->fetchAll();

if (!$pedido) {
    echo "Pedido não encontrado.";
    exit;
}

// Processar a liberação do pedido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['liberar_pedido'])) {
    try {
        $pdo->beginTransaction();

        // Atualizar estoque dos produtos
        foreach ($itens as $item) {
            $novaQuantidade = $item['produto_estoque'] - $item['quantidade'];
            if ($novaQuantidade < 0) {
                throw new Exception("Estoque insuficiente para o produto {$item['produto_nome']}.");
            }

            $sqlAtualizarEstoque = "UPDATE produtos SET quantidade = :novaQuantidade WHERE id = :produtoId";
            $stmtAtualizarEstoque = $pdo->prepare($sqlAtualizarEstoque);
            $stmtAtualizarEstoque->execute([
                'novaQuantidade' => $novaQuantidade,
                'produtoId' => $item['id_produto']
            ]);
        }

        // Atualizar o status do pedido
        $sqlAtualizarPedido = "UPDATE orcamentos SET status = 'liberado' WHERE id = :id";
        $stmtAtualizarPedido = $pdo->prepare($sqlAtualizarPedido);
        $stmtAtualizarPedido->execute(['id' => $idPedido]);

        $pdo->commit();
        header("Location: listar.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao liberar o pedido: " . $e->getMessage();
    }
}

// Processar o cancelamento do pedido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancelar_pedido'])) {
    $sqlCancelarPedido = "UPDATE orcamentos SET status = 'cancelado' WHERE id = :id";
    $stmtCancelarPedido = $pdo->prepare($sqlCancelarPedido);
    $stmtCancelarPedido->execute(['id' => $idPedido]);

    header("Location: listar.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Pedido #<?= htmlspecialchars($pedido['numero_orcamento']) ?></h2>
        
        <h4>Dados do Cliente</h4>
        <p><strong>Nome:</strong> <?= htmlspecialchars($pedido['cliente_nome']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($pedido['cliente_email']) ?></p>

        <h4>Itens do Pedido</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['produto_nome']) ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>
                        <?php
                        $precoUnitario = $item['valor_unitario'] ?? 0; // Define 0 como padrão caso o índice não exista
                        echo "R$ " . number_format((float)$precoUnitario, 2, ',', '.');
                        ?>
                    </td>
                    <td>
                        <?php
                        $totalItem = $item['quantidade'] * (float)$precoUnitario;
                        echo "R$ " . number_format($totalItem, 2, ',', '.');
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Total do Pedido: R$ <?= number_format($pedido['valor_total'] ?? 0, 2, ',', '.') ?></h4>

        <form method="post">
            <button type="submit" name="liberar_pedido" class="btn btn-success">Liberar Pedido</button>
            <button type="submit" name="cancelar_pedido" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja cancelar este pedido?');">Cancelar Pedido</button>
            <a href="listar.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>
