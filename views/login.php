<?php
session_start();
require_once __DIR__ . '/../includes/conexao.php';

$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    try {
        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $stmt->execute([':usuario' => $usuario]);
        $usuarioBanco = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuarioBanco && password_verify($senha, $usuarioBanco['senha'])) {
            $_SESSION['usuario_logado'] = true;
            $_SESSION['usuario_tipo'] = $usuarioBanco['tipo'];
             $_SESSION['usuario'] = $usuarioBanco['usuario'];
            header('Location: ../index.php');
            exit;
        } else {
            $mensagemErro = 'Usuário ou senha inválidos.';
        }
    } catch (PDOException $e) {
        $mensagemErro = 'Erro ao verificar login: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #88a3ff, #5ac8fa);
            font-family: sans-serif;
        }

        form {
            max-width: 400px;
            padding: 30px;
            background-color: #ffffffd9;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-weight: bold;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 12px 10px;
            margin-bottom: 20px;
            border: 2px solid #e1e1e1;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #5cb85c;
        }

        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 1.1rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #4cae4c;
        }

        p[style="color: red;"] {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Login</h1>
    <?php if ($mensagemErro): ?>
        <p style="color: red;"><?php echo $mensagemErro; ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" required><br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" required><br>

        <input type="submit" value="Entrar">
    </form>
</body>
</html>