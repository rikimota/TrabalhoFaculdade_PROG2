<?php
require_once './verifica-login.php';
$usuarioLogado = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">Controle de Estoque</a>

            <!-- Botão toggle para telas pequenas -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarConteudo" aria-controls="navbarConteudo" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Itens da navbar -->
            <div class="collapse navbar-collapse" id="navbarConteudo">
                <!-- Menu centralizado -->
                <ul class="navbar-nav mx-auto">
                    <?php if ($usuarioLogado): ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="produtos.php">Produtos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="categorias.php">Categorias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="relatorio-venda.php">Vendas</a>
                        </li>
                        <?php if ($usuarioLogado['nivel'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="usuarios.php">Usuários</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>

                <!-- Info do usuário à direita -->
                <ul class="navbar-nav align-items-center">
                    <?php if ($usuarioLogado): ?>
                        <li class="nav-item me-3 text-white">
                            Olá, <strong><?= htmlspecialchars($usuarioLogado['nome']) ?></strong>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Conteúdo principal -->
    <main class="container mt-5 pt-4 mb-5">
        <?php
        if (isset($conteudo)) {
            echo $conteudo;
        } else {
            echo "<p>Bem-vindo ao sistema!</p>";
        }
        ?>
    </main>

    <!-- Rodapé fixado ao fundo -->
    <footer class="text-center text-muted py-3 bg-light border-top mt-auto">
        <small>&copy; <?php echo date("Y"); ?> - Sistema de Controle de Estoque</small>
    </footer>

    <script>
        (function () {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
            })
        })()
    </script>

</body>
</html>
