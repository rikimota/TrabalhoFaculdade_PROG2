<?php
require_once '../src/Model/Produto.php';
require_once '../src/Model/Categoria.php';
require_once '../src/Database/Conexao.php';
require_once './verifica-login.php';

use App\Model\Produto;
use App\Model\Categoria;

if (!isset($_GET['id'])) {
    header('Location: produtos.php');
    exit;
}

$produto = new Produto();
$prod = $produto->buscarPorId($_GET['id']);

$categoria = new Categoria();
$categorias = $categoria->listar();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: produtos.php');
    exit;
}

if (!$produto) {
    header('Location: produtos.php');
}

$dados = $_SESSION['dados'] ?? $produto;
unset($_SESSION['dados']);

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Editar Produto</h2>
    <a href="produtos.php" class="btn btn-secondary">Voltar</a>
</div>

<?php if (isset($_SESSION['erros']) && is_array($_SESSION['erros'])): ?>
    <div class="alert alert-danger">
        <h5>Erros ao atualizar o produto:</h5>
        <ul class="mb-0">
            <?php foreach ($_SESSION['erros'] as $erro): ?>
                <li><?= htmlspecialchars($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['erros']); ?>
<?php endif; ?>

<form action="salvar-produto.php" method="POST">
    <input type="hidden" name="id" value="<?= $prod['id'] ?>">

    <div class="mb-3">
        <label for="nome" class="form-label">Nome do Produto</label>
        <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($prod['nome']) ?>" required>
        <div class="invalid-feedback">
            Por favor, informe o nome do produto.
        </div>
    </div>

    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea name="descricao" id="descricao" class="form-control" rows="3" required><?= htmlspecialchars($prod['descricao']) ?></textarea>
        <div class="invalid-feedback">
            Descrição é obrigatória.
        </div>
    </div>

    <div class="mb-3">
        <label for="preco" class="form-label">Preço</label>
        <input type="number" step="0.01" name="preco" id="preco" class="form-control" value="<?= $prod['preco'] ?>" required>
        <div class="invalid-feedback">
            Informe um preço válido.
        </div>
    </div>

    <div class="mb-3">
        <label for="quantidade" class="form-label">Quantidade</label>
        <input type="number" name="quantidade" id="quantidade" class="form-control" value="<?= $prod['quantidade'] ?>" required>
        <div class="invalid-feedback">
            Quantidade é obrigatória.
        </div>
    </div>

    <div class="mb-3">
        <label for="id_categoria" class="form-label">Categoria</label>
        <select name="id_categoria" id="id_categoria" class="form-select" required>
            <option value="">-- Selecione uma categoria --</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $prod['id_categoria'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="invalid-feedback">
            Selecione uma categoria.
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Atualizar Produto</button>
</form>

<?php
$conteudo = ob_get_clean();
include 'template.php';
