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
        $_SESSION['id'] = $funcionario['id'];
        $_SESSION['nome'] = $funcionario['nome'];
        $_SESSION['nivel_acesso'] = $funcionario['nivel_acesso'];
        
        header("Location: index.php");
        exit;
    } else {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: url('img_lib/background.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .login-container {
            width: 100%;
            max-width: 800px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .login-header {
            text-align: center;
        }
        .login-header img {
            display: block;
            margin: 0 auto;
            max-width: 150px;
            margin-bottom: 20px;
        }
        .login-header h2 {
            color: #007bff;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .demo-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        footer {
            margin-top: auto;
            padding: 10px 0;
            background: rgba(255, 255, 255, 0.8);
            width: 100%;
            text-align: center;
            position: relative;
            bottom: 0;
            font-size: 0.9rem;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <!-- Logotipo -->
            <img src="img_lib/logo.png" alt="Logotipo do Sistema">
            <h2>Sistema de Vendas para Laboratórios</h2>
        </div>

        <div class="row">
            <!-- Formulário de login -->
            <div class="col-md-6">
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger"><?= $erro ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuário:</label>
                        <input type="text" id="usuario" name="usuario" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha:</label>
                        <input type="password" id="senha" name="senha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>

            <!-- Informações de demonstração -->
            <div class="col-md-6 demo-info">
                <h5 class="text-center">Versão de demonstração</h5>
                <p>Utilize o seguinte usuário e senha para acessar o sistema:</p>
                <ul>
                    <li><strong>Usuário:</strong> teste1</li>
                    <li><strong>Senha:</strong> Teste1@</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Rodapé com os créditos -->
    <footer>
        Projeto criado por Alisson para fins educacionais.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
