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

// Buscar produtos que correspondem ao termo (nome ou código)
$sql = "SELECT id, nome, preco FROM produtos WHERE nome LIKE :termo OR id LIKE :termo LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->execute(['termo' => "%$termo%"]);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gerar HTML com a lista de produtos, campo de quantidade e valor unitário
$html = '<ul class="list-group">';
foreach ($produtos as $produto) {
    $html .= '
    <li class="list-group-item d-flex justify-content-between align-items-center produto-selecionado"
        data-id="' . $produto['id'] . '"
        data-nome="' . htmlspecialchars($produto['nome']) . '">
        <span><strong>' . htmlspecialchars($produto['nome']) . '</strong> - Código: ' . $produto['id'] . '</span>
        <input type="number" class="form-control form-control-sm w-25 me-2 quantidade-produto" placeholder="Qtd" min="1" value="1">
        <input type="number" class="form-control form-control-sm w-25 me-2 valor-unitario-produto" placeholder="Valor Unitário" min="0.01" step="0.01" value="' . $produto['preco'] . '">
        <button class="btn btn-sm btn-success btn-add-produto" data-id="' . $produto['id'] . '" data-nome="' . htmlspecialchars($produto['nome']) . '">Adicionar</button>
    </li>';
}
$html .= '</ul>';

echo $html;
