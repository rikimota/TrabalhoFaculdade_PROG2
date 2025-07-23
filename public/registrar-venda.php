<?php
require_once '../src/Database/Conexao.php';
require_once '../src/Model/Produto.php';
require_once '../src/Model/Venda.php';
require_once '../src/Model/ItemVenda.php';
require_once './verifica-login.php';

use App\Model\Produto;
use App\Model\Venda;
use App\Model\ItemVenda;

$produtoModel = new Produto();
$vendaModel = new Venda();
$itemVendaModel = new ItemVenda();

$produtos = $produtoModel->listar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['usuario']['id'];
    $produtosSelecionados = $_POST['produtos'];
    $itens = [];

    foreach ($produtosSelecionados as $item) {
        if (!empty($item['id']) && intval($item['quantidade']) > 0) {
            $itens[] = [
                'produto_id' => $item['id'],
                'quantidade' => intval($item['quantidade'])
            ];
        }
    }

    try {
        $id_venda = $vendaModel->registrarVendaCompleta($id_usuario, $itens);
        header('Location: relatorio-venda.php');
        exit;
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}


ob_start();
?>

<div class="container mt-5 pt-4">
    <h2 class="mb-4">Registrar Venda</h2>

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="post" id="formVenda">
        <div id="listaProdutos"></div>

        <button type="button" class="btn btn-secondary mt-3" onclick="adicionarProduto()">Adicionar Produto</button>

        <div class="mt-3">
            <h5>Total: R$ <span id="totalVenda">0.00</span></h5>
        </div>

        <button type="submit" class="btn btn-success mt-3">Finalizar Venda</button>
    </form>
</div>

<script>
    const produtos = <?= json_encode($produtos) ?>;
    let contador = 0;

    function adicionarProduto() {
        const container = document.getElementById('listaProdutos');

        const div = document.createElement('div');
        div.classList.add('row', 'g-2', 'align-items-end', 'mb-3');

        div.innerHTML = `
            <div class="col-md-5">
                <label>Produto</label>
                <select class="form-select" name="produtos[${contador}][id]" onchange="atualizarPreco(this, ${contador})">
                    <option value="">Selecione</option>
                    ${produtos.map(p => `<option value="${p.id}" data-preco="${p.preco}">${p.nome}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-2">
                <label>Qtd.</label>
                <input type="number" name="produtos[${contador}][quantidade]" class="form-control" value="1" min="1" onchange="calcularTotal()">
            </div>
            <div class="col-md-3">
                <label>Preço Unit.</label>
                <input type="text" name="produtos[${contador}][preco]" class="form-control" readonly>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" onclick="this.closest('.row').remove(); calcularTotal()">Remover</button>
            </div>
        `;

        container.appendChild(div);
        contador++;
    }

    function atualizarPreco(select, index) {
        const preco = select.options[select.selectedIndex].getAttribute('data-preco');
        select.closest('.row').querySelector(`input[name="produtos[${index}][preco]"]`).value = preco;
        calcularTotal();
    }

    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('#listaProdutos .row').forEach(row => {
            const preco = parseFloat(row.querySelector('input[name$="[preco]"]').value) || 0;
            const qtd = parseInt(row.querySelector('input[name$="[quantidade]"]').value) || 0;
            total += preco * qtd;
        });
        document.getElementById('totalVenda').innerText = total.toFixed(2);
    }
</script>

<?php
$conteudo = ob_get_clean();
include 'template.php';
?>