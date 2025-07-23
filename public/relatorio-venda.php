<?php
require_once '../src/Model/Venda.php';
require_once './verifica-login.php';
require_once '../src/Database/Conexao.php';

use App\Model\Venda;

$vendaModel = new Venda();
$vendas = [];

$dataInicio = $_GET['data_inicio'] ?? '';
$dataFim = $_GET['data_fim'] ?? '';

if (!$dataInicio && !$dataFim) {
    $dataFim = date('Y-m-d');
    $dataInicio = date('Y-m-d', strtotime('-1 days'));
    $vendas = $vendaModel->listarPorPeriodo($dataInicio, $dataFim);
}

if ($dataInicio && $dataFim) {
    $vendas = $vendaModel->listarPorPeriodo($dataInicio, $dataFim);
}

ob_start();
?>

<div class="container mt-5 pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Relatório de Vendas por Período</h2>
        <a href="registrar-venda.php" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Nova Venda
        </a>
    </div>
    <hr class="mb-4">
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Data Início</label>
            <input type="date" name="data_inicio" class="form-control" value="<?= htmlspecialchars($dataInicio) ?>">
        </div>
        <div class="col-md-4">
            <label>Data Fim</label>
            <input type="date" name="data_fim" class="form-control" value="<?= htmlspecialchars($dataFim) ?>">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <a href="exportar-vendas.php?data_inicio=<?= $dataInicio ?>&data_fim=<?= $dataFim ?>" class="btn btn-success mb-3">
                Exportar para Excel
            </a>
        </div>
    </form>

    <?php if ($vendas): ?>
        <?php $totalGeral = 0; ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Usuário</th>
                    <th>Qtd. Itens</th>
                    <th>Total (R$)</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vendas as $venda): ?>
                    <?php $totalGeral += $venda['total']; ?>
                    <tr>
                        <td><?= $venda['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($venda['data_venda'])) ?></td>
                        <td><?= htmlspecialchars($venda['nome_usuario']) ?></td>
                        <td><?= $venda['quantidade_itens'] ?></td>
                        <td><?= number_format($venda['total'], 2, ',', '.') ?></td>
                        <td>
                            <a href="detalhes-venda.php?id=<?= $venda['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Ver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Total Vendido no Período:</th>
                    <th colspan="2">R$ <?= number_format($totalGeral, 2, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>
    <?php elseif ($dataInicio && $dataFim): ?>
        <div class="alert alert-warning">Nenhuma venda encontrada no período.</div>
    <?php endif; ?>
</div>

<?php
$conteudo = ob_get_clean();
include 'template.php';