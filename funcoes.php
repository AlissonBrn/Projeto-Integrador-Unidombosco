<?php
// Função para exibir botões de navegação
function exibirBotoesNavegacao() {
    echo '
    <div class="d-flex justify-content-between my-4">
        <button onclick="window.history.back()" class="btn btn-secondary">Voltar</button>
        <a href="/index.php" class="btn btn-primary">Início</a>
    </div>';
}
?>
