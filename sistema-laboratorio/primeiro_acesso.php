<?php
include 'db.php'; // Conexão com o banco de dados

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmar_senha'];

    // Verificar se as senhas coincidem
    if ($senha !== $confirmarSenha) {
        $erro = "As senhas não coincidem.";
    } else {
        // Hash da senha para segurança
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir o novo administrador no banco de dados
        $sql = "INSERT INTO funcionarios (nome, usuario, senha, nivel_acesso) VALUES (:nome, :usuario, :senha, 'admin')";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                'nome' => $nome,
                'usuario' => $usuario,
                'senha' => $senhaHash
            ]);
            $sucesso = "Administrador cadastrado com sucesso! Faça login.";
            header("Location: login.php"); // Redirecionar para o login após o cadastro
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // Código de erro para chave duplicada (usuario)
                $erro = "Nome de usuário já existe. Escolha outro.";
            } else {
                $erro = "Erro ao cadastrar administrador: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Administrador</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Cadastro do Primeiro Administrador</h2>
        <p>Por favor, cadastre o primeiro administrador para acessar o sistema.</p>
        <?php if (isset($erro)): ?>
            <p style="color: red;"><?= $erro ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="usuario">Nome de Usuário:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <label for="confirmar_senha">Confirmar Senha:</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required>

            <button type="submit">Cadastrar Administrador</button>
        </form>
    </div>
</body>
</html>
