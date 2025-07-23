<?php
require_once '../src/Model/Produto.php';
require_once '../src/Database/Conexao.php';
require_once './verifica-login.php';

use App\Model\Produto;

$produto = new Produto();
$busca = $_GET['busca'] ?? '';
$filtro = $_GET['filtro'] ?? '';
$produtos = $produto->listar($busca, $filtro);

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Produtos</h2>
    <a href="novo-produto.php" class="btn btn-success">+ Novo Produto</a>
</div>

<form method="GET" class="mb-4 d-flex flex-wrap gap-2 align-items-end" style="max-width: 600px;">
    <div class="flex-grow-1">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por nome" value="<?= $_GET['busca'] ?? '' ?>">
    </div>

    <div>
        <select name="filtro" class="form-select">
            <option value="">Todos os produtos</option>
            <option value="baixo" <?= (($_GET['filtro'] ?? '') === 'baixo') ? 'selected' : '' ?>>Apenas com baixo estoque</option>
        </select>
    </div>

    <div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </div>
</form>

<table class="table table-bordered table-striped">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Categoria</th>
            <th style="width: 160px;">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $p): ?>
            <?php $classeEstoque = ($p['quantidade'] < 5) ? 'table-danger' : ''; ?>
            <tr class="<?= $classeEstoque ?>">
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['nome']) ?></td>
                <td><?= nl2br(htmlspecialchars($p['descricao'])) ?></td>
                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                <td>
                    <?php if ($p['quantidade'] < 5): ?>
                        <span class="text-danger fw-bold"><?= $p['quantidade'] ?> (Baixo)</span>
                    <?php else: ?>
                        <?= $p['quantidade'] ?>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($p['categoria_nome'] ?? '—') ?></td>
                <td>
                    <a href="editar-produto.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <form action="excluir-produto.php" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
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
