<?php
session_start();
include '../db.php';
include '../funcoes.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Gerar o número do orçamento único ao carregar a página
$numeroOrcamento = 'ORC-' . time();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Orçamento - Sistema de Vendas para Laboratórios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Geração de Orçamento</h2>
        
        <!-- Mostrar Número do Orçamento -->
        <div class="card mb-3">
            <div class="card-body">
                <h5>Número do Orçamento: <span id="numero_orcamento"><?php echo htmlspecialchars($numeroOrcamento); ?></span></h5>
            </div>
        </div>

        <!-- Seção de Informações do Cliente -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Informações do Cliente</h5>
                <div class="row">
                    <div class="col-md-3 position-relative">
                        <label>Nome do Cliente</label>
                        <input type="text" id="nome_cliente" class="form-control" placeholder="Digite o nome do cliente...">
                        <div id="sugestoes_cliente" class="list-group position-absolute w-100" style="z-index: 10;"></div>
                    </div>
                    <div class="col-md-3">
                        <label>Endereço</label>
                        <input type="text" id="endereco_cliente" class="form-control" readonly>
                    </div>
                    <div class="col-md-3">
                        <label>E-mail</label>
                        <input type="email" id="email_cliente" class="form-control" readonly>
                    </div>
                    <div class="col-md-3">
                        <label>Telefone</label>
                        <input type="text" id="telefone_cliente" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Adicionar Produtos -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Adicionar Produtos ao Orçamento</h5>
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <label>Buscar Produto</label>
                        <input type="text" id="pesquisa_produto" class="form-control" placeholder="Digite o nome ou código do produto...">
                        <small class="text-muted">Digite "geral" para adicionar um produto manualmente</small>
                        <div id="lista_produtos" class="mt-2"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Produtos Adicionados ao Orçamento -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Produtos no Orçamento</h5>
                    <button id="toggle_lista_produtos" class="btn btn-secondary btn-sm">Esconder Produtos</button>
                </div>
                <div id="lista_produtos_orcamento" class="mb-4"></div>
                <h6>Total do Orçamento: R$<span id="total_orcamento">0.00</span></h6>
            </div>
        </div>

        <!-- Botões para Finalizar ou Cancelar -->
        <div class="mt-4 d-flex justify-content-end">
            <button type="button" class="btn btn-success me-2" id="finalizar_orcamento">Finalizar Orçamento</button>
            <button onclick="window.history.back()" class="btn btn-secondary">Cancelar</button>
        </div>
    </div>

    <!-- Modal para Editar Produto -->
    <div class="modal fade" id="modalEditarProduto" tabindex="-1" aria-labelledby="modalEditarProdutoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarProdutoLabel">Editar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_produto_id">
                    <div class="mb-3">
                        <label>Nome do Produto</label>
                        <input type="text" id="edit_produto_nome" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Quantidade</label>
                        <input type="number" id="edit_produto_quantidade" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Valor Unitário</label>
                        <input type="number" id="edit_produto_preco" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="salvarEdicaoProduto()">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let totalOrcamento = 0;

            // Sugestões de Clientes
            $('#nome_cliente').on('input', function () {
                let termo = $(this).val();
                if (termo.length >= 1) {
                    $.getJSON('buscarCliente.php', { termo: termo }, function (data) {
                        $('#sugestoes_cliente').empty();
                        $.each(data, function (index, cliente) {
                            $('#sugestoes_cliente').append(
                                `<button type="button" class="list-group-item list-group-item-action" data-id="${cliente.id}" data-nome="${cliente.nome}">
                                    ${cliente.nome}
                                </button>`
                            );
                        });
                    });
                } else {
                    $('#sugestoes_cliente').empty();
                }
            });

            // Selecionar cliente da lista
            $(document).on('click', '#sugestoes_cliente .list-group-item', function () {
                let clienteId = $(this).data('id');
                $('#nome_cliente').val($(this).data('nome'));
                $('#sugestoes_cliente').empty();

                $.get('buscarCliente.php', { id: clienteId }, function (data) {
                    let cliente = JSON.parse(data);
                    $('#endereco_cliente').val(cliente.endereco);
                    $('#email_cliente').val(cliente.email);
                    $('#telefone_cliente').val(cliente.telefone);
                });
            });

            // Sugestões de Produtos e Produtos Manuais
            $('#pesquisa_produto').on('input', function () {
                let termo = $(this).val();
                if (termo.toLowerCase() === "geral") {
                    $('#lista_produtos').html(`
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5>Produto Manual</h5>
                                <input type="text" placeholder="Nome do Produto" class="form-control mb-2" id="nome_manual">
                                <input type="number" placeholder="Quantidade" class="form-control mb-2" id="quantidade_manual">
                                <input type="number" placeholder="Valor Unitário" class="form-control" id="valor_unitario_manual">
                                <button class="btn btn-success mt-2" onclick="adicionarProdutoManual()">Adicionar ao Orçamento</button>
                            </div>
                        </div>
                    `);
                } else if (termo.length >= 2) {
                    $.get('buscarProduto.php', { termo: termo }, function (data) {
                        $('#lista_produtos').html(data);
                    });
                }
            });

            // Captura a ação de adicionar o produto com quantidade e valor definidos
            $(document).on('click', '.btn-add-produto', function () {
                let produtoId = $(this).data('id');
                let produtoNome = $(this).data('nome');
                let produtoPreco = parseFloat($(this).closest('li').find('.valor-unitario-produto').val());
                let quantidade = parseInt($(this).closest('li').find('.quantidade-produto').val());

                if (quantidade > 0 && produtoPreco > 0) {
                    adicionarProdutoAoOrcamento(produtoId, produtoNome, quantidade, produtoPreco);
                    $('#lista_produtos').empty();
                } else {
                    alert("Por favor, insira uma quantidade e um valor unitário válidos.");
                }
            });

            // Função para Adicionar Produtos ao Orçamento
            function adicionarProdutoAoOrcamento(id, nome, quantidade, preco) {
                let precoTotal = quantidade * preco;
                totalOrcamento += precoTotal;
                $('#total_orcamento').text(totalOrcamento.toFixed(2));

                $('#lista_produtos_orcamento').append(`
                    <div class="d-flex justify-content-between align-items-center mt-2 p-2 border rounded">
                        <span><strong>${nome}</strong> - Quantidade: ${quantidade}, Valor Unitário: R$${preco.toFixed(2)}</span>
                        <div>
                            <button class="btn btn-warning btn-sm me-2" onclick="abrirModalEditarProduto('${id}', '${nome}', ${quantidade}, ${preco})">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="removerProduto(this, ${precoTotal})">Excluir</button>
                        </div>
                    </div>
                `);
            }

            function adicionarProdutoManual() {
                let nome = $('#nome_manual').val();
                let quantidade = parseFloat($('#quantidade_manual').val());
                let valorUnitario = parseFloat($('#valor_unitario_manual').val());

                if (nome && quantidade > 0 && valorUnitario > 0) {
                    adicionarProdutoAoOrcamento('manual', nome, quantidade, valorUnitario);
                    $('#lista_produtos').empty();
                } else {
                    alert('Por favor, preencha o nome, a quantidade e o valor unitário do produto manual.');
                }
            }

            function removerProduto(elemento, precoTotal) {
                $(elemento).closest('.d-flex').remove();
                totalOrcamento -= precoTotal;
                $('#total_orcamento').text(totalOrcamento.toFixed(2));
            }

            window.abrirModalEditarProduto = function(id, nome, quantidade, preco) {
                $('#edit_produto_id').val(id);
                $('#edit_produto_nome').val(nome);
                $('#edit_produto_quantidade').val(quantidade);
                $('#edit_produto_preco').val(preco);
                $('#modalEditarProduto').modal('show');
            }

            window.salvarEdicaoProduto = function() {
                let quantidade = parseFloat($('#edit_produto_quantidade').val());
                let preco = parseFloat($('#edit_produto_preco').val());
                let precoTotal = quantidade * preco;
                totalOrcamento += precoTotal;
                $('#total_orcamento').text(totalOrcamento.toFixed(2));
                $('#modalEditarProduto').modal('hide');
            }

            $('#toggle_lista_produtos').click(function () {
                $('#lista_produtos_orcamento').toggleClass('hidden');
                $(this).text(function(i, text){
                    return text === "Esconder Produtos" ? "Exibir Produtos" : "Esconder Produtos";
                });
            });

            // Função para finalizar o orçamento e enviar para salvar
            $('#finalizar_orcamento').click(function () {
                let numeroOrcamento = $('#numero_orcamento').text(); // Obter o número do orçamento
                let data = new Date().toISOString().split('T')[0]; // Data atual
                let valorTotal = parseFloat($('#total_orcamento').text());

                let orcamento = {
                    numero_orcamento: numeroOrcamento,
                    data: data,
                    id_cliente: $('#nome_cliente').data('id'),
                    valor_total: valorTotal,
                    itens: []
                };

                $('#lista_produtos_orcamento .d-flex').each(function () {
                    let id = $(this).data('id');
                    let quantidade = $(this).find('.quantidade').text();
                    let valorUnitario = $(this).find('.valor-unitario').text();

                    orcamento.itens.push({
                        id_produto: id,
                        quantidade: parseInt(quantidade),
                        valor_unitario: parseFloat(valorUnitario),
                        valor_total: parseFloat(valorUnitario) * parseInt(quantidade)
                    });
                });

                $.post('salvarOrcamento.php', orcamento, function (response) {
                    if (response.success) {
                        window.location.href = 'relatorioOrcamento.php?numero_orcamento=' + numeroOrcamento;
                    } else {
                        alert('Erro ao salvar o orçamento.');
                    }
                }, 'json');
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
