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

// Buscar produtos que correspondem ao termo (por nome ou código)
$sql = "SELECT id, nome, preco FROM produtos WHERE nome LIKE :termo OR id LIKE :termo LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->execute(['termo' => "%$termo%"]);
$produtos = $stmt->fetchAll();

// Gerar os resultados com botões de adição para cada produto
$html = '';
foreach ($produtos as $produto) {
    $html .= '
    <div class="card mt-2">
        <div class="card-body d-flex justify-content-between align-items-center">
            <span><strong>' . htmlspecialchars($produto['nome']) . '</strong> - Código: ' . $produto['id'] . '</span>
            <button type="button" class="btn btn-sm btn-success btn-adicionar-produto"
                    data-id="' . $produto['id'] . '"
                    data-nome="' . htmlspecialchars($produto['nome']) . '"
                    data-preco="' . $produto['preco'] . '">
                Adicionar
            </button>
        </div>
    </div>';
}

echo $html;
