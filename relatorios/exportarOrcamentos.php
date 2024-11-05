<?php
session_start();
include '../db.php';
require '../fpdf/fpdf.php';

if (!isset($_GET_ALL['id'])) {
    echo "ID do orçamento não fornecido.";
    exit;
}

$id_orcamento = $_GET['id'];

// Consulta os detalhes do orçamento
$sqlOrcamento = "SELECT o.id, o.data_criacao, o.valor_total, c.nome AS cliente_nome
                 FROM orcamentos o
                 JOIN clientes c ON o.id_cliente = c.id
                 WHERE o.id = :id";
$stmtOrcamento = $pdo->prepare($sqlOrcamento);
$stmtOrcamento->execute(['id' => $id_orcamento]);
$orcamento = $stmtOrcamento->fetch(PDO::FETCH_ASSOC);

if (!$orcamento) {
    echo "Orçamento não encontrado.";
    exit;
}

// Consulta os itens do orçamento
$sqlItens = "SELECT p.nome, io.quantidade, io.valor_unitario
             FROM itens_orcamento io
             JOIN produtos p ON io.id_produto = p.id
             WHERE io.id_orcamento = :id_orcamento";
$stmtItens = $pdo->prepare($sqlItens);
$stmtItens->execute(['id_orcamento' => $id_orcamento]);
$itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

// Configuração do PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(0, 10, 'Orcamento ID: ' . $orcamento['id'], 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, 'Cliente: ' . $orcamento['cliente_nome'], 0, 1);
$pdf->Cell(0, 10, 'Data: ' . date('d/m/Y', strtotime($orcamento['data_criacao'])), 0, 1);
$pdf->Ln(10);

// Tabela de itens
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(90, 10, 'Produto', 1);
$pdf->Cell(30, 10, 'Quantidade', 1);
$pdf->Cell(30, 10, 'Valor Unitario', 1);
$pdf->Cell(30, 10, 'Total', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
foreach ($itens as $item) {
    $total = $item['quantidade'] * $item['valor_unitario'];
    $pdf->Cell(90, 10, $item['nome'], 1);
    $pdf->Cell(30, 10, $item['quantidade'], 1, 0, 'C');
    $pdf->Cell(30, 10, 'R$ ' . number_format($item['valor_unitario'], 2, ',', '.'), 1);
    $pdf->Cell(30, 10, 'R$ ' . number_format($total, 2, ',', '.'), 1);
    $pdf->Ln();
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Valor Total: R$ ' . number_format($orcamento['valor_total'], 2, ',', '.'), 0, 1, 'R');

$pdf->Output('I', 'Orcamento_' . $orcamento['id'] . '.pdf');
?>
