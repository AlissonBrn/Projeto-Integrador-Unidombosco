<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

// Verificar se o formulário de login foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Buscar o colaborador no banco de dados pelo usuário
    $sql = "SELECT * FROM funcionarios WHERE usuario = :usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['usuario' => $usuario]);
    $funcionario = $stmt->fetch();

    // Verificar se o usuário foi encontrado e se a senha está correta
    if ($funcionario && password_verify($senha, $funcionario['senha'])) {
        // Armazenar o ID, nome e nível de acesso do colaborador na sessão
        $_SESSION['id'] = $funcionario['id'];
        $_SESSION['nome'] = $funcionario['nome'];
        $_SESSION['nivel_acesso'] = $funcionario['nivel_acesso'];
        
        // Redirecionar para a página inicial ou administrativa, dependendo do nível de acesso
        if ($_SESSION['nivel_acesso'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        // Mensagem de erro caso as credenciais estejam incorretas
        $erro = "Usuário ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Vendas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($erro)): ?>
            <p style="color: red;"><?= $erro ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" required>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
