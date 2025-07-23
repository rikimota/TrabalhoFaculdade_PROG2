<?php
require_once './verifica-login.php';

$titulo = "Nova Categoria";
ob_start();
?>

<h2>Cadastrar Nova Categoria</h2>

<form action="salvar-categoria.php" method="POST" class="mt-4">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome da Categoria</label>
        <input type="text" name="nome" id="nome" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="categorias.php" class="btn btn-secondary">Voltar</a>
</form>

<?php
$conteudo = ob_get_clean();
include 'template.php';
?>