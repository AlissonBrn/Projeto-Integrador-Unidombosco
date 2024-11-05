<?php
session_start();
include '../db.php'; // Conexão com o banco de dados

// Verificar se o colaborador está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Obter o ID do produto a partir do parâmetro URL
$id = $_GET['id'] ?? null;

// Verificar se o ID do produto foi fornecido
if (!$id) {
    echo "Produto não encontrado.";
    exit;
}

// Deletar o produto do banco de dados
$sql = "DELETE FROM produtos WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);

header("Location: listar.php");
exit;
