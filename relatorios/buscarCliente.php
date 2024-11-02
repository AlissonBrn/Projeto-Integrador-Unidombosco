<?php
session_start();
include '../db.php'; // Conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT nome, endereco, email, telefone FROM clientes WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($cliente);
    exit;
}

$termo = $_GET['termo'] ?? '';
$sql = "SELECT id, nome FROM clientes WHERE nome LIKE :termo ORDER BY nome LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->execute(['termo' => "$termo%"]);
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($clientes);
