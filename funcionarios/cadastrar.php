<?php
session_start();
include '../db.php'; // Conexão com o banco de dados
include '../funcoes.php'; // Inclui a função para exibir botões de navegação

// Verificar se o usuário está logado e se tem permissão de administrador (opcional)
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Verificar se o formulário de cadastro foi submetido
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

        // Inserir o novo funcionário no banco de dados
        $sql = "INSERT INTO funcionarios (nome, usuario, senha) VALUES (:nome, :usuario, :senha)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                'nome' => $nome,
                'usuario' => $usuario,
                'senha' => $senhaHash
            ]);
            $sucesso = "Funcionário cadastrado com sucesso!";
        } catch (PDOException $e) {
            // Mensagem de erro caso o usuário já exista
            if ($e->getCode() === '23000') { // Código de erro para chave duplicada (usuario)
                $erro = "Nome de usuário já existe. Escolha outro.";
            } else {
                $erro = "Erro ao cadastrar funcionário: " . $e->getMessage();
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
    <title>Cadastrar Funcionário</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Cadastrar Novo Funcionário</h2>
        <?php if (isset($erro)): ?>
            <p style="color: red;"><?= $erro ?></p>
        <?php endif; ?>
        <?php if (isset($sucesso)): ?>
            <p style="color: green;"><?= $sucesso ?></p>
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
            
            <!-- Adicionado nivel de acesso do colaborador ao sistema v1-->
            <label label for="nivel_acesso">Nível de Acesso:</label>
            <select id="nivel_acesso" name="nivel_acesso" required>
                 <option value="colaborador">Colaborador</option>
                 <option value="admin">Administrador</option>
            </select>

            <button type="submit">Cadastrar Funcionário</button>
        </form>
        
        <!-- Exibir Botões de Navegação -->
        <?php exibirBotoesNavegacao(); ?>
        
        
    </div>
</body>
</html>
