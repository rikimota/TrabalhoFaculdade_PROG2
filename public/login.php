<?php
session_start();
ob_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
        }
        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background-color: white;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center bg-light" style="height: 100vh;">
    <div class="login-box">
        <h2 class="text-center mb-4">Acesso ao Sistema</h2>
        <?php if (isset($_SESSION['erro_login'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="autenticar.php">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</body>
</html>