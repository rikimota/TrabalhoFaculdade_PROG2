<?php
require_once '../src/Model/Categoria.php';
require_once '../src/Database/Conexao.php';
require_once './verifica-login.php';

use App\Model\Categoria;

if (!isset($_GET['id'])) {
    header('Location: categorias.php');
    exit;
}

$categoria = new Categoria();
$dado = $categoria->buscarPorId($_GET['id']);

if (!$dado) {
    header('Location: categorias.php');
    exit;
}

$titulo = "Editar Categoria";
ob_start();
?>

<form action="atualizar-categoria.php" method="POST" class="mt-4">
    <input type="hidden" name="id" value="<?= $dado['id'] ?>">

    <div class="mb-3">
        <label for="nome" class="form-label">Nome da Categoria</label>
        <input type="text" name="nome" id="nome" class="form-control" value="<?= $dado['nome'] ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Atualizar</button>
    <a href="categorias.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php
$conteudo = ob_get_clean();
include 'template.php';
?>