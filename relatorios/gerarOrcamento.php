<?php
session_start();
include '../db.php'; // Conexão com o banco de dados
include '../funcoes.php'; // Inclui a função para exibir botões de navegação

// Verificação de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Processar o formulário de geração de orçamento
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_orcamento = $_POST['numero_orcamento'];
    $data = date('Y-m-d');
    $validade = $_POST['validade'];
    $prazo_entrega = $_POST['prazo_entrega'];
    $id_cliente = $_POST['id_cliente'];
    $produtos_selecionados = $_POST['produtos'];
    $valor_total = 0;

    // Calcular o valor total do orçamento
    foreach ($produtos_selecionados as $produto_id => $dados) {
        $valor_total += $dados['valor_unitario'] * $dados['quantidade'];
    }

    // Inserir orçamento no banco de dados
    $sql_orcamento = "INSERT INTO orcamentos (numero_orcamento, data, validade, prazo_entrega, id_cliente, valor_total)
                      VALUES (:numero_orcamento, :data, :validade, :prazo_entrega, :id_cliente, :valor_total)";
    $stmt = $pdo->prepare($sql_orcamento);
    $stmt->execute([
        'numero_orcamento' => $numero_orcamento,
        'data' => $data,
        'validade' => $validade,
        'prazo_entrega' => $prazo_entrega,
        'id_cliente' => $id_cliente,
        'valor_total' => $valor_total
    ]);

    $id_orcamento = $pdo->lastInsertId();

    // Inserir produtos selecionados no orçamento
    foreach ($produtos_selecionados as $produto_id => $dados) {
        if ($produto_id === 'manual') { // Verificar se é um produto manual
            $sql_item = "INSERT INTO itens_orcamento (id_orcamento, nome_manual, quantidade, valor_unitario)
                         VALUES (:id_orcamento, :nome_manual, :quantidade, :valor_unitario)";
            $stmt = $pdo->prepare($sql_item);
            $stmt->execute([
                'id_orcamento' => $id_orcamento,
                'nome_manual' => $dados['nome'],
                'quantidade' => $dados['quantidade'],
                'valor_unitario' => $dados['valor_unitario']
            ]);
        } else {
            $sql_item = "INSERT INTO itens_orcamento (id_orcamento, id_produto, quantidade, valor_unitario)
                         VALUES (:id_orcamento, :id_produto, :quantidade, :valor_unitario)";
            $stmt = $pdo->prepare($sql_item);
            $stmt->execute([
                'id_orcamento' => $id_orcamento,
                'id_produto' => $produto_id,
                'quantidade' => $dados['quantidade'],
                'valor_unitario' => $dados['valor_unitario']
            ]);
        }
    }

    $sucesso = "Orçamento gerado com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Orçamento - Sistema de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Gerar Orçamento</h2>

        <?php if (isset($sucesso)): ?>
            <div class="alert alert-success"><?= $sucesso ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="numero_orcamento" class="form-label">Número do Orçamento</label>
                <input type="text" id="numero_orcamento" name="numero_orcamento" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="data" class="form-label">Data</label>
                <input type="text" id="data" name="data" class="form-control" value="<?= date('Y-m-d') ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="validade" class="form-label">Validade do Orçamento (em dias)</label>
                <input type="number" id="validade" name="validade" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="prazo_entrega" class="form-label">Prazo de Entrega</label>
                <input type="text" id="prazo_entrega" name="prazo_entrega" class="form-control" required>
            </div>

            <!-- Campo de Pesquisa de Cliente -->
            <div class="mb-3">
                <label for="id_cliente" class="form-label">Cliente</label>
                <input type="text" id="pesquisa_cliente" class="form-control" placeholder="Digite o nome do cliente...">
                <select id="id_cliente" name="id_cliente" class="form-select mt-2" required>
                    <option value="">Selecione um cliente</option>
                </select>
            </div>

            <!-- Campo de Pesquisa de Produto -->
            <div class="mb-3">
                <label for="pesquisa_produto" class="form-label">Adicionar Produtos ao Orçamento</label>
                <input type="text" id="pesquisa_produto" class="form-control" placeholder="Digite o nome ou código do produto...">
                <div id="lista_produtos" class="mt-2"></div>
            </div>

            <!-- Área para produtos selecionados -->
            <div id="produtos_selecionados" class="mb-3"></div>

            <!-- Botão para adicionar produtos manuais -->
            <button type="button" id="adicionar_produto_manual" class="btn btn-secondary mt-2">Adicionar Produto Manual</button>

            <button type="submit" class="btn btn-primary mt-4">Gerar Orçamento</button>
        </form>

        <!-- Exibir Botões de Navegação (Voltar e Início) -->
        <div class="d-flex justify-content-between mt-4">
            <button onclick="window.history.back()" class="btn btn-outline-secondary">Voltar</button>
            <a href="../index.php" class="btn btn-outline-primary">Início</a>
        </div>
    </div>

    <!-- Script para Pesquisar Clientes e Produtos -->
    <script>
        $(document).ready(function () {
            // Função para pesquisar clientes
            $('#pesquisa_cliente').on('input', function () {
                let termo = $(this).val();
                if (termo.length >= 2) {
                    $.get('buscarCliente.php', { termo: termo }, function (data) {
                        $('#id_cliente').html(data);
                    });
                }
            });

            // Função para pesquisar produtos
            $('#pesquisa_produto').on('input', function () {
                let termo = $(this).val();
                if (termo.length >= 2) {
                    $.get('buscarProduto.php', { termo: termo }, function (data) {
                        $('#lista_produtos').html(data);
                    });
                }
            });

            // Adicionar produto selecionado ao orçamento
            $(document).on('click', '.btn-adicionar-produto', function () {
                const produtoId = $(this).data('id');
                const produtoNome = $(this).data('nome');
                const produtoPreco = $(this).data('preco');

                $('#produtos_selecionados').append(`
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5>${produtoNome}</h5>
                            <input type="hidden" name="produtos[${produtoId}][nome]" value="${produtoNome}">
                            <input type="hidden" name="produtos[${produtoId}][id]" value="${produtoId}">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Quantidade</label>
                                    <input type="number" name="produtos[${produtoId}][quantidade]" class="form-control" min="1" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Valor Unitário</label>
                                    <input type="number" name="produtos[${produtoId}][valor_unitario]" class="form-control" value="${produtoPreco}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });

            // Adicionar produto manual
            $('#adicionar_produto_manual').on('click', function () {
                const produtoManualId = 'manual_' + Math.random().toString(36).substr(2, 9);
                $('#produtos_selecionados').append(`
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5>Produto Manual</h5>
                            <input type="text" name="produtos[${produtoManualId}][nome]" placeholder="Nome do produto" class="form-control mb-2" required>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Quantidade</label>
                                    <input type="number" name="produtos[${produtoManualId}][quantidade]" class="form-control" min="1" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Valor Unitário</label>
                                    <input type="number" name="produtos[${produtoManualId}][valor_unitario]" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
