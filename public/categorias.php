<?php
require_once '../src/Model/Categoria.php';
require_once '../src/Database/Conexao.php';
require_once './verifica-login.php';

use App\Model\Categoria;

$categoria = new Categoria();
$categorias = $categoria->listar();

$titulo = "Categorias";
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Categorias</h2>
    <a href="nova-categoria.php" class="btn btn-success">+ Nova Categoria</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categorias as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= $c['nome'] ?></td>
                <td>
                    <a href="editar-categoria.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                    <form action="excluir-categoria.php" method="POST" class="d-inline"
                        onsubmit="return confirm('Deseja realmente excluir esta categoria?');">
                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$conteudo = ob_get_clean();
include 'template.php';
?>