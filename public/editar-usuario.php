<?php
require_once '../src/Model/Usuario.php';
require_once '../src/Database/Conexao.php';
require_once './verifica-login.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel'] !== 'admin') {
    header('Location: produtos.php');
    exit;
}

use App\Model\Usuario;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario = new Usuario();
    
    // Busca as informações do usuário
    $usuarioData = $usuario->buscarPorId($id);
    
    // Se não encontrar o usuário
    if (!$usuarioData) {
        $_SESSION['erro'] = "Usuário não encontrado!";
        header('Location: usuarios.php');
        exit;
    }
}

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Editar Usuário</h2>
    <a href="usuarios.php" class="btn btn-secondary">Voltar</a>
</div>

<form action="salvar-usuario.php" method="POST">
    <input type="hidden" name="id" value="<?= $usuarioData['id'] ?>">

    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuarioData['nome']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuarioData['email']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($usuarioData['telefone']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="nivel" class="form-label">Nível</label>
        <select class="form-select" id="nivel" name="nivel" required>
            <option value="admin" <?= $usuarioData['nivel'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
            <option value="usuario" <?= $usuarioData['nivel'] == 'usuario' ? 'selected' : '' ?>>Usuário</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Salvar Alterações</button>
</form>

<?php
$conteudo = ob_get_clean();
include 'template.php';
?>
