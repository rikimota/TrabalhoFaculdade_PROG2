<?php
require_once '../src/Model/Usuario.php';
require_once '../src/Database/Conexao.php';
require_once './verifica-login.php';
require_once '../src/Model/Produto.php';
require_once '../src/Model/Categoria.php';

$usuarioLogado = $_SESSION['usuario'];

use App\Model\Produto;
use App\Model\Categoria;
use App\Model\Usuario;

$produtoModel = new Produto();
$categoriaModel = new Categoria();
$usuarioModel = new Usuario();

$totalProdutos = $produtoModel->contar();
$totalCategorias = $categoriaModel->contar();
$totalUsuarios = $usuarioModel->contar();
$totalAdmins = $usuarioModel->contar('admin'); // Exemplo com filtro

// Consulta para pegar a contagem
$dadosPorCategoria = $produtoModel->contarPorCategoria();
$statusEstoque = $produtoModel->contarPorStatusEstoque();

// Prepara os dados para o gráfico
$categorias = [];
$quantidades = [];

foreach ($dadosPorCategoria as $item) {
    $categorias[] = $item['categoria_nome'];
    $quantidades[] = $item['total'];
}

ob_start();
?>

<div class="container mt-5 pt-4">
    <h2 class="mb-4">Dashboard</h2>

    <div class="row g-4">
        <!-- Card Produtos -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Produtos</h5>
                        <p class="card-text fs-4"><?= $totalProdutos ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Categorias -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-tags" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Categorias</h5>
                        <p class="card-text fs-4"><?= $totalCategorias ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Usuários -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-warning text-dark">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Usuários</h5>
                        <p class="card-text fs-4"><?= $totalUsuarios ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRAFICOS -->
        <div class="row mt-5 g-4">
            <!-- Gráfico de Categorias -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Produtos por Categoria</h5>
                        <canvas id="graficoCategorias" style="height: 200px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Estoque -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Status do Estoque</h5>
                        <canvas id="graficoEstoque" style="height: 200px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const ctx = document.getElementById('graficoCategorias').getContext('2d');
            const graficoCategorias = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($categorias) ?>,
                    datasets: [{
                        label: 'Total de Produtos',
                        data: <?= json_encode($quantidades) ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom', // ou 'top', 'left', 'right'
                            labels: {
                                boxWidth: 20,
                                padding: 15,
                                color: '#333',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.parsed.y} produtos`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        </script>
        
        <script>
            const ctxEstoque = document.getElementById('graficoEstoque').getContext('2d');
            const graficoEstoque = new Chart(ctxEstoque, {
                type: 'doughnut',
                data: {
                    labels: ['Estoque Baixo', 'Estoque Médio', 'Estoque OK'],
                    datasets: [{
                        label: 'Produtos por status de estoque',
                        data: [<?= $statusEstoque['baixo'] ?>, <?= $statusEstoque['medio'] ?>, <?= $statusEstoque['ok'] ?>],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',   // vermelho
                            'rgba(255, 206, 86, 0.7)',   // amarelo
                            'rgba(75, 192, 192, 0.7)'    // verde
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'right',
                            labels: {
                                boxWidth: 20,
                                padding: 15,
                                color: '#333',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    return `${label}: ${value} produtos`;
                                }
                            }
                        }
                    }
                }
            });
        </script>
    </div>
</div>

<?php
$conteudo = ob_get_clean();
include 'template.php';