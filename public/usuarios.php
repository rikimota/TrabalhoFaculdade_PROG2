<?php
require_once './verifica-login.php';
require_once '../src/Database/Conexao.php';
require_once '../src/Model/Usuario.php';

// apenas usuarios administradores podem acessar esta pagina
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel'] !== 'admin') {
    header('Location: produtos.php');
    exit;
}

use App\Model\Usuario;

$usuario = new Usuario();
$busca = $_GET['busca'] ?? '';
$usuarios = $usuario->listar($busca);

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Usuários</h2>
    <?php if ($_SESSION['usuario']['nivel'] === 'admin'): ?>
        <a href="novo-usuario.php" class="btn btn-success">+ Novo Usuário</a>
    <?php endif; ?>
</div>

<form method="GET" class="mb-4 d-flex" style="max-width: 400px;">
    <input type="text" name="busca" class="form-control me-2" placeholder="Usuario ou Email" value="<?= $_GET['busca'] ?? '' ?>">
    <button type="submit" class="btn btn-primary">Buscar</button>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Nivel</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['nome']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['telefone']) ?></td>
                <td><?= htmlspecialchars($u['nivel']) ?></td>
                <td>
                    <?php if ($_SESSION['usuario']['nivel'] === 'admin'): ?>
                    <a href="editar-usuario.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                    <form action="excluir-usuario.php" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$conteudo = ob_get_clean();
include 'template.php';