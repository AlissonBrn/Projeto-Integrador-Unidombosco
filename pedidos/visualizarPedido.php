<?php
session_start();
include '../db.php'; // Conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Obter o ID do pedido
$id_pedido = $_GET['id'] ?? null;

// Buscar detalhes do pedido e do cliente
$sql_pedido = "
    SELECT p.*, c.nome AS cliente_nome, c.endereco AS cliente_endereco
    FROM pedidos p
    JOIN clientes c ON p.id_cliente = c.id
    WHERE p.id = :id";
$stmt = $pdo->prepare($sql_pedido);
$stmt->execute(['id' => $id_pedido]);
$pedido = $stmt->fetch();

if (!$pedido) {
    echo "Pedido não encontrado.";
    exit;
}

// Buscar produtos do pedido
$sql_produtos = "
    SELECT p.nome, p.marca, ip.quantidade, ip.preco_unitario
    FROM itens_pedido ip
    JOIN produtos p ON ip.id_produto = p.id
    WHERE ip.id_pedido = :id";
$stmt = $pdo->prepare($sql_produtos);
$stmt->execute(['id' => $id_pedido]);
$produtos = $stmt->fetchAll();
