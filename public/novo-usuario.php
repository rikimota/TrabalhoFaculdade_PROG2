<?php
require_once '../src/Database/Conexao.php';
require_once '../src/Model/Usuario.php';
require_once './verifica-login.php';

// Deixa que apenas usuarios administradores acessem essa funcionalidade
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel'] !== 'admin') {
    header('Location: produtos.php');
    exit;
}

use App\Model\Usuario;

//limpa o buffer
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Novo Usuário</h2>
    <a href="usuarios.php" class="btn btn-secondary">Voltar</a>
</div>

<form action="salvar-usuario.php" method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>

    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="telefone" name="telefone" required>
    </div>

    <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" class="form-control" id="senha" name="senha" required>
    </div>

    <div class="mb-3">
        <label for="nivel" class="form-label">Nível de Acesso</label>
        <select class="form-select" id="nivel" name="nivel">
            <option value="usuario" selected>Usuário</option>
            <option value="admin">Administrador</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Salvar Usuário</button>
</form>

<?php
$conteudo = ob_get_clean();
include 'template.php';