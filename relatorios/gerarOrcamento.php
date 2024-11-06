<?php
session_start();
include '../db.php'; // Conexão com o banco de dados
include '../funcoes.php'; // Inclui a função para exibir botões de navegação

// Processa o formulário ao submeter o orçamento
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_POST['id_cliente'];
    $validade = $_POST['validade'];
    $prazo_entrega = $_POST['prazo_entrega'];
    $forma_pagamento = $_POST['forma_pagamento']; // Recebe a forma de pagamento do formulário
    $data = date("Y-m-d");

    // Insere o orçamento no banco de dados com a nova coluna forma_pagamento
    $sql = "INSERT INTO orcamentos (numero_orcamento, data, validade, prazo_entrega, id_cliente, valor_total, forma_pagamento) 
            VALUES (UUID_SHORT(), :data, :validade, :prazo_entrega, :id_cliente, 0.00, :forma_pagamento)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'data' => $data,
        'validade' => $validade,
        'prazo_entrega' => $prazo_entrega,
        'id_cliente' => $id_cliente,
        'forma_pagamento' => $forma_pagamento
    ]);
    $id_orcamento = $pdo->lastInsertId();

    // Redireciona para a página de adicionar itens ao orçamento
    header("Location: adicionarItensOrcamento.php?id_orcamento=$id_orcamento");
    exit;
}

// Busca os clientes cadastrados para exibir no formulário
$clientes = $pdo->query("SELECT id, nome FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Orçamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Novo Orçamento</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="id_cliente" class="form-label">Cliente</label>
                <select id="id_cliente" name="id_cliente" class="form-select" required>
                    <option value="">Selecione um cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="validade" class="form-label">Validade do Orçamento (dias)</label>
                <input type="number" id="validade" name="validade" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="prazo_entrega" class="form-label">Prazo de Entrega</label>
                <input type="text" id="prazo_entrega" name="prazo_entrega" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="forma_pagamento" class="form-label">Forma de Pagamento</label>
                <select id="forma_pagamento" name="forma_pagamento" class="form-select" required>
                    <option value="">Selecione a forma de pagamento</option>
                    <option value="Boleto">Boleto</option>
                    <option value="Dinheiro">Dinheiro</option>
                    <option value="Cartão Crédito">Cartão Crédito</option>
                    <option value="Cartão Débito">Cartão Débito</option>
                    <option value="Pix">Pix</option>
                    <option value="Crédito na Empresa">Crédito na Empresa</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Criar Orçamento</button>
        </form>
        
        <!-- Exibir Botões de Navegação -->
        <?php exibirBotoesNavegacao(); ?>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
