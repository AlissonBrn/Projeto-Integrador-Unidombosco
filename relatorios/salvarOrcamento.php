<?php
session_start();
include '../db.php'; // Conexão com o banco de dados

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$numeroOrcamento = $_POST['numero_orcamento'];
$data = $_POST['data'];
$idCliente = $_POST['id_cliente'];
$valorTotal = $_POST['valor_total'];
$itens = $_POST['itens'];

try {
    $pdo->beginTransaction();

    // Inserir orçamento
    $sqlOrcamento = "INSERT INTO orcamentos (numero_orcamento, data, id_cliente, valor_total) 
                     VALUES (:numero_orcamento, :data, :id_cliente, :valor_total)";
    $stmt = $pdo->prepare($sqlOrcamento);
    $stmt->execute([
        ':numero_orcamento' => $numeroOrcamento,
        ':data' => $data,
        ':id_cliente' => $idCliente,
        ':valor_total' => $valorTotal
    ]);

    $idOrcamento = $pdo->lastInsertId();

    // Inserir itens do orçamento
    $sqlItem = "INSERT INTO itens_orcamento (id_orcamento, id_produto, quantidade, valor_unitario, valor_total) 
                VALUES (:id_orcamento, :id_produto, :quantidade, :valor_unitario, :valor_total)";
    $stmtItem = $pdo->prepare($sqlItem);

    foreach ($itens as $item) {
        $stmtItem->execute([
            ':id_orcamento' => $idOrcamento,
            ':id_produto' => $item['id_produto'],
            ':quantidade' => $item['quantidade'],
            ':valor_unitario' => $item['valor_unitario'],
            ':valor_total' => $item['valor_total']
        ]);
    }

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
