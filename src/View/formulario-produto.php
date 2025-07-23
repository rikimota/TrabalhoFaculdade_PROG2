<?php
$edicao = isset($dados);
?>

<h2 class="mb-4"><?= $edicao ? 'Editar Produto' : 'Cadastrar Novo Produto' ?></h2>

<form action="<?= $edicao ? 'atualizar-produto.php' : 'salvar-produto.php' ?>" method="POST">
    <?php if ($edicao): ?>
        <input type="hidden" name="id" value="<?= $dados['id'] ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label for="nome" class="form-label">Nome do Produto</label>
        <input type="text" class="form-control" name="nome" id="nome"
               value="<?= $edicao ? htmlspecialchars($dados['nome']) : '' ?>" required>
    </div>

    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea class="form-control" name="descricao" id="descricao"><?= $edicao ? htmlspecialchars($dados['descricao']) : '' ?></textarea>
    </div>

    <div class="mb-3">
        <label for="preco" class="form-label">Preço (R$)</label>
        <input type="number" class="form-control" name="preco" id="preco"
               step="0.01" value="<?= $edicao ? $dados['preco'] : '' ?>" required>
    </div>

    <div class="mb-3">
        <label for="quantidade" class="form-label">Quantidade</label>
        <input type="number" class="form-control" name="quantidade" id="quantidade"
               value="<?= $edicao ? $dados['quantidade'] : '' ?>" required>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="produtos.php" class="btn btn-secondary">Cancelar</a>
</form>
