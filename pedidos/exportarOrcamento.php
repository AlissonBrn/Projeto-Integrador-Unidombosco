<?php
session_start();
require '../db.php'; // Conexão com o banco de dados
require '../fpdf/fpdf.php'; // Biblioteca FPDF para geração de PDF

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Obter o ID do orçamento
$id_orcamento = $_GET['id'] ?? null;

// Buscar informações do orçamento e do cliente
$sql_orcamento = "
    SELECT o.*, c.nome AS cliente_nome, c.endereco AS cliente_endereco
    FROM orcamentos o
    JOIN clientes c ON o.id_cliente = c.id
    WHERE o.id = :id";
$stmt = $pdo->prepare($sql_orcamento);
$stmt->execute(['id' => $id_orcamento]);
$orcamento = $stmt->fetch();

if (!$orcamento) {
    echo "Orçamento não encontrado.";
    exit;
}

// Buscar produtos do orçamento
$sql_produtos = "
    SELECT p.nome, p.marca, io.quantidade, io.valor_unitario
    FROM itens_orcamento io
    JOIN produtos p ON io.id_produto = p.id
    WHERE io.id_orcamento = :id";
$stmt = $pdo->prepare($sql_produtos);
$stmt->execute(['id' => $id_orcamento]);
$produtos = $stmt->fetchAll();

// Criar o PDF do orçamento
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Cabeçalho
$pdf->Cell(0, 10, 'Orçamento - Sistema de Vendas', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10);
$pdf->Cell(0, 10, "Número do Orçamento: {$orcamento['numero_orcamento']}", 0, 1);
$pdf->Cell(0, 10, "Data: " . date('d/m/Y', strtotime($orcamento['data'])), 0, 1);
$pdf->Cell(0, 10, "Cliente: {$orcamento['cliente_nome']}", 0, 1);
$pdf->Cell(0, 10, "Endereço do Cliente: {$orcamento['cliente_endereco']}", 0, 1);
$pdf->Cell(0, 10, "Validade: {$orcamento['validade']} dias", 0, 1);
$pdf->Cell(0, 10, "Prazo de Entrega: {$orcamento['prazo_entrega']}", 0, 1);
$pdf->Cell(0, 10, "Valor Total: R$ " . number_format($orcamento['valor_total'], 2, ',', '.'), 0, 1);
$pdf->Ln(10);

// Tabela de produtos
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Produto', 1);
$pdf->Cell(30, 10, 'Marca', 1);
$pdf->Cell(20, 10, 'Qtd', 1);
$pdf->Cell(40, 10, 'Valor Unitario', 1);
$pdf->Cell(40, 10, 'Subtotal', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
foreach ($produtos as $produto) {
    $subtotal = $produto['valor_unitario'] * $produto['quantidade'];
    $pdf->Cell(60, 10, $produto['nome'], 1);
    $pdf->Cell(30, 10, $produto['marca'], 1);
    $pdf->Cell(20, 10, $produto['quantidade'], 1);
    $pdf->Cell(40, 10, 'R$ ' . number_format($produto['valor_unitario'], 2, ',', '.'), 1);
    $pdf->Cell(40, 10, 'R$ ' . number_format($subtotal, 2, ',', '.'), 1);
    $pdf->Ln();
}

// Output do PDF
$pdf->Output('I', "Orcamento_{$orcamento['numero_orcamento']}.pdf");
?>
