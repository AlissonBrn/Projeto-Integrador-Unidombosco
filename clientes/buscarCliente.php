<?php
session_start();
include '../db.php'; // Conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Obter o termo de pesquisa enviado via GET
$termo = $_GET['termo'] ?? '';

// Buscar clientes que correspondem ao termo
$sql = "SELECT id, nome FROM clientes WHERE nome LIKE :termo LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->execute(['termo' => "%$termo%"]);
$clientes = $stmt->fetchAll();

// Gerar as opções de clientes para o dropdown
$options = '<option value="">Selecione um cliente</option>';
foreach ($clientes as $cliente) {
    $options .= '<option value="' . $cliente['id'] . '">' . htmlspecialchars($cliente['nome']) . '</option>';
}

echo $options;
