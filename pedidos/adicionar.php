<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_POST['id_cliente'];
    $produtos = $_POST['produtos']; // Array de produtos
    $quantidade = $_POST['quantidade'];

    // Insere pedido e reduz estoque (implementação adicional necessária)
    // Cálculo de valores totalizados e outras operações

    header("Location: listar.php");
}
?>

<form method="POST" action="">
    Cliente: <select name="id_cliente">
        <!-- Listar clientes para selecionar -->
    </select>
    Produtos: <input type="text" name="produtos" required>
    Quantidade: <input type="number" name="quantidade" required>
    <button type="submit">Adicionar Pedido</button>
</form>
