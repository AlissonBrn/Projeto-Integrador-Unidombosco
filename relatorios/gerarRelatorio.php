<?php
require_once('../tcpdf/tcpdf.php'); // Biblioteca TCPDF para geração de PDF

// Configurações de layout e conteúdo do relatório
// Obter dados do banco e configurar a saída do relatório

$pdf = new TCPDF();
$pdf->AddPage();
// Adicionar título e conteúdo
$pdf->Output('relatorio.pdf', 'D'); // Baixar PDF
?>
