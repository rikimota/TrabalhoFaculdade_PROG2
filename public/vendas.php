<?php
require_once '../src/Database/Conexao.php';
require_once '../src/Model/Venda.php';
require_once './verifica-login.php';

use App\Model\Venda;

$vendaModel = new Venda();
$vendas = $vendaModel->listarComResumo();

ob_start();
?>

<div class="container mt-5 pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Vendas Registradas</h2>
        <a href="registrar-venda.php" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Nova Venda
        </a>
    </div>

    <?php if (count($vendas) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Usuário</th>
                        <th>Itens</th>
                        <th>Total (R$)</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendas as $venda): ?>
                        <tr>
                            <td><?= $venda['id'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($venda['data_venda'])) ?></td>
                            <td><?= htmlspecialchars($venda['nome_usuario']) ?></td>
                            <td><?= $venda['quantidade_itens'] ?></td>
                            <td><?= number_format($venda['total'], 2, ',', '.') ?></td>
                            <td>
                                <a href="detalhes-venda.php?id=<?= $venda['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Detalhes
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Nenhuma venda registrada até o momento.
        </div>
    <?php endif; ?>
</div>

<?php
$conteudo = ob_get_clean();
include 'template.php';