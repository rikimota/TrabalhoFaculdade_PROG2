<?php

use App\Model\Produto;
require_once '../src/Model/Produto.php';
require_once '../src/Database/Conexao.php';

$produto = new Produto();
$produtos = $produto->listarTodos();

?>

<h2 class="mb-4">Lista de Produtos</h2>

<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nome']) ?></td>
                <td><?= htmlspecialchars($p['descricao']) ?></td>
                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                <td><?= $p['quantidade'] ?></td>
                <td>
                    <a href="editar-produto.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                    <form action="excluir-produto.php" method="POST" class="d-inline"
                        onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>