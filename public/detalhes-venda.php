<?php
require_once '../src/Database/Conexao.php';
require_once '../src/Model/ItemVenda.php';
require_once '../src/Model/Venda.php';
require_once './verifica-login.php';

use App\Model\Venda;
use App\Model\ItemVenda;

$vendaModel = new Venda();
$itemVendaModel = new ItemVenda();

$idVenda = $_GET['id'] ?? null;

if (!$idVenda) {
    header('Location: vendas.php');
    exit;
}

$venda = $vendaModel->buscarPorId($idVenda);
$itens = $itemVendaModel->listarPorVenda($idVenda);

ob_start();
?>

<div class="container mt-5 pt-4">
    <h2>Detalhes da Venda #<?= $venda['id'] ?></h2>
    <p><strong>Data da Venda:</strong> <?= date('d/m/Y H:i', strtotime($venda['data'])) ?></p>
    <p><strong>Registrada por:</strong> <?= htmlspecialchars($venda['nome_usuario']) ?></p>

    <div class="table-responsive mt-4">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($itens as $item):
                    $subtotal = $item['preco_unitario'] * $item['quantidade'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['nome_produto']) ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total:</th>
                    <th>R$ <?= number_format($total, 2, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <a href="vendas.php" class="btn btn-secondary mt-3">Voltar</a>
</div>

<?php
$conteudo = ob_get_clean();
include 'template.php';
?>