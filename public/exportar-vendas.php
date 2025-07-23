<?php
require_once '../src/Model/Venda.php';
require_once '../src/Database/Conexao.php';

use App\Model\Venda;

$vendaModel = new Venda();
$dataInicio = $_GET['data_inicio'] ?? '';
$dataFim = $_GET['data_fim'] ?? '';

if (!$dataInicio || !$dataFim) {
    die('Parâmetros de data inválidos.');
}

$vendas = $vendaModel->listarPorPeriodo($dataInicio, $dataFim);

// Cabeçalhos para Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=relatorioVendas_{$dataInicio}_a_{$dataFim}.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Estilo mínimo
echo "<table border='1'>";
echo "<thead>
    <tr style='background-color:#f2f2f2'>
        <th colspan='5'><strong>Relatorio de Vendas de $dataInicio ate $dataFim</strong></th>
    </tr>
    <tr>
        <th>ID</th>
        <th>Data</th>
        <th>Usuario</th>
        <th>Qtd. Itens</th>
        <th>Total (R$)</th>
    </tr>
</thead>";
echo "<tbody>";

$totalGeral = 0;
foreach ($vendas as $venda) {
    $totalGeral += $venda['total'];
    echo "<tr>
        <td>{$venda['id']}</td>
        <td>" . date('d/m/Y H:i', strtotime($venda['data_venda'])) . "</td>
        <td>" . htmlspecialchars($venda['nome_usuario']) . "</td>
        <td>{$venda['quantidade_itens']}</td>
        <td>" . number_format($venda['total'], 2, ',', '.') . "</td>
    </tr>";
}

echo "</tbody>";
echo "<tfoot>
    <tr>
        <td colspan='4' style='text-align:right'><strong>Total Geral:</strong></td>
        <td><strong>R$ " . number_format($totalGeral, 2, ',', '.') . "</strong></td>
    </tr>
</tfoot>";
echo "</table>";