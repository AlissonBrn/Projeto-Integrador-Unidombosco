<?php
session_start();
include '../db.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$termo = $_GET['termo'] ?? '';

$sql = "SELECT id, nome, preco FROM produtos WHERE nome LIKE :termo OR id LIKE :termo LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->execute(['termo' => "%$termo%"]);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = '<ul class="list-group">';
foreach ($produtos as $produto) {
    $html .= '
    <li class="list-group-item d-flex justify-content-between align-items-center produto-selecionado"
        data-id="' . $produto['id'] . '"
        data-nome="' . htmlspecialchars($produto['nome']) . '"
        data-preco="' . $produto['preco'] . '">
        <span><strong>' . htmlspecialchars($produto['nome']) . '</strong> - Código: ' . $produto['id'] . '</span>
        <input type="number" class="form-control form-control-sm w-25 me-2 quantidade-produto" placeholder="Qtd" min="1" value="1">
        <button class="btn btn-sm btn-success btn-add-produto" data-id="' . $produto['id'] . '" data-nome="' . htmlspecialchars($produto['nome']) . '" data-preco="' . $produto['preco'] . '">Adicionar</button>
    </li>';
}
$html .= '</ul>';

echo $html;
