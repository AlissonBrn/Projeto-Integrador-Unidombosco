$(document).ready(function () {
    let totalOrcamento = 0;

    // Busca de Clientes e Sugestões
    $('#cliente').on('input', function () {
        let termo = $(this).val();
        if (termo.length >= 2) {
            $.getJSON('../relatorios/buscarCliente.php', { termo: termo }, function (data) {
                $('#sugestoes_cliente').empty();
                data.forEach(cliente => {
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

    // Selecionar Cliente
    $(document).on('click', '#sugestoes_cliente .list-group-item', function () {
        let clienteId = $(this).data('id');
        let clienteNome = $(this).data('nome');

        // Preencher o campo cliente com o nome selecionado e armazenar o ID
        $('#cliente').val(clienteNome);
        $('#cliente').data('id', clienteId);
        $('#sugestoes_cliente').empty();
    });

    // Busca de Produtos e Sugestões
    $('#produto').on('input', function () {
        let termo = $(this).val();
        if (termo.toLowerCase() === "geral") {
            // Exibir formulário para produto manual
            $('#sugestoes_produto').html(`
                <div class="card mt-3">
                    <div class="card-body">
                        <h5>Adicionar Produto Manual</h5>
                        <input type="text" id="nome_manual" class="form-control mb-2" placeholder="Nome do Produto" required>
                        <input type="number" id="quantidade_manual" class="form-control mb-2" placeholder="Quantidade" required>
                        <input type="number" step="0.01" id="valor_unitario_manual" class="form-control" placeholder="Valor Unitário" required>
                        <button class="btn btn-success mt-2" id="adicionarProdutoManual">Adicionar ao Orçamento</button>
                    </div>
                </div>
            `);
        } else if (termo.length >= 2) {
            // Buscar produtos no banco se o termo tiver ao menos 2 caracteres
            $.get('../relatorios/buscarProduto.php', { termo: termo }, function (data) {
                $('#sugestoes_produto').html(data);
            });
        } else {
            $('#sugestoes_produto').empty();
        }
    });

    // Adicionar Produto ao Orçamento
    $(document).on('click', '.btn-add-produto', function () {
        let produtoId = $(this).data('id');
        let produtoNome = $(this).data('nome');
        let precoUnitario = parseFloat($(this).closest('li').find('.valor-unitario-produto').val());
        let quantidade = parseInt($(this).closest('li').find('.quantidade-produto').val());

        if (quantidade > 0 && precoUnitario > 0) {
            let subtotal = quantidade * precoUnitario;
            totalOrcamento += subtotal;

            // Exibir produto no orçamento
            $('#lista_produtos').append(`
                <tr data-id="${produtoId}">
                    <td>${produtoNome}</td>
                    <td>${quantidade}</td>
                    <td>R$${precoUnitario.toFixed(2)}</td>
                    <td>R$${subtotal.toFixed(2)}</td>
                    <td><button class="btn btn-danger btn-sm remover_produto">Remover</button></td>
                </tr>
            `);
            $('#total_orcamento').text(totalOrcamento.toFixed(2));
            $('#sugestoes_produto').empty();
            $('#produto').val('');
        } else {
            alert("Por favor, insira uma quantidade e um valor unitário válidos.");
        }
    });

    // Função para adicionar o produto manual ao orçamento
    $(document).on('click', '#adicionarProdutoManual', function () {
        let nome = $('#nome_manual').val();
        let quantidade = parseInt($('#quantidade_manual').val());
        let valorUnitario = parseFloat($('#valor_unitario_manual').val());

        if (nome && quantidade > 0 && valorUnitario > 0) {
            let subtotal = quantidade * valorUnitario;
            totalOrcamento += subtotal;

            // Exibir produto manual no orçamento
            $('#lista_produtos').append(`
                <tr>
                    <td>${nome} (Produto Manual)</td>
                    <td>${quantidade}</td>
                    <td>R$${valorUnitario.toFixed(2)}</td>
                    <td>R$${subtotal.toFixed(2)}</td>
                    <td><button class="btn btn-danger btn-sm remover_produto">Remover</button></td>
                </tr>
            `);

            $('#total_orcamento').text(totalOrcamento.toFixed(2));
            $('#sugestoes_produto').empty();
            $('#produto').val('');
        } else {
            alert('Por favor, preencha todos os campos do produto manual corretamente.');
        }
    });

    // Remover Produto do Orçamento
    $(document).on('click', '.remover_produto', function () {
        let subtotal = parseFloat($(this).closest('tr').find('td:eq(3)').text().replace('R$', '').replace(',', '.'));
        totalOrcamento -= subtotal;
        $('#total_orcamento').text(totalOrcamento.toFixed(2));
        $(this).closest('tr').remove();
    });

    // Finalizar Orçamento
    $('#finalizar_orcamento').click(function () {
        let clienteId = $('#cliente').data('id');
        if (!clienteId || $('#lista_produtos tr').length === 0) {
            alert('Selecione um cliente e adicione ao menos um produto.');
            return;
        }

        let numeroOrcamento = $('#numero_orcamento').text();
        let itens = [];

        $('#lista_produtos tr').each(function () {
            let produtoId = $(this).data('id');
            let quantidade = $(this).find('td:eq(1)').text();
            let valorUnitario = $(this).find('td:eq(2)').text().replace('R$', '');

            itens.push({
                produto_id: produtoId,
                quantidade: parseInt(quantidade),
                valor_unitario: parseFloat(valorUnitario),
                valor_total: parseFloat(valorUnitario) * parseInt(quantidade)
            });
        });

        let orcamento = {
            numero: numeroOrcamento,
            cliente_id: clienteId,
            total: totalOrcamento,
            itens: itens
        };

        $.ajax({
            url: '../relatorios/salvarOrcamento.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(orcamento),
            success: function (response) {
                if (response.success) {
                    alert('Orçamento salvo com sucesso!');
                    window.location.href = '../relatorios/listar.php';
                } else {
                    alert('Erro ao salvar orçamento.');
                }
            },
            error: function () {
                alert('Erro ao conectar com o servidor.');
            }
        });
    });

    // Transformar Orçamento em Pedido
    $('#transformar_pedido').click(function () {
        let orcamentoId = $('#numero_orcamento').data('id');

        $.post('../relatorios/transformarPedido.php', { orcamento_id: orcamentoId }, function (response) {
            if (response.success) {
                alert('Orçamento transformado em pedido com sucesso!');
                window.location.href = '../pedidos/listar.php';
            } else {
                alert(response.message || 'Erro ao transformar orçamento em pedido.');
            }
        }, 'json');
    });
});
